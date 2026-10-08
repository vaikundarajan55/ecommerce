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
            'done'    => $orders->where(['user_id' => $uid, 'status' => 'delivered'])->countAllResults(),
            'cancel'  => $orders->where(['user_id' => $uid, 'status' => 'cancelled'])->countAllResults(),
            'spent'   => (float) ($orders->selectSum('total')->where(['user_id' => $uid, 'payment_status' => 'paid'])->first()['total'] ?? 0),
            'recent'  => $this->withItems($orders)->where('user_id', $uid)->orderBy('id', 'DESC')->findAll(5),
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
        $sizes = [5, 10, 20];
        $per   = (int) $this->request->getGet('per_page');
        $per   = in_array($per, $sizes, true) ? $per : 10;
        $model = new OrderModel();
        $rows  = $this->withItems($model)->where('user_id', session('user_id'))->orderBy('id', 'DESC')->paginate($per);
        $total = $model->pager->getTotal();
        $from  = $total ? ($model->pager->getCurrentPage() - 1) * $per + 1 : 0;
        return view('website/account/orders', [
            'title'   => 'My orders',
            'orders'  => $rows,
            'pager'   => $model->pager,
            'perPage' => $per,
            'sizes'   => $sizes,
            'total'   => $total,
            'from'    => $from,
            'to'      => $total ? $from + count($rows) - 1 : 0,
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

    /** Add first item name, item count and total quantity to an orders query (for the order tables). */
    private function withItems(OrderModel $m): OrderModel
    {
        return $m->select(
            'orders.*,
             (SELECT i.name FROM order_items i WHERE i.order_id = orders.id ORDER BY i.id LIMIT 1) first_item,
             (SELECT COUNT(*) FROM order_items i WHERE i.order_id = orders.id) item_count,
             (SELECT COALESCE(SUM(i.qty), 0) FROM order_items i WHERE i.order_id = orders.id) qty',
            false
        );
    }
}
