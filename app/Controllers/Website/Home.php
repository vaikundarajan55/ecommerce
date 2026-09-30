<?php

namespace App\Controllers\Website;

use App\Controllers\BaseController;

use App\Models\BannerModel;
use App\Models\CategoryModel;
use App\Models\ContactModel;
use App\Models\ProductModel;

class Home extends BaseController
{
    public function index()
    {
        return view('website/pages/home', [
            'title'      => 'Home',
            'banners'    => (new BannerModel())->where('status', 1)->orderBy('sort_order')->findAll(),
            'categories' => (new CategoryModel())->where('status', 1)->findAll(),
            'featured'   => (new ProductModel())->where(['status' => 1, 'featured' => 1])->orderBy('id', 'DESC')->findAll(8),
            'latest'     => (new ProductModel())->where('status', 1)->orderBy('id', 'DESC')->findAll(4),
        ]);
    }

    public function about()
    {
        return view('website/pages/about', ['title' => 'About us']);
    }

    public function contact()
    {
        return view('website/pages/contact', ['title' => 'Contact us']);
    }

    public function sendContact()
    {
        $rules = [
            'name'    => 'required|min_length[2]|max_length[100]',
            'email'   => 'required|valid_email',
            'subject' => 'permit_empty|max_length[200]',
            'message' => 'required|min_length[5]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        (new ContactModel())->insert([
            'name'       => $this->request->getPost('name'),
            'email'      => $this->request->getPost('email'),
            'subject'    => $this->request->getPost('subject'),
            'message'    => $this->request->getPost('message'),
            'is_read'    => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to(base_url('contact'))->with('success', 'Thanks! Your message has been sent. We will reply soon.');
    }
}
