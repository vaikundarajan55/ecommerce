<?php

if (! function_exists('money')) {
    function money($amount): string
    {
        return '₹' . number_format((float) $amount, 2);
    }
}

if (! function_exists('client_ip')) {
    /**
     * Visitor IP in IPv4 dotted format (e.g. 192.168.10.243).
     * "::ffff:192.168.10.243" is unwrapped, and localhost (::1 / 127.0.0.1) becomes this server's LAN IP.
     */
    function client_ip(): string
    {
        $ip = service('request')->getIPAddress();

        if (stripos($ip, '::ffff:') === 0 && filter_var(substr($ip, 7), FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $ip = substr($ip, 7);
        }
        if ($ip === '::1' || str_starts_with($ip, '127.')) {
            $lan = gethostbyname(gethostname());
            $ip  = filter_var($lan, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && ! str_starts_with($lan, '127.') ? $lan : '127.0.0.1';
        }
        return $ip;
    }
}

if (! function_exists('img_url')) {
    /** Returns the public URL of an uploaded image, or a placeholder. */
    function img_url(?string $file, string $folder = 'products'): string
    {
        if ($file && is_file(FCPATH . 'uploads/' . $folder . '/' . $file)) {
            return base_url('uploads/' . $folder . '/' . $file);
        }
        return base_url('assets/img/placeholder.svg');
    }
}

if (! function_exists('current_price')) {
    function current_price(array|object $p): float
    {
        $p = (array) $p;
        return (! empty($p['sale_price']) && $p['sale_price'] > 0) ? (float) $p['sale_price'] : (float) $p['price'];
    }
}

if (! function_exists('discount_pct')) {
    function discount_pct(array|object $p): int
    {
        $p = (array) $p;
        if (empty($p['sale_price']) || $p['price'] <= 0 || $p['sale_price'] >= $p['price']) {
            return 0;
        }
        return (int) round((1 - $p['sale_price'] / $p['price']) * 100);
    }
}

if (! function_exists('status_badge')) {
    function status_badge(string $status): string
    {
        $map = [
            'placed' => 'primary', 'processing' => 'info', 'shipped' => 'warning', 'delivered' => 'success',
            'cancelled' => 'danger', 'paid' => 'success', 'pending' => 'secondary', 'failed' => 'danger',
        ];
        $c = $map[$status] ?? 'secondary';
        return '<span class="badge text-bg-' . $c . '">' . esc(ucfirst($status)) . '</span>';
    }
}

if (! function_exists('active_badge')) {
    function active_badge($v): string
    {
        return $v ? '<span class="badge text-bg-success">Active</span>' : '<span class="badge text-bg-secondary">Inactive</span>';
    }
}

if (! function_exists('cart_count')) {
    function cart_count(): int
    {
        return array_sum(session()->get('cart') ?? []);
    }
}

if (! function_exists('flash_alerts')) {
    function flash_alerts(): string
    {
        $out = '';
        foreach (['success' => 'success', 'error' => 'danger', 'info' => 'info'] as $key => $cls) {
            if ($msg = session()->getFlashdata($key)) {
                $out .= '<div class="alert alert-' . $cls . ' alert-dismissible fade show flash-alert" role="alert">'
                    . esc($msg) . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
            }
        }
        if ($errors = session()->getFlashdata('errors')) {
            $out .= '<div class="alert alert-danger alert-dismissible fade show flash-alert"><ul class="mb-0 ps-3">';
            foreach ((array) $errors as $e) {
                $out .= '<li>' . esc($e) . '</li>';
            }
            $out .= '</ul><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        }
        return $out;
    }
}

if (! function_exists('make_slug')) {
    function make_slug(string $text): string
    {
        $slug = url_title($text, '-', true);
        return $slug !== '' ? $slug : 'item-' . substr(md5($text . microtime()), 0, 6);
    }
}

if (! function_exists('site_name')) {
    function site_name(): string
    {
        return 'ShopKart';
    }
}

if (! function_exists('product_card')) {
    function product_card(array $p): string
    {
        return view('website/partials/product_card', ['p' => $p], ['saveData' => false]);
    }
}

if (! function_exists('nav_categories')) {
    function nav_categories(): array
    {
        return (new \App\Models\CategoryModel())->where('status', 1)->orderBy('name')->findAll();
    }
}

if (! function_exists('is_active')) {
    function is_active(string $path): string
    {
        $uri = trim(uri_string(), '/'); // relative to baseURL, so it works when the site lives in a subfolder
        return ($path === '' ? $uri === '' : str_starts_with($uri, $path)) ? 'active' : '';
    }
}

if (! function_exists('delete_form')) {
    function delete_form(string $action, string $msg = 'Delete this item? This cannot be undone.'): string
    {
        return view('admin/partials/delete_form', ['action' => $action, 'msg' => $msg], ['saveData' => false]);
    }
}

if (! function_exists('row_menu')) {
    /** "⋮" actions dropdown for admin table rows. See admin/partials/row_menu for the $items format. */
    function row_menu(array $items): string
    {
        return view('admin/partials/row_menu', ['items' => $items], ['saveData' => false]);
    }
}

if (! function_exists('date_cell')) {
    /** Date with the time underneath, for admin list tables. */
    function date_cell(?string $dt): string
    {
        if (! $dt) {
            return '<span class="text-muted">—</span>';
        }
        $t = strtotime($dt);
        return '<div class="date-cell">' . date('n/j/Y', $t) . '<small>' . date('h:i A', $t) . '</small></div>';
    }
}

if (! function_exists('setting')) {
    /** Editable website content (Admin > Website), loaded once per request. */
    function setting(string $key, string $default = ''): string
    {
        static $all = null;
        $all ??= (new \App\Models\SettingModel())->all();
        $v = $all[$key] ?? null;
        return $v === null || $v === '' ? $default : $v;
    }
}

if (! function_exists('setting_list')) {
    /** A setting stored as a JSON list (About us stats and promises). */
    function setting_list(string $key): array
    {
        $list = json_decode(setting($key, '[]'), true);
        return is_array($list) ? $list : [];
    }
}

if (! function_exists('tel_link')) {
    /** "+91 98765 43210" -> "tel:+919876543210" */
    function tel_link(string $phone): string
    {
        return 'tel:' . preg_replace('/[^\d+]/', '', $phone);
    }
}
