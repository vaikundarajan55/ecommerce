<?= $this->extend('website/layout') ?>
<?= $this->section('content') ?>

<!-- Hero banners (managed from Admin > Banners) -->
<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6000">
  <div class="carousel-inner">
    <?php if ($banners): foreach ($banners as $i => $b): $has = $b['image'] && is_file(FCPATH . 'uploads/banners/' . $b['image']); ?>
      <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
        <div class="hero-slide <?= $has ? '' : 'no-img' ?>" <?= $has ? 'style="background-image:url(' . img_url($b['image'], 'banners') . ')"' : '' ?>>
          <?php if (! $has): ?><span class="hero-shape s1"></span><span class="hero-shape s2"></span><?php endif; ?>
          <div class="container">
            <h1 class="hero-title mb-3"><?= esc($b['title']) ?></h1>
            <p class="hero-sub mb-4"><?= esc($b['subtitle']) ?></p>
            <a class="btn btn-accent btn-lg hero-cta" href="<?= base_url(ltrim($b['link'] ?: 'shop', '/')) ?>">Shop now</a>
          </div>
        </div>
      </div>
    <?php endforeach; else: ?>
      <div class="carousel-item active"><div class="hero-slide no-img"><span class="hero-shape s1"></span><span class="hero-shape s2"></span>
        <div class="container"><h1 class="hero-title mb-3">Welcome to <?= site_name() ?></h1><a class="btn btn-accent btn-lg hero-cta" href="<?= base_url('shop') ?>">Shop now</a></div></div></div>
    <?php endif; ?>
  </div>
  <?php if (count($banners) > 1): ?>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span><span class="visually-hidden">Previous</span></button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span><span class="visually-hidden">Next</span></button>
  <?php endif; ?>
</div>

<!-- Trust row -->
<section class="py-4 border-bottom">
  <div class="container"><div class="row g-3">
    <?php foreach ([['bi-truck', 'Free delivery', 'On orders above ₹999'], ['bi-arrow-repeat', 'Easy returns', '7-day return window'], ['bi-shield-check', 'Secure payment', 'Protected checkout'], ['bi-headset', 'Real support', 'Mon–Sat, 9am–7pm']] as $t): ?>
      <div class="col-6 col-lg-3"><div class="trust"><span class="ico"><i class="bi <?= $t[0] ?>"></i></span><div><strong><?= $t[1] ?></strong><div class="small text-muted-2"><?= $t[2] ?></div></div></div></div>
    <?php endforeach; ?>
  </div></div>
</section>

<!-- Categories -->
<section class="section">
  <div class="container">
    <h2 class="section-title mb-4" data-aos="fade-up">Shop by category</h2>
    <div class="row g-3">
      <?php foreach ($categories as $n => $c): ?>
        <div class="col-6 col-md-3" data-aos="zoom-in" data-aos-delay="<?= $n * 70 ?>">
          <a class="cat-tile" href="<?= base_url('shop?cat=' . $c['slug']) ?>">
            <div class="cat-img"><?php if ($c['image']): ?><img src="<?= img_url($c['image'], 'categories') ?>" alt=""><?php else: ?><i class="bi bi-grid"></i><?php endif; ?></div>
            <strong><?= esc($c['name']) ?></strong>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Product sections, tagged in Admin > Products (Featured / Current / Peak) -->
<?= view('website/partials/product_section', ['title' => 'Featured products', 'eyebrow' => 'Hand-picked', 'icon' => 'bi-star-fill', 'tone' => 'eb-amber', 'products' => $featured, 'link' => base_url('shop?tag=featured'), 'alt' => true]) ?>
<?= view('website/partials/product_section', ['title' => 'Trending now', 'eyebrow' => 'Current picks', 'icon' => 'bi-lightning-charge-fill', 'products' => $current, 'link' => base_url('shop?tag=current')]) ?>

<!-- Promo -->
<section class="section">
  <div class="container">
    <div class="promo-band p-4 p-md-5" data-aos="fade-up">
      <span class="hero-shape s1"></span>
      <div class="row align-items-center position-relative">
        <div class="col-md-8"><h2 class="fw-bold">Have a question about a product?</h2><p class="mb-0 opacity-75">Every product page has an enquiry form. Send it and we reply within one working day.</p></div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0"><a href="<?= base_url('contact') ?>" class="btn btn-accent btn-lg">Contact us</a></div>
      </div>
    </div>
  </div>
</section>

<?= view('website/partials/product_section', ['title' => 'Best sellers', 'eyebrow' => 'Peak products', 'icon' => 'bi-fire', 'tone' => 'eb-red', 'products' => $peak, 'link' => base_url('shop?tag=peak'), 'alt' => true]) ?>

<!-- Latest -->
<section class="section">
  <div class="container">
    <h2 class="section-title mb-4" data-aos="fade-up">Just arrived</h2>
    <div class="row g-3 g-lg-4">
      <?php foreach ($latest as $n => $p): ?>
        <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?= $n * 80 ?>"><?= product_card($p) ?></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?= view('website/partials/testimonials', ['testimonials' => $testimonials, 'alt' => true]) ?>
<?= $this->endSection() ?>
