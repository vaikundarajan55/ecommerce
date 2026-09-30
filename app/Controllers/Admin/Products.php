<?php

namespace App\Controllers\Admin;

use App\Libraries\Uploader;
use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\SubcategoryModel;

class Products extends AdminBase
{
    private array $rules = [
        'category_id' => 'required|is_natural_no_zero',
        'name'        => 'required|min_length[2]|max_length[200]',
        'price'       => 'required|decimal',
        'sale_price'  => 'permit_empty|decimal',
        'stock'       => 'required|integer',
        'short_desc'  => 'permit_empty|max_length[300]',
        'image'       => 'permit_empty|is_image[image]|max_size[image,3072]',
    ];

    public function index()
    {
        $model = new ProductModel();
        $q     = trim((string) $this->request->getGet('q'));
        $model->select('products.*, categories.name AS category_name, subcategories.name AS sub_name')
            ->join('categories', 'categories.id = products.category_id')
            ->join('subcategories', 'subcategories.id = products.subcategory_id', 'left');
        if ($q !== '') {
            $model->groupStart()->like('products.name', $q)->orLike('products.sku', $q)->groupEnd();
        }
        return $this->render('products/index', [
            'title' => 'Products',
            'rows'  => $model->orderBy('products.id', 'DESC')->paginate(10),
            'pager' => $model->pager,
            'q'     => $q,
        ]);
    }

    public function create()
    {
        return $this->render('products/form', $this->formData('Add product', null));
    }

    public function store()
    {
        if (! $this->validate($this->rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $model = new ProductModel();
        $data  = $this->collect();
        $data['slug']  = $this->uniqueSlug($model, $data['name']);
        $data['image'] = Uploader::image($this->request->getFile('image'), 'products');
        $model->insert($data);
        return redirect()->to(base_url('admin/products'))->with('success', 'Product added.');
    }

    public function edit(int $id)
    {
        $row = (new ProductModel())->find($id) ?? throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return $this->render('products/form', $this->formData('Edit product', $row));
    }

    public function update(int $id)
    {
        $model = new ProductModel();
        $row   = $model->find($id) ?? throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        if (! $this->validate($this->rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data          = $this->collect();
        $data['slug']  = $data['name'] === $row['name'] ? $row['slug'] : $this->uniqueSlug($model, $data['name'], $id);
        $data['image'] = Uploader::image($this->request->getFile('image'), 'products', $row['image']);
        $model->update($id, $data);
        return redirect()->to(base_url('admin/products'))->with('success', 'Product updated.');
    }

    public function delete(int $id)
    {
        $model = new ProductModel();
        if ($row = $model->find($id)) {
            Uploader::delete($row['image'], 'products');
            $model->delete($id);
        }
        return redirect()->to(base_url('admin/products'))->with('success', 'Product deleted.');
    }

    private function collect(): array
    {
        $d = $this->request->getPost(['category_id', 'subcategory_id', 'name', 'sku', 'short_desc', 'description', 'price', 'sale_price', 'stock']);
        $d['subcategory_id'] = $d['subcategory_id'] ?: null;
        $d['sale_price']     = ($d['sale_price'] !== '' && $d['sale_price'] !== null) ? $d['sale_price'] : null;
        $d['featured']       = $this->request->getPost('featured') ? 1 : 0;
        $d['status']         = $this->request->getPost('status') ? 1 : 0;
        return $d;
    }

    private function formData(string $title, ?array $row): array
    {
        return [
            'title'      => $title,
            'row'        => $row,
            'categories' => (new CategoryModel())->findAll(),
            'subs'       => (new SubcategoryModel())->findAll(),
        ];
    }

    private function uniqueSlug(ProductModel $m, string $name, int $ignore = 0): string
    {
        $base = make_slug($name);
        $slug = $base;
        $n    = 2;
        while ($m->where('slug', $slug)->where('id !=', $ignore)->first()) {
            $slug = $base . '-' . $n++;
        }
        return $slug;
    }
}
