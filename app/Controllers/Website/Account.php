<?php

namespace App\Controllers\Website;

use App\Controllers\BaseController;

use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\UserModel;

class Account extends BaseController
{
    public function dashboard()
    {
        $uid    = session('user_id');
        $orders = new OrderModel();
        return view('website/account/dashboard', [
            'title'   => 'My account',
            'user'    => (new UserModel())->find($uid),
            'total'   => $orders->where('user_id', $uid)->countAllResults(),
            'pending' => $orders->where('user_id', $uid)->whereIn('status', ['placed', 'processing', 'shipped'])->countAllResults(),
            'spent'   => (float) ($orders->selectSum('total')->where(['user_id' => $uid, 'payment_status' => 'paid'])->first()['total'] ?? 0),
            'recent'  => $orders->where('user_id', $uid)->orderBy('id', 'DESC')->findAll(5),
        ]);
    }

    public function profile()
    {
        return view('website/account/profile', ['title' => 'My profile', 'user' => (new UserModel())->find(session('user_id'))]);
    }

    public function updateProfile()
    {
        $id    = session('user_id');
        $rules = [
            'name'    => 'required|min_length[2]',
            'email'   => "required|valid_email|is_unique[users.email,id,{$id}]",
            'phone'   => 'permit_empty|numeric|min_length[10]|max_length[15]',
            'pincode' => 'permit_empty|numeric|max_length[8]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        (new UserModel())->update($id, $this->request->getPost(['name', 'email', 'phone', 'address', 'city', 'pincode']));
        session()->set('user_name', $this->request->getPost('name'));
        return redirect()->to(base_url('account/profile'))->with('success', 'Profile updated.');
    }

    public function orders()
    {
        $model = new OrderModel();
        return view('website/account/orders', [
            'title'  => 'My orders',
            'orders' => $model->where('user_id', session('user_id'))->orderBy('id', 'DESC')->paginate(10),
            'pager'  => $model->pager,
        ]);
    }

    public function orderView(int $id)
    {
        [$order, $items] = $this->load($id);
        return view('website/account/order_view', ['title' => 'Order ' . $order['order_no'], 'order' => $order, 'items' => $items]);
    }

    public function invoice(int $id)
    {
        [$order, $items] = $this->load($id);
        return view('shared/invoice', ['title' => 'Invoice ' . $order['order_no'], 'order' => $order, 'items' => $items, 'backUrl' => base_url('account/orders/' . $id)]);
    }

    public function password()
    {
        return view('website/account/password', ['title' => 'Change password']);
    }

    public function updatePassword()
    {
        $rules = ['current_password' => 'required', 'new_password' => 'required|min_length[6]', 'confirm_password' => 'required|matches[new_password]'];
        if (! $this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }
        $users = new UserModel();
        $user  = $users->find(session('user_id'));
        if (! password_verify($this->request->getPost('current_password'), $user['password'])) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }
        $users->update($user['id'], ['password' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT)]);
        return redirect()->to(base_url('account/change-password'))->with('success', 'Password changed successfully.');
    }

    private function load(int $id): array
    {
        $order = (new OrderModel())->where(['id' => $id, 'user_id' => session('user_id')])->first();
        if (! $order) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        return [$order, (new OrderItemModel())->where('order_id', $id)->findAll()];
    }
}
