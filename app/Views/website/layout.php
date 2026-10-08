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
<?php
  $userName = (string) session('user_name');
  $userInit = strtoupper(implode('', array_map(static fn ($w) => $w[0] ?? '', array_slice(preg_split('/\s+/', trim($userName) ?: 'U'), 0, 2))));
  $cartQty  = cart_count();
  // Store contact details (Admin > Website > Contact details)
  $cPhone   = setting('contact_phone');
  $cEmail   = setting('contact_email');
  $cAddress = setting('contact_address');
  $cHours   = setting('contact_hours');
?>
<div class="topstrip d-none d-md-block">
  <div class="container d-flex justify-content-between align-items-center">
    <span class="ts-item"><i class="bi bi-truck"></i>Free delivery on orders above ₹999</span>
    <div class="d-flex align-items-center gap-4">
      <?php if ($cEmail): ?><a class="ts-item" href="mailto:<?= esc($cEmail) ?>"><i class="bi bi-envelope"></i><?= esc($cEmail) ?></a><?php endif; ?>
      <?php if ($cPhone): ?><a class="ts-item" href="<?= esc(tel_link($cPhone)) ?>"><i class="bi bi-telephone"></i><?= esc($cPhone) ?></a><?php endif; ?>
    </div>
  </div>
</div>

<nav class="navbar navbar-expand-lg site-nav" id="siteNav">
  <div class="container">
    <a class="navbar-brand" href="<?= base_url() ?>"><span class="brand-mark"><i class="bi bi-bag-heart-fill"></i></span><span class="brand-text"><?= site_name() ?><small>Shop smart, live better</small></span></a>
    <button class="nav-burger d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Menu"><span></span><span></span><span></span></button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav nav-pills-wrap mx-lg-auto">
        <li class="nav-item"><a class="nav-link <?= is_active('') ?>" href="<?= base_url() ?>"><i class="bi bi-house-door"></i>Home</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?= is_active('shop') ?>" href="#" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-grid"></i>Shop</a>
          <ul class="dropdown-menu nav-drop">
            <li><a class="dropdown-item" href="<?= base_url('shop') ?>"><i class="bi bi-shop"></i>All products</a></li>
            <li><hr class="dropdown-divider"></li>
            <?php foreach (nav_categories() as $c): ?>
              <li><a class="dropdown-item" href="<?= base_url('shop?cat=' . $c['slug']) ?>"><i class="bi bi-tag"></i><?= esc($c['name']) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link <?= is_active('about') ?>" href="<?= base_url('about') ?>"><i class="bi bi-info-circle"></i>About us</a></li>
        <li class="nav-item"><a class="nav-link <?= is_active('contact') ?>" href="<?= base_url('contact') ?>"><i class="bi bi-chat-dots"></i>Contact us</a></li>
      </ul>
      <form class="search-form" action="<?= base_url('shop') ?>" method="get" role="search">
        <i class="bi bi-search"></i>
        <input type="search" name="q" placeholder="Search products" value="<?= esc($_GET['q'] ?? '') ?>" aria-label="Search products">
        <button aria-label="Search"><i class="bi bi-arrow-right"></i></button>
      </form>
      <div class="nav-actions">
        <a href="<?= base_url('cart') ?>" class="icon-btn cart-link <?= is_active('cart') ?>" aria-label="Cart<?= $cartQty ? ", {$cartQty} items" : '' ?>"><i class="bi bi-bag"></i>
          <?php if ($cartQty > 0): ?><span class="cart-badge"><?= $cartQty ?></span><?php endif; ?></a>
        <?php if (session('user_id')): ?>
          <div class="dropdown">
            <button class="user-pill" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="user-avatar"><?= esc($userInit) ?></span><span class="user-name"><?= esc(explode(' ', $userName)[0]) ?></span><i class="bi bi-chevron-down"></i></button>
            <ul class="dropdown-menu dropdown-menu-end nav-drop">
              <li class="drop-head"><strong><?= esc($userName) ?></strong><small>Customer account</small></li>
              <li><a class="dropdown-item" href="<?= base_url('account') ?>"><i class="bi bi-speedometer2"></i>Dashboard</a></li>
              <li><a class="dropdown-item" href="<?= base_url('account/orders') ?>"><i class="bi bi-box-seam"></i>My orders</a></li>
              <li><a class="dropdown-item" href="<?= base_url('account/profile') ?>"><i class="bi bi-person"></i>Profile</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right"></i>Logout</a></li>
            </ul>
          </div>
        <?php else: ?>
          <a href="<?= base_url('login') ?>" class="btn-ghost">Login</a>
          <a href="<?= base_url('register') ?>" class="btn-grad-nav">Register</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<?php $flash = flash_alerts(); if ($flash): ?><div class="container mt-3"><?= $flash ?></div><?php endif; ?>

<main><?= $this->renderSection('content') ?></main>

<footer class="site-footer">
  <div class="footer-glow" aria-hidden="true"></div>
  <div class="container position-relative">
    <div class="row g-4 g-lg-5">
      <div class="col-lg-4" data-aos="fade-up">
        <a class="navbar-brand footer-brand" href="<?= base_url() ?>"><span class="brand-mark"><i class="bi bi-bag-heart-fill"></i></span><span class="brand-text"><?= site_name() ?><small>Shop smart, live better</small></span></a>
        <p class="footer-about">Quality products for home, fashion and gadgets, delivered to your door.</p>
        <div class="footer-perk"><i class="bi bi-truck"></i>Free delivery on orders above ₹999</div>
      </div>
      <div class="col-6 col-lg-2" data-aos="fade-up" data-aos-delay="80">
        <h6 class="footer-title">Shop</h6>
        <ul class="footer-links">
          <li><a href="<?= base_url('shop') ?>"><i class="bi bi-chevron-right"></i>All products</a></li>
          <?php foreach (array_slice(nav_categories(), 0, 4) as $c): ?>
            <li><a href="<?= base_url('shop?cat=' . $c['slug']) ?>"><i class="bi bi-chevron-right"></i><?= esc($c['name']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="col-6 col-lg-2" data-aos="fade-up" data-aos-delay="160">
        <h6 class="footer-title">Company</h6>
        <ul class="footer-links">
          <li><a href="<?= base_url('about') ?>"><i class="bi bi-chevron-right"></i>About us</a></li>
          <li><a href="<?= base_url('contact') ?>"><i class="bi bi-chevron-right"></i>Contact us</a></li>
          <li><a href="<?= base_url('account') ?>"><i class="bi bi-chevron-right"></i>My account</a></li>
          <li><a href="<?= base_url('admin/login') ?>"><i class="bi bi-chevron-right"></i>Admin</a></li>
        </ul>
      </div>
      <div class="col-lg-4" data-aos="fade-up" data-aos-delay="240">
        <h6 class="footer-title">Contact</h6>
        <ul class="footer-contact">
          <?php if ($cAddress): ?><li><span><i class="bi bi-geo-alt"></i></span><?= esc($cAddress) ?></li><?php endif; ?>
          <?php if ($cEmail): ?><li><span><i class="bi bi-envelope"></i></span><a href="mailto:<?= esc($cEmail) ?>"><?= esc($cEmail) ?></a></li><?php endif; ?>
          <?php if ($cPhone): ?><li><span><i class="bi bi-telephone"></i></span><a href="<?= esc(tel_link($cPhone)) ?>"><?= esc($cPhone) ?></a></li><?php endif; ?>
          <?php if ($cHours): ?><li><span><i class="bi bi-clock"></i></span><?= esc($cHours) ?></li><?php endif; ?>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>&copy; <?= date('Y') ?> <?= site_name() ?>. Payments on this demo site are simulated.</span>
      <button type="button" class="to-top" id="toTop" aria-label="Back to top"><i class="bi bi-arrow-up"></i></button>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="<?= base_url('assets/website/js/site.js') ?>"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
