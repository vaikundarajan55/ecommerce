<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

abstract class AdminBase extends BaseController
{
    protected function render(string $view, array $data = [])
    {
        return view('admin/' . $view, $data);
    }
}
