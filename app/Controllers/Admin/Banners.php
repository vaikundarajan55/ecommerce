<?php

namespace App\Controllers\Admin;

use App\Libraries\Uploader;
use App\Models\BannerModel;

class Banners extends AdminBase
{
    private array $rules = [
        'title'      => 'required|min_length[2]|max_length[150]',
        'subtitle'   => 'permit_empty|max_length[255]',
        'link'       => 'permit_empty|max_length[255]',
        'sort_order' => 'permit_empty|integer',
        'image'      => 'permit_empty|is_image[image]|max_size[image,3072]',
    ];

    public function index()
    {
        $data = $this->listing(new BannerModel(), ['title', 'subtitle', 'link'], 'sort_order', 'ASC');
        return $this->render('banners/index', ['title' => 'Banners'] + $data);
    }

    public function create()
    {
        return $this->render('banners/form', ['title' => 'Add banner', 'row' => null]);
    }

    public function store()
    {
        if (! $this->validate($this->rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data          = $this->request->getPost(['title', 'subtitle', 'link', 'sort_order']);
        $data['status'] = $this->request->getPost('status') ? 1 : 0;
        $data['sort_order'] = (int) $data['sort_order'];
        $data['image'] = Uploader::image($this->request->getFile('image'), 'banners');
        (new BannerModel())->insert($data);
        return redirect()->to(base_url('admin/banners'))->with('success', 'Banner added.');
    }

    public function edit(int $id)
    {
        $row = (new BannerModel())->find($id) ?? throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return $this->render('banners/form', ['title' => 'Edit banner', 'row' => $row]);
    }

    public function update(int $id)
    {
        $model = new BannerModel();
        $row   = $model->find($id) ?? throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        if (! $this->validate($this->rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data          = $this->request->getPost(['title', 'subtitle', 'link', 'sort_order']);
        $data['sort_order'] = (int) $data['sort_order'];
        $data['status'] = $this->request->getPost('status') ? 1 : 0;
        $data['image'] = Uploader::image($this->request->getFile('image'), 'banners', $row['image']);
        $model->update($id, $data);
        return redirect()->to(base_url('admin/banners'))->with('success', 'Banner updated.');
    }

    public function delete(int $id)
    {
        $model = new BannerModel();
        if ($row = $model->find($id)) {
            Uploader::delete($row['image'], 'banners');
            $model->delete($id);
        }
        return redirect()->to(base_url('admin/banners'))->with('success', 'Banner deleted.');
    }
}
