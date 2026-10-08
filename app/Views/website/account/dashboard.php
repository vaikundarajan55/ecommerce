<?= $this->extend('website/layout') ?>
<?= $this->section('content') ?>
<div class="page-head"><div class="container"><h1 class="fw-bold mb-0">Hello, <?= esc(explode(' ', $user['name'])[0]) ?></h1></div></div>
<section class="section pt-4"><div class="container">
  <?php
    $pct   = static fn (int $n) => $total ? round($n / $total * 100, 1) : 0;
    // [tone, label, count, caption, icon, bar %]
    $cards = [
      ['m-blue',  'Total orders', $total,   money($spent) . ' spent',         'bi-bag-check-fill',    100],
      ['m-green', 'Delivered',    $done,    $pct($done) . '% delivered',      'bi-check-circle-fill', $pct($done)],
      ['m-amber', 'In progress',  $pending, $pct($pending) . '% on the way',  'bi-truck',             $pct($pending)],
      ['m-red',   'Cancelled',    $cancel,  $pct($cancel) . '% cancelled',    'bi-x-octagon-fill',    $pct($cancel)],
    ];
  ?>
  <section class="metrics-card mb-4">
    <h2 class="metrics-title">Order status metrics</h2>
    <p class="metrics-sub">Summary of your orders, deliveries and spending</p>
    <div class="row g-3">
      <?php foreach ($cards as $i => [$tone, $label, $num, $caption, $icon, $fill]): ?>
        <div class="col-sm-6 col-lg-3">
          <div class="metric <?= $tone ?>" style="--rd: <?= $i * 120 ?>ms">
            <div class="metric-top">
              <span class="metric-label"><?= esc($label) ?></span>
              <span class="metric-ico"><i class="bi <?= $icon ?>"></i></span>
            </div>
            <strong class="metric-num" data-count="<?= $num ?>"><?= $num ?></strong>
            <span class="metric-caption"><?= esc($caption) ?></span>
            <span class="metric-bar"><span style="--w: <?= $fill ?>%"></span></span>
            <span class="metric-dot"></span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
<div class="row g-4">
  <div class="col-lg-3"><?= $this->include('website/account/nav') ?></div>
  <div class="col-lg-9">
    <section class="order-card">
      <div class="order-head">
        <div><h2 class="metrics-title">Recent orders</h2><p class="metrics-sub mb-0">Your latest orders, payment status and delivery progress</p></div>
        <a href="<?= base_url('account/orders') ?>" class="btn-soft">See all<i class="bi bi-arrow-right"></i></a>
      </div>
      <?php if ($recent): ?>
        <?= view('website/account/orders_table', ['orders' => $recent]) ?>
      <?php else: ?><div class="text-muted-2 py-4">You have not placed any orders yet. <a href="<?= base_url('shop') ?>">Start shopping</a></div><?php endif; ?>
    </section>
  </div>
</div></div></section>
<?= $this->endSection() ?>
