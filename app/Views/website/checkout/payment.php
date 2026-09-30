<?= $this->extend('website/layout') ?>
<?= $this->section('content') ?>
<div class="gw-wrap">
  <div class="gw-card">
    <div class="gw-head d-flex justify-content-between align-items-center">
      <div><div class="small text-muted-2">Paying <?= site_name() ?></div><div class="fs-3 fw-bold brand-font"><?= money($order['total']) ?></div></div>
      <div class="text-end small text-muted-2">Order<br><strong><?= esc($order['order_no']) ?></strong></div>
    </div>
    <form id="gwForm" action="<?= base_url('payment/' . $order['order_no']) ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="result" value="">
      <div class="gw-body" id="gwFields">
        <div class="alert alert-warning py-2 small"><i class="bi bi-info-circle me-1"></i>Demo gateway. Use any card details, or pick a result below.</div>
        <div class="mb-3"><label class="form-label">Card number</label><input id="cardNumber" class="form-control" inputmode="numeric" placeholder="4111 1111 1111 1111" required></div>
        <div class="row g-3 mb-3">
          <div class="col-6"><label class="form-label">Expiry</label><input class="form-control" placeholder="MM/YY" maxlength="5" required></div>
          <div class="col-6"><label class="form-label">CVV</label><input class="form-control" type="password" maxlength="4" placeholder="•••" required></div>
        </div>
        <div class="mb-4"><label class="form-label">Name on card</label><input class="form-control" value="<?= esc($order['name']) ?>" required></div>
        <button class="btn btn-brand btn-lg w-100 mb-2" data-result="success">Pay <?= money($order['total']) ?></button>
        <button class="btn btn-outline-danger w-100" data-result="failure" formnovalidate>Simulate failed payment</button>
      </div>
      <div class="gw-spinner" id="gwSpinner"><div class="ring"></div><strong>Processing payment…</strong><div class="small text-muted-2">Please do not refresh or close this page.</div></div>
    </form>
  </div>
</div>
<?= $this->endSection() ?>
