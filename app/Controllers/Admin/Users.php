<?php

namespace App\Controllers\Admin;

use App\Models\UserModel;

class Users extends AdminBase
{
    public function index()
    {
        $model = new UserModel();
        $q     = trim((string) $this->request->getGet('q'));
        $model->select('users.*, (SELECT COUNT(*) FROM orders o WHERE o.user_id = users.id) AS order_count');
        if ($q !== '') {
            $model->groupStart()->like('name', $q)->orLike('email', $q)->orLike('phone', $q)->groupEnd();
        }
        return $this->render('users/index', ['title' => 'Users', 'rows' => $model->orderBy('id', 'DESC')->paginate(10), 'pager' => $model->pager, 'q' => $q]);
    }

    public function toggle(int $id)
    {
        $model = new UserModel();
        if ($u = $model->find($id)) {
            $model->update($id, ['status' => $u['status'] ? 0 : 1]);
        }
        return redirect()->back()->with('success', 'User status changed.');
    }

    public function delete(int $id)
    {
        (new UserModel())->delete($id);
        return redirect()->back()->with('success', 'User deleted.');
    }
}
