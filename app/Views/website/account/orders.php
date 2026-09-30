<?= $this->extend('website/layout') ?>
<?= $this->section('content') ?>
<div class="page-head"><div class="container"><h1 class="fw-bold mb-0">My orders</h1></div></div>
<section class="section pt-4"><div class="container"><div class="row g-4">
  <div class="col-lg-3"><?= $this->include('website/account/nav') ?></div>
  <div class="col-lg-9">
    <?php if ($orders): ?>
    <div class="table-responsive"><table class="table align-middle">
      <thead><tr><th>Order</th><th>Date</th><th>Total</th><th>Payment</th><th>Status</th><th></th></tr></thead>
      <tbody><?php foreach ($orders as $o): ?>
        <tr><td class="fw-bold"><?= esc($o['order_no']) ?></td><td><?= date('d M Y', strtotime($o['created_at'])) ?></td><td><?= money($o['total']) ?></td><td><?= status_badge($o['payment_status']) ?></td><td><?= status_badge($o['status']) ?></td>
        <td class="text-end text-nowrap">
          <a class="btn btn-sm btn-outline-brand" href="<?= base_url('account/orders/' . $o['id']) ?>">View</a>
          <a class="btn btn-sm btn-brand" href="<?= base_url('account/invoice/' . $o['id']) ?>">Invoice</a>
          <?php if ($o['payment_status'] !== 'paid'): ?><a class="btn btn-sm btn-accent" href="<?= base_url('payment/' . $o['order_no']) ?>">Pay now</a><?php endif; ?></td></tr>
      <?php endforeach; ?></tbody>
    </table></div>
    <?= $pager->links('default', 'bootstrap_full') ?>
    <?php else: ?><div class="text-center py-5"><i class="bi bi-box fs-1 text-muted-2"></i><h5 class="mt-2">No orders yet</h5><a href="<?= base_url('shop') ?>" class="btn btn-brand">Browse products</a></div><?php endif; ?>
  </div>
</div></div></section>
<?= $this->endSection() ?>
