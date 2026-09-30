<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= esc($title ?? 'Home') ?> | <?= site_name() ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,800&family=Nunito+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link href="<?= base_url('assets/website/css/site.css') ?>" rel="stylesheet">
  <?= $this->renderSection('head') ?>
</head>
<body>
<div class="topstrip py-2 d-none d-md-block">
  <div class="container d-flex justify-content-between">
    <span><i class="bi bi-truck me-1"></i> Free delivery on orders above ₹999</span>
    <span><i class="bi bi-telephone me-1"></i> <a href="tel:+919876543210">+91 98765 43210</a></span>
  </div>
</div>

<nav class="navbar navbar-expand-lg site-nav">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="<?= base_url() ?>"><span class="brand-mark"><i class="bi bi-bag-heart-fill"></i></span><?= site_name() ?></a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-label="Menu"><i class="bi bi-list fs-2"></i></button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
        <li class="nav-item"><a class="nav-link <?= is_active('') ?>" href="<?= base_url() ?>">Home</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?= is_active('shop') ?>" href="#" data-bs-toggle="dropdown">Shop</a>
          <ul class="dropdown-menu border-0 shadow">
            <li><a class="dropdown-item" href="<?= base_url('shop') ?>">All products</a></li>
            <li><hr class="dropdown-divider"></li>
            <?php foreach (nav_categories() as $c): ?>
              <li><a class="dropdown-item" href="<?= base_url('shop?cat=' . $c['slug']) ?>"><?= esc($c['name']) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link <?= is_active('about') ?>" href="<?= base_url('about') ?>">About us</a></li>
        <li class="nav-item"><a class="nav-link <?= is_active('contact') ?>" href="<?= base_url('contact') ?>">Contact us</a></li>
      </ul>
      <form class="search-form d-flex me-lg-3 mb-2 mb-lg-0" action="<?= base_url('shop') ?>" method="get" role="search">
        <input class="form-control" type="search" name="q" placeholder="Search products" value="<?= esc($_GET['q'] ?? '') ?>" aria-label="Search">
        <button class="btn btn-brand" aria-label="Search"><i class="bi bi-search"></i></button>
      </form>
      <div class="d-flex align-items-center gap-2">
        <a href="<?= base_url('cart') ?>" class="nav-link cart-link" aria-label="Cart"><i class="bi bi-cart3 fs-5"></i>
          <?php if (cart_count() > 0): ?><span class="cart-badge"><?= cart_count() ?></span><?php endif; ?></a>
        <?php if (session('user_id')): ?>
          <div class="dropdown">
            <a class="btn btn-outline-brand btn-sm dropdown-toggle" href="#" data-bs-toggle="dropdown"><i class="bi bi-person-circle me-1"></i><?= esc(explode(' ', session('user_name'))[0]) ?></a>
            <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
              <li><a class="dropdown-item" href="<?= base_url('account') ?>">Dashboard</a></li>
              <li><a class="dropdown-item" href="<?= base_url('account/orders') ?>">My orders</a></li>
              <li><a class="dropdown-item" href="<?= base_url('account/profile') ?>">Profile</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="<?= base_url('logout') ?>">Logout</a></li>
            </ul>
          </div>
        <?php else: ?>
          <a href="<?= base_url('login') ?>" class="btn btn-outline-brand btn-sm">Login</a>
          <a href="<?= base_url('register') ?>" class="btn btn-brand btn-sm">Register</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<?php $flash = flash_alerts(); if ($flash): ?><div class="container mt-3"><?= $flash ?></div><?php endif; ?>

<main><?= $this->renderSection('content') ?></main>

<footer class="site-footer mt-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4">
        <h6 class="fs-5"><?= site_name() ?></h6>
        <p class="pe-lg-5">Quality products for home, fashion and gadgets, delivered to your door.</p>
      </div>
      <div class="col-6 col-lg-2">
        <h6>Shop</h6>
        <ul class="list-unstyled">
          <?php foreach (array_slice(nav_categories(), 0, 4) as $c): ?>
            <li class="mb-1"><a href="<?= base_url('shop?cat=' . $c['slug']) ?>"><?= esc($c['name']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="col-6 col-lg-2">
        <h6>Company</h6>
        <ul class="list-unstyled">
          <li class="mb-1"><a href="<?= base_url('about') ?>">About us</a></li>
          <li class="mb-1"><a href="<?= base_url('contact') ?>">Contact us</a></li>
          <li class="mb-1"><a href="<?= base_url('account') ?>">My account</a></li>
          <li class="mb-1"><a href="<?= base_url('admin/login') ?>">Admin</a></li>
        </ul>
      </div>
      <div class="col-lg-4">
        <h6>Contact</h6>
        <p class="mb-1"><i class="bi bi-geo-alt me-2"></i>12 Beach Road, Puducherry 605001</p>
        <p class="mb-1"><i class="bi bi-envelope me-2"></i>support@shopkart.test</p>
        <p class="mb-1"><i class="bi bi-telephone me-2"></i>+91 98765 43210</p>
      </div>
    </div>
    <hr class="border-secondary my-4">
    <div class="small">&copy; <?= date('Y') ?> <?= site_name() ?>. Payments on this demo site are simulated.</div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="<?= base_url('assets/website/js/site.js') ?>"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
