<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class Auth extends BaseController
{
    /** /admin: login page for guests, dashboard for logged-in admins. */
    public function index()
    {
        if (session()->get('admin_id')) {
            return redirect()->to(base_url('admin/dashboard'));
        }

        return $this->login();
    }

    public function login()
    {
        return view('admin/auth/login', ['title' => 'Admin login']);
    }

    public function attempt()
    {
        if (! $this->validate(['email' => 'required|valid_email', 'password' => 'required'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $admin = (new AdminModel())->where('email', $this->request->getPost('email'))->first();
        if (! $admin || ! password_verify($this->request->getPost('password'), $admin['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email or password is incorrect.');
        }
        session()->regenerate();
        session()->set(['admin_id' => $admin['id'], 'admin_name' => $admin['name']]);
        return redirect()->to(base_url('admin/dashboard'));
    }

    public function logout()
    {
        session()->remove(['admin_id', 'admin_name']);
        return redirect()->to(base_url('admin/login'))->with('info', 'You have been logged out.');
    }
}
