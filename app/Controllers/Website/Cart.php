<?php

namespace App\Controllers\Website;

use App\Controllers\BaseController;

use App\Libraries\Cart as CartLib;

class Cart extends BaseController
{
    public function index()
    {
        $cart  = new CartLib();
        $items = $cart->items();
        return view('website/shop/cart', ['title' => 'Your cart', 'items' => $items, 'totals' => $cart->totals($items)]);
    }

    public function add(int $id)
    {
        $qty = (int) ($this->request->getPost('qty') ?: 1);
        $ok  = (new CartLib())->add($id, $qty);
        if (! $ok) {
            return redirect()->back()->with('error', 'This product is out of stock or unavailable.');
        }
        if ($this->request->getPost('buy_now')) {
            return redirect()->to(base_url('checkout'));
        }
        return redirect()->back()->with('success', 'Added to cart.');
    }

    public function update()
    {
        $cart = new CartLib();
        foreach ((array) $this->request->getPost('qty') as $id => $qty) {
            $cart->update((int) $id, (int) $qty);
        }
        return redirect()->to(base_url('cart'))->with('success', 'Cart updated.');
    }

    public function remove(int $id)
    {
        (new CartLib())->remove($id);
        return redirect()->to(base_url('cart'))->with('info', 'Item removed.');
    }
}
