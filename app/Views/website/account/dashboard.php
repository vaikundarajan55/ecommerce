<?= $this->extend('website/layout') ?>
<?= $this->section('content') ?>
<div class="page-head"><div class="container"><h1 class="fw-bold mb-0">Hello, <?= esc(explode(' ', $user['name'])[0]) ?></h1></div></div>
<section class="section pt-4"><div class="container"><div class="row g-4">
  <div class="col-lg-3"><?= $this->include('website/account/nav') ?></div>
  <div class="col-lg-9">
    <div class="row g-3 mb-4">
      <div class="col-md-4" data-aos="fade-up"><div class="stat-tile"><div class="text-muted-2">Total orders</div><div class="num"><?= $total ?></div></div></div>
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="80"><div class="stat-tile"><div class="text-muted-2">In progress</div><div class="num"><?= $pending ?></div></div></div>
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="160"><div class="stat-tile"><div class="text-muted-2">Total spent</div><div class="num"><?= money($spent) ?></div></div></div>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-2"><h5 class="mb-0">Recent orders</h5><a href="<?= base_url('account/orders') ?>" class="link-more">See all</a></div>
    <?php if ($recent): ?>
    <div class="table-responsive"><table class="table align-middle">
      <thead><tr><th>Order</th><th>Date</th><th>Total</th><th>Payment</th><th>Status</th><th></th></tr></thead>
      <tbody><?php foreach ($recent as $o): ?>
        <tr><td class="fw-bold"><?= esc($o['order_no']) ?></td><td><?= date('d M Y', strtotime($o['created_at'])) ?></td><td><?= money($o['total']) ?></td><td><?= status_badge($o['payment_status']) ?></td><td><?= status_badge($o['status']) ?></td>
        <td class="text-end"><a class="btn btn-sm btn-outline-brand" href="<?= base_url('account/orders/' . $o['id']) ?>">View</a></td></tr>
      <?php endforeach; ?></tbody>
    </table></div>
    <?php else: ?><div class="text-muted-2 py-4">You have not placed any orders yet. <a href="<?= base_url('shop') ?>">Start shopping</a></div><?php endif; ?>
  </div>
</div></div></section>
<?= $this->endSection() ?>
