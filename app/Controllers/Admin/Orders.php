<?php

namespace App\Controllers\Admin;

use App\Models\OrderItemModel;
use App\Models\OrderModel;

class Orders extends AdminBase
{
    public function index()
    {
        $model  = new OrderModel();
        $status = $this->request->getGet('status');
        $q      = trim((string) $this->request->getGet('q'));
        if ($status) {
            $model->where('status', $status);
        }
        if ($q !== '') {
            $model->groupStart()->like('order_no', $q)->orLike('name', $q)->orLike('email', $q)->groupEnd();
        }
        return $this->render('orders/index', [
            'title'  => 'Orders',
            'rows'   => $model->orderBy('id', 'DESC')->paginate(10),
            'pager'  => $model->pager,
            'status' => $status,
            'q'      => $q,
        ]);
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
