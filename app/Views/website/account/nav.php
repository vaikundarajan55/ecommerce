<?php
$uri   = uri_string();
$name  = (string) session('user_name');
$init  = strtoupper(implode('', array_map(static fn ($w) => $w[0] ?? '', array_slice(preg_split('/\s+/', trim($name) ?: 'U'), 0, 2))));
// [url, icon, label, active]
$links = [
    ['account', 'bi-speedometer2', 'Dashboard', $uri === 'account'],
    ['account/orders', 'bi-box-seam', 'My orders', str_contains($uri, 'orders') || str_contains($uri, 'invoice')],
    ['account/profile', 'bi-person', 'Profile', str_contains($uri, 'profile')],
    ['account/change-password', 'bi-key', 'Change password', str_contains($uri, 'change-password')],
];
?>
<nav class="acc-nav" aria-label="My account">
  <div class="acc-user">
    <span class="acc-avatar"><?= esc($init) ?></span>
    <div class="min-w-0"><div class="acc-name"><?= esc($name ?: 'My account') ?></div><small>Customer account</small></div>
  </div>
  <div class="acc-links">
    <?php foreach ($links as $i => [$url, $icon, $label, $on]): ?>
      <a class="acc-link <?= $on ? 'active' : '' ?>" href="<?= base_url($url) ?>" style="--i: <?= $i ?>"<?= $on ? ' aria-current="page"' : '' ?>><i class="bi <?= $icon ?>"></i><span><?= $label ?></span><i class="bi bi-chevron-right acc-arrow"></i></a>
    <?php endforeach; ?>
    <a class="acc-link acc-logout" href="<?= base_url('logout') ?>" style="--i: <?= count($links) ?>"><i class="bi bi-box-arrow-right"></i><span>Logout</span><i class="bi bi-chevron-right acc-arrow"></i></a>
  </div>
</nav>
