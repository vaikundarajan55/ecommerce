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
  $unreadCon = (new \App\Models\ContactModel())->where('is_read', 0)->countAllResults();
  $nav = [
    ['admin/dashboard', 'bi-speedometer2', 'Dashboard', 0],
    ['GROUP', 'Catalog'],
    ['admin/banners', 'bi-images', 'Banners', 0],
    ['admin/categories', 'bi-grid', 'Categories', 0],
    ['admin/subcategories', 'bi-diagram-3', 'Subcategories', 0],
    ['admin/products', 'bi-box-seam', 'Products', 0],
    ['GROUP', 'Sales'],
    ['admin/orders', 'bi-receipt', 'Orders', 0],
    ['admin/users', 'bi-people', 'Users', 0],
    ['GROUP', 'Messages'],
    ['admin/enquiries', 'bi-question-circle', 'Enquiries', $unreadEnq],
    ['admin/contacts', 'bi-envelope', 'Contact us', $unreadCon],
    ['GROUP', 'Account'],
    ['admin/change-password', 'bi-key', 'Change password', 0],
    ['admin/logout', 'bi-box-arrow-right', 'Logout', 0],
  ];
?>
<div class="scrim" id="scrim"></div>
<aside class="sidebar" id="sidebar">
  <a href="<?= base_url('admin/dashboard') ?>" class="logo"><i class="bi bi-bag-heart-fill"></i><?= site_name() ?></a>
  <nav class="nav flex-column">
    <?php foreach ($nav as $n): if ($n[0] === 'GROUP'): ?>
      <div class="group"><?= $n[1] ?></div>
    <?php else: ?>
      <a class="nav-link <?= str_starts_with(uri_string(), $n[0]) ? 'active' : '' ?>" href="<?= base_url($n[0]) ?>"><i class="bi <?= $n[1] ?>"></i><?= $n[2] ?><?php if ($n[3]): ?><span class="badge text-bg-warning"><?= $n[3] ?></span><?php endif; ?></a>
    <?php endif; endforeach; ?>
  </nav>
</aside>

<div class="main">
  <header class="topbar d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-3">
      <button class="btn btn-light d-lg-none" id="menuBtn" aria-label="Menu"><i class="bi bi-list fs-5"></i></button>
      <h1 class="h5 mb-0"><?= esc($title ?? '') ?></h1>
    </div>
    <div class="d-flex align-items-center gap-3">
      <a href="<?= base_url() ?>" target="_blank" class="btn btn-sm btn-outline-brand"><i class="bi bi-box-arrow-up-right me-1"></i>View site</a>
      <span class="fw-bold d-none d-sm-inline"><i class="bi bi-person-circle me-1"></i><?= esc(session('admin_name')) ?></span>
    </div>
  </header>
  <div class="content">
    <?= flash_alerts() ?>
    <?= $this->renderSection('content') ?>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/admin/js/admin.js') ?>"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
