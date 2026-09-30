<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/** Keeps logged-in users/admins away from login & register pages. */
class GuestOnly implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $area = $arguments[0] ?? 'user';
        if ($area === 'admin' && session()->get('admin_id')) {
            return redirect()->to(base_url('admin/dashboard'));
        }
        if ($area === 'user' && session()->get('user_id')) {
            return redirect()->to(base_url('account'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
