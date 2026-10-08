<?= $this->extend('website/layout') ?>
<?= $this->section('content') ?>
<div class="page-head"><div class="container"><h1 class="fw-bold mb-0">My orders</h1></div></div>
<section class="section pt-4"><div class="container"><div class="row g-4">
  <div class="col-lg-3"><?= $this->include('website/account/nav') ?></div>
  <div class="col-lg-9">
    <?php if ($orders): ?>
    <section class="order-card">
      <div class="order-head">
        <div><h2 class="metrics-title">Order history</h2><p class="metrics-sub mb-0">Every order you have placed, with payment status and delivery progress</p></div>
      </div>
      <?= view('website/account/orders_table', ['orders' => $orders]) ?>
      <div class="order-foot">
        <form method="get" class="d-flex align-items-center gap-2">
          <label for="perPage" class="mb-0">Rows per page:</label>
          <select id="perPage" name="per_page" class="rows-select" onchange="this.form.submit()">
            <?php foreach ($sizes as $s): ?><option value="<?= $s ?>" <?= $perPage === $s ? 'selected' : '' ?>><?= $s ?></option><?php endforeach; ?>
          </select>
        </form>
        <span><?= $from ?>–<?= $to ?> of <?= $total ?></span>
        <div class="d-flex gap-1">
          <?php $prev = $pager->getPreviousPageURI(); $next = $pager->getNextPageURI(); ?>
          <a class="page-arrow <?= $prev ? '' : 'disabled' ?>" href="<?= $prev ?? '#' ?>" aria-label="Previous page"><i class="bi bi-chevron-left"></i></a>
          <a class="page-arrow <?= $next ? '' : 'disabled' ?>" href="<?= $next ?? '#' ?>" aria-label="Next page"><i class="bi bi-chevron-right"></i></a>
        </div>
      </div>
    </section>
    <?php else: ?><div class="text-center py-5"><i class="bi bi-box fs-1 text-muted-2"></i><h5 class="mt-2">No orders yet</h5><a href="<?= base_url('shop') ?>" class="btn btn-brand">Browse products</a></div><?php endif; ?>
  </div>
</div></div></section>
<?= $this->endSection() ?>
