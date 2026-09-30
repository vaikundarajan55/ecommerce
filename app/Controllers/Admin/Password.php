<?php

namespace App\Controllers\Admin;

use App\Models\AdminModel;

class Password extends AdminBase
{
    public function index()
    {
        return $this->render('auth/password', ['title' => 'Change password']);
    }

    public function update()
    {
        $rules = ['current_password' => 'required', 'new_password' => 'required|min_length[6]', 'confirm_password' => 'required|matches[new_password]'];
        if (! $this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }
        $model = new AdminModel();
        $admin = $model->find(session('admin_id'));
        if (! password_verify($this->request->getPost('current_password'), $admin['password'])) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }
        $model->update($admin['id'], ['password' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT)]);
        return redirect()->to(base_url('admin/change-password'))->with('success', 'Password changed successfully.');
    }
}
