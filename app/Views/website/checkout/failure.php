<?= $this->extend('website/layout') ?>
<?= $this->section('content') ?>
<section class="section">
  <div class="container text-center" style="max-width:600px">
    <svg class="result-icon result-bad" viewBox="0 0 120 120"><circle cx="60" cy="60" r="54"/><path d="M42 42l36 36M78 42L42 78"/></svg>
    <h1 class="fw-bold">Payment failed</h1>
    <p class="text-muted-2 fs-5">We could not complete the payment for order <strong><?= esc($order['order_no']) ?></strong>. Nothing was charged. You can try again.</p>
    <a href="<?= base_url('payment/' . $order['order_no']) ?>" class="btn btn-accent btn-lg me-2">Try payment again</a>
    <a href="<?= base_url('cart') ?>" class="btn btn-outline-brand btn-lg">Back to cart</a>
  </div>
</section>
<?= $this->endSection() ?>
