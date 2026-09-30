<?= $this->extend('website/layout') ?>
<?= $this->section('content') ?>
<div class="page-head"><div class="container"><h1 class="fw-bold mb-1">About us</h1><p class="text-muted-2 mb-0">A small team selling things we would use ourselves.</p></div></div>
<section class="section">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-6" data-aos="fade-right">
        <h2 class="section-title">Started in Puducherry, shipping across India</h2>
        <p>We began as a two-person shop selling home and kitchen items to neighbours. Today we ship electronics, fashion, home and beauty products to customers across the country.</p>
        <p>Every product listed here is checked by our team before it goes live. If something is not right, you can return it within 7 days.</p>
        <a href="<?= base_url('shop') ?>" class="btn btn-brand">Browse products</a>
      </div>
      <div class="col-lg-6" data-aos="fade-left">
        <div class="row g-3 text-center">
          <?php foreach ([['10k+', 'Orders delivered'], ['500+', 'Products'], ['4.7/5', 'Average rating'], ['48 hrs', 'Typical dispatch time']] as $s): ?>
            <div class="col-6"><div class="stat-tile"><div class="num text-success"><?= $s[0] ?></div><div class="text-muted-2"><?= $s[1] ?></div></div></div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="section section-alt">
  <div class="container">
    <h2 class="section-title mb-4">What we promise</h2>
    <div class="row g-4">
      <?php foreach ([['bi-patch-check', 'Genuine products', 'Sourced from brands and verified suppliers.'], ['bi-box-seam', 'Careful packing', 'Fragile items are double-wrapped.'], ['bi-chat-dots', 'Human support', 'Write to us and a real person answers.']] as $n => $f): ?>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="<?= $n * 100 ?>"><div class="trust"><span class="ico"><i class="bi <?= $f[0] ?>"></i></span><div><strong><?= $f[1] ?></strong><div class="text-muted-2"><?= $f[2] ?></div></div></div></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?= $this->endSection() ?>
