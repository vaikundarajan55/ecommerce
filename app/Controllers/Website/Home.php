<?php

namespace App\Controllers\Website;

use App\Controllers\BaseController;

use App\Models\BannerModel;
use App\Models\CategoryModel;
use App\Models\EnquiryModel;
use App\Models\ProductModel;
use App\Models\TestimonialModel;

class Home extends BaseController
{
    public function index()
    {
        // Products tagged in Admin > Products for each home page section
        $tagged = static fn (string $flag, int $n) => (new ProductModel())->where(['status' => 1, $flag => 1])->orderBy('id', 'DESC')->findAll($n);

        return view('website/pages/home', [
            'title'        => 'Home',
            'banners'      => (new BannerModel())->where('status', 1)->orderBy('sort_order')->findAll(),
            'categories'   => (new CategoryModel())->where('status', 1)->findAll(),
            'featured'     => $tagged('featured', 8),
            'current'      => $tagged('is_current', 4),
            'peak'         => $tagged('is_peak', 4),
            'latest'       => (new ProductModel())->where('status', 1)->orderBy('id', 'DESC')->findAll(4),
            'testimonials' => $this->testimonials(),
        ]);
    }

    public function about()
    {
        return view('website/pages/about', ['title' => 'About us', 'testimonials' => $this->testimonials()]);
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
            'phone'   => 'permit_empty|max_length[20]',
            'subject' => 'permit_empty|max_length[200]',
            'message' => 'required|min_length[5]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        // Contact messages go to the same admin inbox as product enquiries
        (new EnquiryModel())->insert([
            'source'     => 'contact',
            'name'       => $this->request->getPost('name'),
            'email'      => $this->request->getPost('email'),
            'phone'      => $this->request->getPost('phone'),
            'subject'    => $this->request->getPost('subject'),
            'message'    => $this->request->getPost('message'),
            'is_read'    => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to(base_url('contact'))->with('success', 'Thanks! Your message has been sent. We will reply soon.');
    }

    private function testimonials(): array
    {
        return (new TestimonialModel())->where('status', 1)->orderBy('sort_order')->orderBy('id', 'DESC')->findAll(9);
    }
}
