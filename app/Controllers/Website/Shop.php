<?php

namespace App\Controllers\Website;

use App\Controllers\BaseController;

use App\Models\CategoryModel;
use App\Models\EnquiryModel;
use App\Models\ProductImageModel;
use App\Models\ProductModel;
use App\Models\SubcategoryModel;

class Shop extends BaseController
{
    public function index()
    {
        $cats  = new CategoryModel();
        $subs  = new SubcategoryModel();
        $model = new ProductModel();
        $model->select('products.*, categories.name AS category_name')
            ->join('categories', 'categories.id = products.category_id')
            ->where('products.status', 1);

        $catSlug = $this->request->getGet('cat');
        $subSlug = $this->request->getGet('sub');
        $q       = trim((string) $this->request->getGet('q'));
        $sort    = $this->request->getGet('sort');

        $activeCat = $activeSub = null;
        if ($catSlug && ($activeCat = $cats->where('slug', $catSlug)->first())) {
            $model->where('products.category_id', $activeCat['id']);
        }
        if ($subSlug && ($activeSub = $subs->where('slug', $subSlug)->first())) {
            $model->where('products.subcategory_id', $activeSub['id']);
        }
        if ($q !== '') {
            $model->groupStart()->like('products.name', $q)->orLike('products.short_desc', $q)->groupEnd();
        }
        match ($sort) {
            'price_asc'  => $model->orderBy('COALESCE(products.sale_price, products.price)', 'ASC', false),
            'price_desc' => $model->orderBy('COALESCE(products.sale_price, products.price)', 'DESC', false),
            'name'       => $model->orderBy('products.name', 'ASC'),
            default      => $model->orderBy('products.id', 'DESC'),
        };

        return view('website/shop/list', [
            'title'      => 'Shop',
            'products'   => $model->paginate(9),
            'pager'      => $model->pager,
            'categories' => $cats->where('status', 1)->findAll(),
            'subs'       => $subs->where('status', 1)->findAll(),
            'activeCat'  => $activeCat,
            'activeSub'  => $activeSub,
            'q'          => $q,
            'sort'       => $sort,
        ]);
    }

    public function view(string $slug)
    {
        $products = new ProductModel();
        $product  = $products->select('products.*, categories.name AS category_name, categories.slug AS category_slug')
            ->join('categories', 'categories.id = products.category_id')
            ->where('products.slug', $slug)->where('products.status', 1)->first();
        if (! $product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $related = (new ProductModel())->where('status', 1)
            ->where('category_id', $product['category_id'])->where('id !=', $product['id'])->findAll(4);

        // Main image first, then the extra gallery images
        $images = array_merge(
            $product['image'] ? [$product['image']] : [],
            array_column((new ProductImageModel())->forProduct((int) $product['id']), 'image')
        );

        return view('website/shop/view', ['title' => $product['name'], 'p' => $product, 'images' => $images, 'related' => $related]);
    }

    public function enquiry(int $id)
    {
        $rules = [
            'name'    => 'required|min_length[2]',
            'email'   => 'required|valid_email',
            'phone'   => 'permit_empty|max_length[20]',
            'message' => 'required|min_length[5]',
        ];
        $product = (new ProductModel())->find($id);
        if (! $product) {
            return redirect()->to(base_url('shop'));
        }
        $back = base_url('shop/' . $product['slug']) . '#enquiry';
        if (! $this->validate($rules)) {
            return redirect()->to($back)->withInput()->with('errors', $this->validator->getErrors());
        }
        (new EnquiryModel())->insert([
            'product_id' => $id,
            'name'       => $this->request->getPost('name'),
            'email'      => $this->request->getPost('email'),
            'phone'      => $this->request->getPost('phone'),
            'message'    => $this->request->getPost('message'),
            'is_read'    => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to($back)->with('success', 'Enquiry sent. We will get back to you shortly.');
    }
}
