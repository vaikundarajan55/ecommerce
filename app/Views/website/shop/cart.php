<?= $this->extend('website/layout') ?>
<?= $this->section('content') ?>
<div class="page-head"><div class="container"><h1 class="fw-bold mb-0">Your cart</h1></div></div>
<section class="section pt-4">
  <div class="container">
  <?php if (! $items): ?>
    <div class="text-center py-5"><i class="bi bi-cart-x fs-1 text-muted-2"></i><h4 class="mt-3">Your cart is empty</h4><p class="text-muted-2">Add something you like and it will show up here.</p><a href="<?= base_url('shop') ?>" class="btn btn-brand btn-lg">Start shopping</a></div>
  <?php else: ?>
    <div class="row g-4">
      <div class="col-lg-8">
        <form action="<?= base_url('cart/update') ?>" method="post">
          <?= csrf_field() ?>
          <div class="table-responsive">
            <table class="table align-middle">
              <thead><tr><th>Product</th><th>Price</th><th style="width:130px">Quantity</th><th class="text-end">Total</th><th></th></tr></thead>
              <tbody>
              <?php foreach ($items as $i): ?>
                <tr>
                  <td><div class="d-flex align-items-center gap-3"><img class="cart-img" src="<?= img_url($i['image']) ?>" alt=""><a class="fw-bold text-decoration-none text-dark" href="<?= base_url('shop/' . $i['slug']) ?>"><?= esc($i['name']) ?></a></div></td>
                  <td><?= money($i['unit']) ?></td>
                  <td><input type="number" class="form-control" name="qty[<?= $i['id'] ?>]" value="<?= $i['qty'] ?>" min="0" max="<?= (int) $i['stock'] ?>" aria-label="Quantity"></td>
                  <td class="text-end fw-bold"><?= money($i['line_total']) ?></td>
                  <td class="text-end"><a href="<?= base_url('cart/remove/' . $i['id']) ?>" class="text-danger" aria-label="Remove"><i class="bi bi-trash3"></i></a></td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <div class="d-flex justify-content-between"><a href="<?= base_url('shop') ?>" class="btn btn-outline-brand">Continue shopping</a><button class="btn btn-brand">Update cart</button></div>
        </form>
      </div>
      <div class="col-lg-4">
        <div class="summary-card">
          <h5 class="mb-3">Order summary</h5>
          <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><span><?= money($totals['subtotal']) ?></span></div>
          <div class="d-flex justify-content-between mb-2"><span>Shipping</span><span><?= $totals['shipping'] ? money($totals['shipping']) : 'Free' ?></span></div>
          <hr><div class="d-flex justify-content-between fs-5 fw-bold mb-3"><span>Total</span><span><?= money($totals['total']) ?></span></div>
          <a href="<?= base_url('checkout') ?>" class="btn btn-accent btn-lg w-100">Proceed to checkout</a>
        </div>
      </div>
    </div>
  <?php endif; ?>
  </div>
</section>
<?= $this->endSection() ?>
