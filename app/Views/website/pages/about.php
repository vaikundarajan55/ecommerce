<?= $this->extend('website/layout') ?>
<?= $this->section('content') ?>
<?php
  // All text on this page is edited in Admin > Website > About us page
  $paras    = array_filter(array_map('trim', preg_split('/\R\s*\R/', setting('about_body'))));
  $stats    = setting_list('about_stats');
  $promises = setting_list('about_promises');
  $image    = setting('about_image');
  $icons    = ['bi-patch-check', 'bi-box-seam', 'bi-chat-dots'];
?>
<div class="page-head"><div class="container"><h1 class="fw-bold mb-1">About us</h1><?php if (setting('about_tagline')): ?><p class="text-muted-2 mb-0"><?= esc(setting('about_tagline')) ?></p><?php endif; ?></div></div>
<section class="section">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-6" data-aos="fade-right">
        <h2 class="section-title"><?= esc(setting('about_heading', 'About ' . site_name())) ?></h2>
        <?php foreach ($paras as $p): ?><p><?= nl2br(esc($p)) ?></p><?php endforeach; ?>
        <a href="<?= base_url('shop') ?>" class="btn btn-brand">Browse products</a>
      </div>
      <div class="col-lg-6" data-aos="fade-left">
        <?php if ($image && is_file(FCPATH . 'uploads/pages/' . $image)): ?>
          <div class="about-img mb-3"><img src="<?= img_url($image, 'pages') ?>" alt=""></div>
        <?php endif; ?>
        <?php if ($stats): ?>
          <div class="row g-3 text-center">
            <?php foreach ($stats as $s): ?>
              <div class="col-6"><div class="stat-tile"><div class="num"><?= esc($s['value']) ?></div><div class="text-muted-2"><?= esc($s['label']) ?></div></div></div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<?php if ($promises): ?>
<section class="section section-alt">
  <div class="container">
    <h2 class="section-title mb-4">What we promise</h2>
    <div class="row g-4">
      <?php foreach ($promises as $n => $f): ?>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="<?= $n * 100 ?>"><div class="trust"><span class="ico"><i class="bi <?= $icons[$n % count($icons)] ?>"></i></span><div><strong><?= esc($f['title']) ?></strong><div class="text-muted-2"><?= esc($f['text']) ?></div></div></div></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
<?= view('website/partials/testimonials', ['testimonials' => $testimonials, 'alt' => ! $promises]) ?>
<?= $this->endSection() ?>
