<?= $this->extend('website/layout') ?>
<?= $this->section('content') ?>
<section class="section position-relative overflow-hidden">
  <div class="confetti position-absolute top-0 start-0 w-100 h-100" style="pointer-events:none"></div>
  <div class="container text-center" style="max-width:640px">
    <svg class="result-icon result-ok" viewBox="0 0 120 120"><circle cx="60" cy="60" r="54"/><path d="M36 62l16 16 32-34"/></svg>
    <h1 class="fw-bold">Payment successful</h1>
    <p class="text-muted-2 fs-5">Thank you, <?= esc($order['name']) ?>. Your order is confirmed and will be packed shortly.</p>
    <div class="summary-card text-start my-4">
      <div class="d-flex justify-content-between mb-2"><span>Order number</span><strong><?= esc($order['order_no']) ?></strong></div>
      <div class="d-flex justify-content-between mb-2"><span>Transaction ID</span><strong><?= esc($order['txn_id']) ?></strong></div>
      <div class="d-flex justify-content-between mb-2"><span>Amount paid</span><strong><?= money($order['total']) ?></strong></div>
      <div class="d-flex justify-content-between"><span>Deliver to</span><strong class="text-end"><?= esc($order['city']) ?> - <?= esc($order['pincode']) ?></strong></div>
    </div>
    <a href="<?= base_url('account/orders/' . $order['id']) ?>" class="btn btn-brand btn-lg me-2">View order</a>
    <a href="<?= base_url('account/invoice/' . $order['id']) ?>" class="btn btn-outline-brand btn-lg">Invoice</a>
  </div>
</section>
<?= $this->endSection() ?>
