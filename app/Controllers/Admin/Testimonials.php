<?php

namespace App\Controllers\Admin;

use App\Libraries\Uploader;
use App\Models\TestimonialModel;

class Testimonials extends AdminBase
{
    private array $rules = [
        'name'       => 'required|min_length[2]|max_length[100]',
        'role'       => 'permit_empty|max_length[120]',
        'message'    => 'required|min_length[10]|max_length[1000]',
        'rating'     => 'required|integer|greater_than[0]|less_than[6]',
        'sort_order' => 'permit_empty|integer',
        'photo'      => 'permit_empty|is_image[photo]|max_size[photo,2048]',
    ];

    public function index()
    {
        $data = $this->listing(new TestimonialModel(), ['name', 'role', 'message'], 'sort_order', 'ASC');
        return $this->render('testimonials/index', ['title' => 'Testimonials', 'stats' => $this->statusCounts('testimonials')] + $data);
    }

    public function create()
    {
        return $this->render('testimonials/form', ['title' => 'Add testimonial', 'row' => null]);
    }

    public function store()
    {
        if (! $this->validate($this->rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data          = $this->collect();
        $data['photo'] = Uploader::image($this->request->getFile('photo'), 'testimonials');
        (new TestimonialModel())->insert($data);
        return redirect()->to(base_url('admin/testimonials'))->with('success', 'Testimonial added.');
    }

    public function edit(int $id)
    {
        $row = (new TestimonialModel())->find($id) ?? throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return $this->render('testimonials/form', ['title' => 'Edit testimonial', 'row' => $row]);
    }

    public function update(int $id)
    {
        $model = new TestimonialModel();
        $row   = $model->find($id) ?? throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        if (! $this->validate($this->rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data          = $this->collect();
        $data['photo'] = Uploader::image($this->request->getFile('photo'), 'testimonials', $row['photo']);
        $model->update($id, $data);
        return redirect()->to(base_url('admin/testimonials'))->with('success', 'Testimonial updated.');
    }

    public function delete(int $id)
    {
        $model = new TestimonialModel();
        if ($row = $model->find($id)) {
            Uploader::delete($row['photo'], 'testimonials');
            $model->delete($id);
        }
        return redirect()->to(base_url('admin/testimonials'))->with('success', 'Testimonial deleted.');
    }

    private function collect(): array
    {
        $d               = $this->request->getPost(['name', 'role', 'message', 'rating']);
        $d['rating']     = (int) $d['rating'];
        $d['sort_order'] = (int) $this->request->getPost('sort_order');
        $d['status']     = $this->request->getPost('status') ? 1 : 0;
        return $d;
    }
}
