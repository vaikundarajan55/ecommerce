<?php

namespace App\Controllers\Admin;

use App\Models\CategoryModel;
use App\Models\SubcategoryModel;

class Subcategories extends AdminBase
{
    public function index()
    {
        $model = (new SubcategoryModel())->select('subcategories.*, categories.name AS category_name')
            ->join('categories', 'categories.id = subcategories.category_id');
        $data = $this->listing($model, ['subcategories.name', 'subcategories.slug', 'categories.name'], 'subcategories.id');
        return $this->render('subcategories/index', ['title' => 'Subcategories'] + $data);
    }

    public function create()
    {
        return $this->render('subcategories/form', ['title' => 'Add subcategory', 'row' => null, 'categories' => (new CategoryModel())->findAll()]);
    }

    public function store()
    {
        if (! $this->validate(['category_id' => 'required|is_natural_no_zero', 'name' => 'required|min_length[2]|max_length[120]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $model = new SubcategoryModel();
        $name  = $this->request->getPost('name');
        $model->insert([
            'category_id' => $this->request->getPost('category_id'),
            'name'        => $name,
            'slug'        => $this->uniqueSlug($model, $name),
            'status'      => $this->request->getPost('status') ? 1 : 0,
        ]);
        return redirect()->to(base_url('admin/subcategories'))->with('success', 'Subcategory added.');
    }

    public function edit(int $id)
    {
        $row = (new SubcategoryModel())->find($id) ?? throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return $this->render('subcategories/form', ['title' => 'Edit subcategory', 'row' => $row, 'categories' => (new CategoryModel())->findAll()]);
    }

    public function update(int $id)
    {
        $model = new SubcategoryModel();
        $row   = $model->find($id) ?? throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        if (! $this->validate(['category_id' => 'required|is_natural_no_zero', 'name' => 'required|min_length[2]|max_length[120]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $name = $this->request->getPost('name');
        $model->update($id, [
            'category_id' => $this->request->getPost('category_id'),
            'name'        => $name,
            'slug'        => $name === $row['name'] ? $row['slug'] : $this->uniqueSlug($model, $name, $id),
            'status'      => $this->request->getPost('status') ? 1 : 0,
        ]);
        return redirect()->to(base_url('admin/subcategories'))->with('success', 'Subcategory updated.');
    }

    public function delete(int $id)
    {
        (new SubcategoryModel())->delete($id);
        return redirect()->to(base_url('admin/subcategories'))->with('success', 'Subcategory deleted.');
    }

    private function uniqueSlug(SubcategoryModel $m, string $name, int $ignore = 0): string
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
