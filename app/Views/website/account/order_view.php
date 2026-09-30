<?= $this->extend('website/layout') ?>
<?= $this->section('content') ?>
<div class="page-head"><div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
  <h1 class="fw-bold mb-0">Order <?= esc($order['order_no']) ?></h1>
  <a class="btn btn-brand" href="<?= base_url('account/invoice/' . $order['id']) ?>"><i class="bi bi-receipt me-1"></i>View invoice</a>
</div></div>
<section class="section pt-4"><div class="container"><div class="row g-4">
  <div class="col-lg-3"><?= $this->include('website/account/nav') ?></div>
  <div class="col-lg-9">
    <?php $steps = ['placed', 'processing', 'shipped', 'delivered']; $idx = array_search($order['status'], $steps); ?>
    <?php if ($order['status'] === 'cancelled'): ?><div class="alert alert-danger">This order was cancelled.</div>
    <?php else: ?>
    <div class="d-flex justify-content-between text-center mb-4">
      <?php foreach ($steps as $n => $s): ?>
        <div class="flex-fill"><div class="mx-auto rounded-circle d-grid place-items-center mb-1 <?= $n <= $idx ? 'bg-success text-white' : 'bg-light text-muted' ?>" style="width:38px;height:38px;place-items:center"><i class="bi <?= $n <= $idx ? 'bi-check-lg' : 'bi-circle' ?>"></i></div><div class="small fw-bold"><?= ucfirst($s) ?></div></div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <div class="table-responsive"><table class="table align-middle">
      <thead><tr><th>Item</th><th>Price</th><th>Qty</th><th class="text-end">Total</th></tr></thead>
      <tbody><?php foreach ($items as $i): ?><tr><td><?= esc($i['name']) ?></td><td><?= money($i['price']) ?></td><td><?= $i['qty'] ?></td><td class="text-end"><?= money($i['total']) ?></td></tr><?php endforeach; ?></tbody>
      <tfoot><tr><td colspan="3" class="text-end">Subtotal</td><td class="text-end"><?= money($order['subtotal']) ?></td></tr>
      <tr><td colspan="3" class="text-end">Shipping</td><td class="text-end"><?= money($order['shipping']) ?></td></tr>
      <tr class="fw-bold"><td colspan="3" class="text-end">Total</td><td class="text-end"><?= money($order['total']) ?></td></tr></tfoot>
    </table></div>
    <div class="row g-3">
      <div class="col-md-6"><div class="summary-card"><h6>Delivery address</h6><?= esc($order['name']) ?><br><?= esc($order['address']) ?><br><?= esc($order['city']) ?> - <?= esc($order['pincode']) ?><br><?= esc($order['phone']) ?></div></div>
      <div class="col-md-6"><div class="summary-card"><h6>Payment</h6>Status: <?= status_badge($order['payment_status']) ?><br>Transaction: <?= esc($order['txn_id'] ?: '—') ?><br>Placed: <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?>
        <?php if ($order['payment_status'] !== 'paid'): ?><div class="mt-2"><a href="<?= base_url('payment/' . $order['order_no']) ?>" class="btn btn-accent btn-sm">Pay now</a></div><?php endif; ?></div></div>
    </div>
  </div>
</div></div></section>
<?= $this->endSection() ?>
