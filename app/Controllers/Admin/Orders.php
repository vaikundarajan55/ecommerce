<?php

namespace App\Controllers\Admin;

use App\Models\OrderItemModel;
use App\Models\OrderModel;

class Orders extends AdminBase
{
    public function index()
    {
        $model  = new OrderModel();
        $status = (string) $this->request->getGet('status');
        if ($status !== '') {
            $model->where('status', $status);
        }
        $data = $this->listing($model, ['order_no', 'name', 'email', 'phone'], 'id');
        return $this->render('orders/index', ['title' => 'Orders', 'status' => $status] + $data);
    }

    public function view(int $id)
    {
        [$order, $items] = $this->load($id);
        return $this->render('orders/view', ['title' => 'Order ' . $order['order_no'], 'order' => $order, 'items' => $items]);
    }

    public function status(int $id)
    {
        $allowed = ['placed', 'processing', 'shipped', 'delivered', 'cancelled'];
        $status  = $this->request->getPost('status');
        if (in_array($status, $allowed, true)) {
            (new OrderModel())->update($id, ['status' => $status]);
        }
        return redirect()->back()->with('success', 'Order status updated.');
    }

    public function invoice(int $id)
    {
        [$order, $items] = $this->load($id);
        return view('shared/invoice', ['title' => 'Invoice ' . $order['order_no'], 'order' => $order, 'items' => $items, 'backUrl' => base_url('admin/orders/view/' . $id)]);
    }

    private function load(int $id): array
    {
        $order = (new OrderModel())->find($id) ?? throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return [$order, (new OrderItemModel())->where('order_id', $id)->findAll()];
    }
}
