<?php

namespace App\Libraries;

use App\Models\ProductModel;

/** Session based shopping cart: session('cart') = [product_id => qty] */
class Cart
{
    public const FREE_SHIPPING_ABOVE = 999;
    public const SHIPPING_FEE        = 49;

    protected $session;

    public function __construct()
    {
        $this->session = session();
    }

    public function raw(): array
    {
        return $this->session->get('cart') ?? [];
    }

    public function add(int $id, int $qty = 1): bool
    {
        $p = (new ProductModel())->where('status', 1)->find($id);
        if (! $p || $p['stock'] < 1) {
            return false;
        }
        $cart       = $this->raw();
        $cart[$id]  = min(($cart[$id] ?? 0) + max(1, $qty), (int) $p['stock']);
        $this->session->set('cart', $cart);
        return true;
    }

    public function update(int $id, int $qty): void
    {
        $cart = $this->raw();
        if (! isset($cart[$id])) {
            return;
        }
        if ($qty < 1) {
            unset($cart[$id]);
        } else {
            $p         = (new ProductModel())->find($id);
            $cart[$id] = $p ? min($qty, max(1, (int) $p['stock'])) : $qty;
        }
        $this->session->set('cart', $cart);
    }

    public function remove(int $id): void
    {
        $cart = $this->raw();
        unset($cart[$id]);
        $this->session->set('cart', $cart);
    }

    public function clear(): void
    {
        $this->session->remove('cart');
    }

    /** @return array<int,array> product rows with qty, unit, line_total */
    public function items(): array
    {
        $cart = $this->raw();
        if (! $cart) {
            return [];
        }
        $rows  = (new ProductModel())->whereIn('id', array_keys($cart))->findAll();
        $items = [];
        foreach ($rows as $r) {
            $r['qty']        = $cart[$r['id']];
            $r['unit']       = current_price($r);
            $r['line_total'] = $r['unit'] * $r['qty'];
            $items[]         = $r;
        }
        return $items;
    }

    public function totals(?array $items = null): array
    {
        $items    ??= $this->items();
        $subtotal   = array_sum(array_column($items, 'line_total'));
        $shipping   = ($subtotal > 0 && $subtotal < self::FREE_SHIPPING_ABOVE) ? self::SHIPPING_FEE : 0;
        return ['subtotal' => $subtotal, 'shipping' => $shipping, 'total' => $subtotal + $shipping];
    }
}
