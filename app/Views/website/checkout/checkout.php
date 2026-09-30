<?= $this->extend('website/layout') ?>
<?= $this->section('content') ?>
<div class="page-head"><div class="container"><h1 class="fw-bold mb-0">Checkout</h1></div></div>
<section class="section pt-4">
  <div class="container">
    <form action="<?= base_url('checkout') ?>" method="post" class="row g-4">
      <?= csrf_field() ?>
      <div class="col-lg-7">
        <h5 class="mb-3">Delivery details</h5>
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label">Full name</label><input class="form-control" name="name" value="<?= esc(old('name', $user['name'])) ?>" required></div>
          <div class="col-md-6"><label class="form-label">Email</label><input type="email" class="form-control" name="email" value="<?= esc(old('email', $user['email'])) ?>" required></div>
          <div class="col-md-6"><label class="form-label">Phone</label><input class="form-control" name="phone" value="<?= esc(old('phone', $user['phone'])) ?>" required></div>
          <div class="col-md-6"><label class="form-label">Pincode</label><input class="form-control" name="pincode" value="<?= esc(old('pincode', $user['pincode'])) ?>" required></div>
          <div class="col-12"><label class="form-label">Address</label><textarea class="form-control" name="address" rows="2" required><?= esc(old('address', $user['address'])) ?></textarea></div>
          <div class="col-md-6"><label class="form-label">City</label><input class="form-control" name="city" value="<?= esc(old('city', $user['city'])) ?>" required></div>
        </div>
        <h5 class="mt-4 mb-3">Payment</h5>
        <div class="border rounded-3 p-3 d-flex align-items-center gap-3"><i class="bi bi-credit-card-2-front fs-3 text-success"></i><div><strong>Online payment (demo gateway)</strong><div class="small text-muted-2">You will pick success or failure on the next screen. No real money is charged.</div></div></div>
      </div>
      <div class="col-lg-5">
        <div class="summary-card">
          <h5 class="mb-3">Your order</h5>
          <?php foreach ($items as $i): ?>
            <div class="d-flex align-items-center gap-3 mb-3"><img class="cart-img" style="width:56px;height:56px" src="<?= img_url($i['image']) ?>" alt=""><div class="flex-grow-1"><div class="fw-bold"><?= esc($i['name']) ?></div><div class="small text-muted-2">Qty <?= $i['qty'] ?></div></div><div class="fw-bold"><?= money($i['line_total']) ?></div></div>
          <?php endforeach; ?>
          <hr>
          <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><span><?= money($totals['subtotal']) ?></span></div>
          <div class="d-flex justify-content-between mb-2"><span>Shipping</span><span><?= $totals['shipping'] ? money($totals['shipping']) : 'Free' ?></span></div>
          <hr><div class="d-flex justify-content-between fs-5 fw-bold mb-3"><span>Total</span><span><?= money($totals['total']) ?></span></div>
          <button class="btn btn-accent btn-lg w-100">Continue to payment</button>
        </div>
      </div>
    </form>
  </div>
</section>
<?= $this->endSection() ?>
