<?php

namespace App\Controllers\Website;

use App\Controllers\BaseController;

use App\Libraries\Cart as CartLib;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\UserModel;

class Checkout extends BaseController
{
    public function index()
    {
        $cart  = new CartLib();
        $items = $cart->items();
        if (! $items) {
            return redirect()->to(base_url('cart'))->with('info', 'Your cart is empty.');
        }
        return view('website/checkout/checkout', [
            'title'  => 'Checkout',
            'items'  => $items,
            'totals' => $cart->totals($items),
            'user'   => (new UserModel())->find(session('user_id')),
        ]);
    }

    public function place()
    {
        $rules = [
            'name'    => 'required|min_length[2]',
            'email'   => 'required|valid_email',
            'phone'   => 'required|numeric|min_length[10]|max_length[15]',
            'address' => 'required|min_length[5]',
            'city'    => 'required',
            'pincode' => 'required|numeric|min_length[5]|max_length[8]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $cart  = new CartLib();
        $items = $cart->items();
        if (! $items) {
            return redirect()->to(base_url('cart'))->with('info', 'Your cart is empty.');
        }
        $totals  = $cart->totals($items);
        $orders  = new OrderModel();
        $orderNo = 'ORD' . date('ymd') . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));

        $db = db_connect();
        $db->transStart();
        $orderId = $orders->insert([
            'order_no'       => $orderNo,
            'user_id'        => session('user_id'),
            'name'           => $this->request->getPost('name'),
            'email'          => $this->request->getPost('email'),
            'phone'          => $this->request->getPost('phone'),
            'address'        => $this->request->getPost('address'),
            'city'           => $this->request->getPost('city'),
            'pincode'        => $this->request->getPost('pincode'),
            'subtotal'       => $totals['subtotal'],
            'shipping'       => $totals['shipping'],
            'total'          => $totals['total'],
            'payment_method' => 'dummy_gateway',
            'payment_status' => 'pending',
            'status'         => 'placed',
        ]);
        $orderItems = new OrderItemModel();
        foreach ($items as $i) {
            $orderItems->insert([
                'order_id'   => $orderId,
                'product_id' => $i['id'],
                'name'       => $i['name'],
                'price'      => $i['unit'],
                'qty'        => $i['qty'],
                'total'      => $i['line_total'],
            ]);
        }
        $db->transComplete();

        // Remember address for next time
        (new UserModel())->update(session('user_id'), [
            'phone' => $this->request->getPost('phone'), 'address' => $this->request->getPost('address'),
            'city'  => $this->request->getPost('city'), 'pincode' => $this->request->getPost('pincode'),
        ]);

        return redirect()->to(base_url('payment/' . $orderNo));
    }

    /** Dummy payment gateway screen */
    public function payment(string $orderNo)
    {
        $order = $this->myOrder($orderNo);
        if (! $order) {
            return redirect()->to(base_url('account/orders'))->with('error', 'Order not found.');
        }
        if ($order['payment_status'] === 'paid') {
            return redirect()->to(base_url('order-success/' . $orderNo));
        }
        return view('website/checkout/payment', ['title' => 'Secure payment', 'order' => $order]);
    }

    public function process(string $orderNo)
    {
        $order = $this->myOrder($orderNo);
        if (! $order) {
            return redirect()->to(base_url('account/orders'));
        }
        $orders = new OrderModel();
        $txn    = 'TXN' . strtoupper(bin2hex(random_bytes(5)));

        if ($this->request->getPost('result') === 'success') {
            $orders->update($order['id'], ['payment_status' => 'paid', 'txn_id' => $txn, 'status' => 'processing']);
            // reduce stock
            $products = db_connect()->table('products');
            foreach ((new OrderItemModel())->where('order_id', $order['id'])->findAll() as $it) {
                if ($it['product_id']) {
                    $products->set('stock', 'GREATEST(stock - ' . (int) $it['qty'] . ', 0)', false)->where('id', $it['product_id'])->update();
                    $products->resetQuery();
                }
            }
            (new CartLib())->clear();
            return redirect()->to(base_url('order-success/' . $orderNo));
        }

        $orders->update($order['id'], ['payment_status' => 'failed', 'txn_id' => $txn]);
        return redirect()->to(base_url('order-failed/' . $orderNo));
    }

    public function success(string $orderNo)
    {
        $order = $this->myOrder($orderNo);
        if (! $order || $order['payment_status'] !== 'paid') {
            return redirect()->to(base_url('account/orders'));
        }
        return view('website/checkout/success', ['title' => 'Order placed', 'order' => $order]);
    }

    public function failure(string $orderNo)
    {
        $order = $this->myOrder($orderNo);
        if (! $order) {
            return redirect()->to(base_url('account/orders'));
        }
        return view('website/checkout/failure', ['title' => 'Payment failed', 'order' => $order]);
    }

    private function myOrder(string $orderNo): ?array
    {
        return (new OrderModel())->where(['order_no' => $orderNo, 'user_id' => session('user_id')])->first();
    }
}
