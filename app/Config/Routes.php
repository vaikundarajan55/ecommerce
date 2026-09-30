<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ------------------------------------------------------------------
// Website (front end)
// ------------------------------------------------------------------
// URL: http://localhost/ecommerce/        -> app/Controllers/Website, app/Views/website
$routes->group('', ['namespace' => 'App\Controllers\Website'], static function ($routes) {
    $routes->get('/', 'Home::index');
    $routes->get('about', 'Home::about');
    $routes->get('contact', 'Home::contact');
    $routes->post('contact', 'Home::sendContact');

    $routes->get('shop', 'Shop::index');
    $routes->get('shop/(:segment)', 'Shop::view/$1');
    $routes->post('enquiry/(:num)', 'Shop::enquiry/$1');

    $routes->get('cart', 'Cart::index');
    $routes->post('cart/add/(:num)', 'Cart::add/$1');
    $routes->post('cart/update', 'Cart::update');
    $routes->get('cart/remove/(:num)', 'Cart::remove/$1');

    // Auth
    $routes->group('', ['filter' => 'guest:user'], static function ($routes) {
        $routes->get('login', 'Auth::login');
        $routes->post('login', 'Auth::attempt');
        $routes->get('register', 'Auth::register');
        $routes->post('register', 'Auth::store');
        $routes->get('forgot-password', 'Auth::forgot');
        $routes->post('forgot-password', 'Auth::sendReset');
        $routes->get('reset-password/(:segment)', 'Auth::reset/$1');
        $routes->post('reset-password/(:segment)', 'Auth::updatePassword/$1');
    });
    $routes->get('logout', 'Auth::logout');

    // Checkout + payment (login required)
    $routes->group('', ['filter' => 'userAuth'], static function ($routes) {
        $routes->get('checkout', 'Checkout::index');
        $routes->post('checkout', 'Checkout::place');
        $routes->get('payment/(:segment)', 'Checkout::payment/$1');
        $routes->post('payment/(:segment)', 'Checkout::process/$1');
        $routes->get('order-success/(:segment)', 'Checkout::success/$1');
        $routes->get('order-failed/(:segment)', 'Checkout::failure/$1');

        // Customer account
        $routes->get('account', 'Account::dashboard');
        $routes->get('account/profile', 'Account::profile');
        $routes->post('account/profile', 'Account::updateProfile');
        $routes->get('account/orders', 'Account::orders');
        $routes->get('account/orders/(:num)', 'Account::orderView/$1');
        $routes->get('account/invoice/(:num)', 'Account::invoice/$1');
        $routes->get('account/change-password', 'Account::password');
        $routes->post('account/change-password', 'Account::updatePassword');
    });
});

// ------------------------------------------------------------------
// Admin panel
// ------------------------------------------------------------------
// URL: http://localhost/ecommerce/admin/  -> app/Controllers/Admin, app/Views/admin
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], static function ($routes) {
    $routes->get('/', 'Auth::index');

    $routes->group('', ['filter' => 'guest:admin'], static function ($routes) {
        $routes->get('login', 'Auth::login');
        $routes->post('login', 'Auth::attempt');
    });
    $routes->get('logout', 'Auth::logout');

    $routes->group('', ['filter' => 'adminAuth'], static function ($routes) {
        $routes->get('dashboard', 'Dashboard::index');

        foreach (['banners' => 'Banners', 'categories' => 'Categories', 'subcategories' => 'Subcategories', 'products' => 'Products'] as $uri => $ctl) {
            $routes->get($uri, "$ctl::index");
            $routes->get("$uri/create", "$ctl::create");
            $routes->post("$uri/store", "$ctl::store");
            $routes->get("$uri/edit/(:num)", "$ctl::edit/$1");
            $routes->post("$uri/update/(:num)", "$ctl::update/$1");
            $routes->post("$uri/delete/(:num)", "$ctl::delete/$1");
        }

        $routes->get('orders', 'Orders::index');
        $routes->get('orders/view/(:num)', 'Orders::view/$1');
        $routes->post('orders/status/(:num)', 'Orders::status/$1');
        $routes->get('orders/invoice/(:num)', 'Orders::invoice/$1');

        $routes->get('users', 'Users::index');
        $routes->post('users/toggle/(:num)', 'Users::toggle/$1');
        $routes->post('users/delete/(:num)', 'Users::delete/$1');

        $routes->get('enquiries', 'Enquiries::index');
        $routes->get('enquiries/view/(:num)', 'Enquiries::view/$1');
        $routes->post('enquiries/delete/(:num)', 'Enquiries::delete/$1');

        $routes->get('contacts', 'Contacts::index');
        $routes->get('contacts/view/(:num)', 'Contacts::view/$1');
        $routes->post('contacts/delete/(:num)', 'Contacts::delete/$1');

        $routes->get('change-password', 'Password::index');
        $routes->post('change-password', 'Password::update');
    });
});
