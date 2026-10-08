<?= $this->extend('website/layout') ?>
<?= $this->section('content') ?>
<?php $off = discount_pct($p); $inStock = $p['stock'] > 0; ?>
<div class="page-head py-3"><div class="container">
  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
    <li class="breadcrumb-item"><a href="<?= base_url('shop') ?>">Shop</a></li>
    <li class="breadcrumb-item"><a href="<?= base_url('shop?cat=' . $p['category_slug']) ?>"><?= esc($p['category_name']) ?></a></li>
    <li class="breadcrumb-item active"><?= esc($p['name']) ?></li>
  </ol></nav>
</div></div>

<section class="section pt-4">
  <div class="container">
    <div class="row g-5">
      <div class="col-md-6" data-aos="fade-right">
        <div class="gallery-main position-relative">
          <?php if ($off): ?><span class="off-badge">-<?= $off ?>%</span><?php endif; ?>
          <img id="galleryMain" src="<?= img_url($images[0] ?? null) ?>" alt="<?= esc($p['name']) ?>">
        </div>
        <?php if (count($images) > 1): ?>
          <div class="gallery-thumbs" role="list">
            <?php foreach ($images as $i => $img): ?>
              <button type="button" class="gallery-thumb <?= $i === 0 ? 'active' : '' ?>" data-src="<?= img_url($img) ?>" aria-label="Show image <?= $i + 1 ?>"><img src="<?= img_url($img) ?>" alt="" loading="lazy"></button>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
      <div class="col-md-6" data-aos="fade-left">
        <div class="text-muted-2 mb-1"><?= esc($p['category_name']) ?><?= $p['sku'] ? ' &middot; SKU ' . esc($p['sku']) : '' ?></div>
        <h1 class="fw-bold"><?= esc($p['name']) ?></h1>
        <p class="lead text-muted-2"><?= esc($p['short_desc']) ?></p>
        <div class="mb-3"><span class="price fs-2"><?= money(current_price($p)) ?></span><?php if ($off): ?><span class="price-old fs-5"><?= money($p['price']) ?></span> <span class="badge text-bg-danger ms-1"><?= $off ?>% off</span><?php endif; ?></div>
        <p><?= $inStock ? '<span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i>In stock</span>' . ($p['stock'] < 6 ? ' <span class="text-danger small">(only ' . (int) $p['stock'] . ' left)</span>' : '') : '<span class="text-danger fw-bold">Out of stock</span>' ?></p>

        <form action="<?= base_url('cart/add/' . $p['id']) ?>" method="post" class="d-flex flex-wrap gap-2 align-items-center my-4">
          <?= csrf_field() ?>
          <div class="qty-box"><button type="button" data-step="-" aria-label="Decrease">−</button><input type="number" name="qty" value="1" min="1" max="<?= max(1, (int) $p['stock']) ?>" aria-label="Quantity"><button type="button" data-step="+" aria-label="Increase">+</button></div>
          <button class="btn btn-brand btn-lg add-btn" <?= $inStock ? '' : 'disabled' ?>><i class="bi bi-cart-plus me-1"></i> Add to cart</button>
          <button class="btn btn-accent btn-lg" name="buy_now" value="1" <?= $inStock ? '' : 'disabled' ?>>Buy now</button>
        </form>

        <ul class="list-unstyled small text-muted-2">
          <li class="mb-1"><i class="bi bi-truck me-2"></i>Free delivery above ₹999, otherwise ₹49</li>
          <li><i class="bi bi-arrow-repeat me-2"></i>7-day easy returns</li>
        </ul>
      </div>
    </div>

    <ul class="nav nav-tabs mt-5" role="tablist">
      <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-desc" type="button">Description</button></li>
      <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-enq" type="button" id="enquiry">Product enquiry</button></li>
    </ul>
    <div class="tab-content border border-top-0 rounded-bottom p-4">
      <div class="tab-pane fade show active" id="tab-desc"><?= nl2br(esc($p['description'] ?: $p['short_desc'])) ?></div>
      <div class="tab-pane fade" id="tab-enq">
        <form action="<?= base_url('enquiry/' . $p['id']) ?>" method="post" class="row g-3" style="max-width:720px">
          <?= csrf_field() ?>
          <div class="col-md-6"><label class="form-label">Name</label><input class="form-control" name="name" value="<?= esc(old('name', session('user_name'))) ?>" required></div>
          <div class="col-md-6"><label class="form-label">Email</label><input type="email" class="form-control" name="email" value="<?= esc(old('email')) ?>" required></div>
          <div class="col-md-6"><label class="form-label">Phone (optional)</label><input class="form-control" name="phone" value="<?= esc(old('phone')) ?>"></div>
          <div class="col-12"><label class="form-label">Your question</label><textarea class="form-control" name="message" rows="3" required><?= esc(old('message')) ?></textarea></div>
          <div class="col-12"><button class="btn btn-outline-brand">Send enquiry</button></div>
        </form>
      </div>
    </div>

    <?php if ($related): ?>
      <h3 class="fw-bold mt-5 mb-4">You may also like</h3>
      <div class="row g-3 g-lg-4">
        <?php foreach ($related as $r): ?><div class="col-6 col-lg-3"><?= product_card($r) ?></div><?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
<?= $this->endSection() ?>
<?php if (session()->getFlashdata('errors') || str_contains((string) session()->getFlashdata('success'), 'Enquiry')): ?>
<?= $this->section('scripts') ?><script>new bootstrap.Tab(document.getElementById('enquiry')).show();</script><?= $this->endSection() ?>
<?php endif; ?>
