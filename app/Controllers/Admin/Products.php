<?php

namespace App\Controllers\Admin;

use App\Libraries\Uploader;
use App\Models\CategoryModel;
use App\Models\ProductImageModel;
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
        'gallery'     => 'permit_empty|is_image[gallery]|max_size[gallery,3072]',
    ];

    /** Most extra images a product can have. */
    private const MAX_GALLERY = 10;

    public function index()
    {
        $model = (new ProductModel())->select('products.*, categories.name AS category_name, subcategories.name AS sub_name')
            ->join('categories', 'categories.id = products.category_id')
            ->join('subcategories', 'subcategories.id = products.subcategory_id', 'left');
        $tag = (string) $this->request->getGet('tag');
        if (in_array($tag, ['featured', 'is_current', 'is_peak'], true)) {
            $model->where('products.' . $tag, 1);
        }
        $data = $this->listing($model, ['products.name', 'products.sku', 'categories.name', 'subcategories.name'], 'products.id');
        return $this->render('products/index', ['title' => 'Products', 'stats' => $this->statusCounts('products'), 'tag' => $tag] + $data);
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
        $id    = $model->insert($data);
        $added = $this->saveGallery((int) $id);
        return redirect()->to(base_url('admin/products'))->with('success', 'Product added' . ($added ? " with $added extra image(s)." : '.'));
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
        $this->removeGallery($id, (array) $this->request->getPost('remove_gallery'));
        $this->saveGallery($id);
        return redirect()->to(base_url('admin/products'))->with('success', 'Product updated.');
    }

    public function delete(int $id)
    {
        $model = new ProductModel();
        if ($row = $model->find($id)) {
            Uploader::delete($row['image'], 'products');
            $this->removeGallery($id);
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
        $d['is_current']     = $this->request->getPost('is_current') ? 1 : 0;
        $d['is_peak']        = $this->request->getPost('is_peak') ? 1 : 0;
        $d['status']         = $this->request->getPost('status') ? 1 : 0;
        return $d;
    }

    private function formData(string $title, ?array $row): array
    {
        return [
            'title'      => $title,
            'row'        => $row,
            'gallery'    => $row ? (new ProductImageModel())->forProduct((int) $row['id']) : [],
            'maxGallery' => self::MAX_GALLERY,
            'categories' => (new CategoryModel())->findAll(),
            'subs'       => (new SubcategoryModel())->findAll(),
        ];
    }

    /** Store the uploaded gallery[] files (up to MAX_GALLERY per product). Returns how many were saved. */
    private function saveGallery(int $productId): int
    {
        $model = new ProductImageModel();
        $have  = $model->where('product_id', $productId)->countAllResults();
        $order = (int) ($model->selectMax('sort_order')->where('product_id', $productId)->first()['sort_order'] ?? 0);
        $saved = 0;
        foreach ($this->request->getFileMultiple('gallery') ?? [] as $file) {
            if ($have + $saved >= self::MAX_GALLERY) {
                break;
            }
            if ($name = Uploader::image($file, 'products')) {
                $model->insert(['product_id' => $productId, 'image' => $name, 'sort_order' => ++$order]);
                $saved++;
            }
        }
        return $saved;
    }

    /** Delete gallery images of a product: the given ids, or all of them when $ids is null. */
    private function removeGallery(int $productId, ?array $ids = null): void
    {
        if ($ids === []) {
            return;
        }
        $model = new ProductImageModel();
        $model->where('product_id', $productId);
        if ($ids !== null) {
            $model->whereIn('id', array_map('intval', $ids));
        }
        foreach ($model->findAll() as $img) {
            Uploader::delete($img['image'], 'products');
            $model->delete($img['id']);
        }
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
