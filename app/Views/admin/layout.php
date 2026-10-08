<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= esc($title ?? 'Admin') ?> | <?= site_name() ?> Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,800&family=Nunito+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="<?= base_url('assets/admin/css/admin.css') ?>" rel="stylesheet">
</head>
<body>
<?php
  $unreadEnq = (new \App\Models\EnquiryModel())->where('is_read', 0)->countAllResults();
  $uri       = uri_string();

  // Sidebar menu: [path, icon, label, badge] or [group label, icon, children]
  $nav = [
    ['admin/dashboard', 'bi-speedometer2', 'Dashboard', 0],
    ['Catalog', 'bi-grid', [
      ['admin/banners', 'bi-images', 'Banners', 0],
      ['admin/categories', 'bi-grid', 'Categories', 0],
      ['admin/subcategories', 'bi-diagram-3', 'Subcategories', 0],
      ['admin/products', 'bi-box-seam', 'Products', 0],
    ]],
    ['Sales', 'bi-receipt', [
      ['admin/orders', 'bi-receipt', 'Orders', 0],
      ['admin/users', 'bi-people', 'Users', 0],
      ['admin/reports', 'bi-bar-chart-line', 'Reports', 0],
      ['admin/visitors', 'bi-globe', 'Visitors & IPs', 0],
    ]],
    ['Website', 'bi-globe2', [
      ['admin/website/about', 'bi-info-circle', 'About us page', 0],
      ['admin/website/contact', 'bi-geo-alt', 'Contact details', 0],
      ['admin/testimonials', 'bi-chat-quote', 'Testimonials', 0],
    ]],
    ['Messages', 'bi-chat-dots', [
      ['admin/enquiries', 'bi-inbox', 'Enquiries', $unreadEnq],
    ]],
  ];

  // Page header: icon + subtitle per section; sub-pages (create / edit / view) get a Back button
  $pages = [
    'admin/dashboard'       => ['bi-speedometer2', 'Overview of sales, orders and store activity'],
    'admin/banners'         => ['bi-images', 'Manage home page slider banners'],
    'admin/categories'      => ['bi-grid', 'Organise the catalog into categories'],
    'admin/subcategories'   => ['bi-diagram-3', 'Group products inside each category'],
    'admin/products'        => ['bi-box-seam', 'Add, edit and track your products'],
    'admin/orders'          => ['bi-receipt', 'Review and manage customer orders'],
    'admin/users'           => ['bi-people', 'Registered customers and their accounts'],
    'admin/reports'         => ['bi-bar-chart-line', 'Sales reports by year and month'],
    'admin/visitors'        => ['bi-globe', 'Website visitors and the network IPs behind visits and purchases'],
    'admin/enquiries'       => ['bi-inbox', 'Product questions and contact us messages in one inbox'],
    'admin/website'         => ['bi-globe2', 'Content shown on the website'],
    'admin/testimonials'    => ['bi-chat-quote', 'Customer testimonials shown on the website'],
    'admin/change-password' => ['bi-key', 'Keep your admin account secure'],
  ];
  $section = implode('/', array_slice(explode('/', $uri), 0, 2));
  [$pageIcon, $pageSub] = $pages[$section] ?? ['bi-app', ''];
  $backUrl = substr_count($uri, '/') >= 2 && isset($pages[$section]) && $section !== 'admin/website' ? base_url($section) : null;

  $adminName = (string) session('admin_name');
  $initials  = strtoupper(implode('', array_map(static fn ($w) => $w[0] ?? '', array_slice(preg_split('/\s+/', trim($adminName) ?: 'A'), 0, 2))));
  $isActive  = static fn (string $p) => str_starts_with($uri, $p);
?>
<script>try { if (localStorage.getItem('adminSidebar') === 'mini') document.body.classList.add('sb-mini'); } catch (e) {}</script>
<div class="scrim" id="scrim"></div>
<aside class="sidebar" id="sidebar">
  <a href="<?= base_url('admin/dashboard') ?>" class="brand" title="<?= esc(site_name()) ?>">
    <span class="brand-logo"><i class="bi bi-bag-heart-fill"></i></span>
    <span class="sb-label"><span class="brand-name"><?= site_name() ?></span><span class="brand-sub">Admin panel</span></span>
  </a>
  <nav class="sb-nav">
    <?php foreach ($nav as $n): if (is_array($n[2])): ?>
      <div class="sb-group"><span class="sb-label"><?= $n[0] ?></span></div>
      <?php foreach ($n[2] as $c): ?>
        <a class="sb-link <?= $isActive($c[0]) ? 'active' : '' ?>" href="<?= base_url($c[0]) ?>" title="<?= $c[2] ?>"><i class="bi <?= $c[1] ?>"></i><span class="sb-label"><?= $c[2] ?></span><?php if ($c[3]): ?><span class="nav-badge"><?= $c[3] ?></span><?php endif; ?></a>
      <?php endforeach; ?>
    <?php else: ?>
      <a class="sb-link <?= $isActive($n[0]) ? 'active' : '' ?>" href="<?= base_url($n[0]) ?>" title="<?= $n[2] ?>"><i class="bi <?= $n[1] ?>"></i><span class="sb-label"><?= $n[2] ?></span></a>
    <?php endif; endforeach; ?>
    <div class="sb-group"><span class="sb-label">Account</span></div>
    <a class="sb-link <?= $isActive('admin/change-password') ? 'active' : '' ?>" href="<?= base_url('admin/change-password') ?>" title="Change password"><i class="bi bi-key"></i><span class="sb-label">Change password</span></a>
    <a class="sb-link" href="<?= base_url('admin/logout') ?>" title="Logout"><i class="bi bi-box-arrow-right"></i><span class="sb-label">Logout</span></a>
  </nav>
</aside>

<div class="main">
<header class="topbar">
  <button class="menu-toggle" id="menuBtn" aria-label="Toggle menu" title="Toggle menu"><i class="bi bi-list"></i></button>
  <div class="top-right">
    <a href="<?= base_url() ?>" target="_blank" class="icon-tile" title="View site"><i class="bi bi-shop"></i></a>
    <div class="dropdown">
      <button class="profile-pill" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="d-none d-sm-inline"><?= esc($adminName ?: 'Admin') ?></span><span class="avatar"><?= esc($initials) ?></span><i class="bi bi-chevron-down small"></i></button>
      <ul class="dropdown-menu dropdown-menu-end nav-drop">
        <li><a class="dropdown-item" href="<?= base_url() ?>" target="_blank"><i class="bi bi-box-arrow-up-right"></i>View site</a></li>
        <li><a class="dropdown-item <?= $isActive('admin/change-password') ? 'active' : '' ?>" href="<?= base_url('admin/change-password') ?>"><i class="bi bi-key"></i>Change password</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-danger" href="<?= base_url('admin/logout') ?>"><i class="bi bi-box-arrow-right"></i>Logout</a></li>
      </ul>
    </div>
  </div>
</header>

<main class="content">
  <section class="page-hero">
    <div class="d-flex align-items-center gap-3">
      <span class="hero-icon"><i class="bi <?= $pageIcon ?>"></i></span>
      <div><h1 class="hero-title"><?= esc($title ?? '') ?></h1><?php if ($pageSub): ?><p class="hero-sub"><?= esc($pageSub) ?></p><?php endif; ?></div>
    </div>
    <div class="hero-actions">
      <?= $this->renderSection('hero_actions') ?>
      <?php if ($backUrl): ?><a href="<?= $backUrl ?>" class="btn-back"><i class="bi bi-arrow-left"></i>Back</a><?php endif; ?>
    </div>
    <span class="hero-line"></span>
  </section>
  <?= flash_alerts() ?>
  <?= $this->renderSection('content') ?>
</main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/admin/js/admin.js') ?>"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
