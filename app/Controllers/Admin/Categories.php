<?php

namespace App\Controllers\Admin;

use App\Libraries\Uploader;
use App\Models\CategoryModel;

class Categories extends AdminBase
{
    public function index()
    {
        $model = (new CategoryModel())->select(
            'categories.*, (SELECT COUNT(*) FROM subcategories s WHERE s.category_id = categories.id) subs,
             (SELECT COUNT(*) FROM products p WHERE p.category_id = categories.id) prods',
            false
        );
        $data = $this->listing($model, ['categories.name', 'categories.slug'], 'categories.id');
        return $this->render('categories/index', ['title' => 'Categories'] + $data);
    }

    public function create()
    {
        return $this->render('categories/form', ['title' => 'Add category', 'row' => null]);
    }

    public function store()
    {
        $rules = ['name' => 'required|min_length[2]|max_length[120]', 'image' => 'permit_empty|is_image[image]|max_size[image,3072]'];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $model = new CategoryModel();
        $name  = $this->request->getPost('name');
        $model->insert([
            'name'   => $name,
            'slug'   => $this->uniqueSlug($model, $name),
            'image'  => Uploader::image($this->request->getFile('image'), 'categories'),
            'status' => $this->request->getPost('status') ? 1 : 0,
        ]);
        return redirect()->to(base_url('admin/categories'))->with('success', 'Category added.');
    }

    public function edit(int $id)
    {
        $row = (new CategoryModel())->find($id) ?? throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return $this->render('categories/form', ['title' => 'Edit category', 'row' => $row]);
    }

    public function update(int $id)
    {
        $model = new CategoryModel();
        $row   = $model->find($id) ?? throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $rules = ['name' => 'required|min_length[2]|max_length[120]', 'image' => 'permit_empty|is_image[image]|max_size[image,3072]'];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $name = $this->request->getPost('name');
        $model->update($id, [
            'name'   => $name,
            'slug'   => $name === $row['name'] ? $row['slug'] : $this->uniqueSlug($model, $name, $id),
            'image'  => Uploader::image($this->request->getFile('image'), 'categories', $row['image']),
            'status' => $this->request->getPost('status') ? 1 : 0,
        ]);
        return redirect()->to(base_url('admin/categories'))->with('success', 'Category updated.');
    }

    public function delete(int $id)
    {
        $model = new CategoryModel();
        if ($row = $model->find($id)) {
            Uploader::delete($row['image'], 'categories');
            $model->delete($id); // subcategories and products are removed by FK cascade
        }
        return redirect()->to(base_url('admin/categories'))->with('success', 'Category deleted.');
    }

    private function uniqueSlug(CategoryModel $m, string $name, int $ignore = 0): string
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
