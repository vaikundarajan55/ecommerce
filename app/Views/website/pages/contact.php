<?= $this->extend('website/layout') ?>
<?= $this->section('content') ?>
<div class="page-head"><div class="container"><h1 class="fw-bold mb-1">Contact us</h1><p class="text-muted-2 mb-0">Send us a message and we will reply within one working day.</p></div></div>
<section class="section">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-7" data-aos="fade-up">
        <form action="<?= base_url('contact') ?>" method="post" class="row g-3">
          <?= csrf_field() ?>
          <div class="col-md-6"><label class="form-label" for="name">Your name</label><input class="form-control" id="name" name="name" value="<?= esc(old('name')) ?>" required></div>
          <div class="col-md-6"><label class="form-label" for="email">Email</label><input type="email" class="form-control" id="email" name="email" value="<?= esc(old('email')) ?>" required></div>
          <div class="col-12"><label class="form-label" for="subject">Subject</label><input class="form-control" id="subject" name="subject" value="<?= esc(old('subject')) ?>"></div>
          <div class="col-12"><label class="form-label" for="message">Message</label><textarea class="form-control" id="message" name="message" rows="5" required><?= esc(old('message')) ?></textarea></div>
          <div class="col-12"><button class="btn btn-brand btn-lg px-4">Send message</button></div>
        </form>
      </div>
      <div class="col-lg-5" data-aos="fade-up" data-aos-delay="100">
        <div class="summary-card">
          <h5 class="mb-3">Store details</h5>
          <p><i class="bi bi-geo-alt text-success me-2"></i>12 Beach Road, Puducherry 605001</p>
          <p><i class="bi bi-telephone text-success me-2"></i>+91 98765 43210</p>
          <p><i class="bi bi-envelope text-success me-2"></i>support@shopkart.test</p>
          <p class="mb-0"><i class="bi bi-clock text-success me-2"></i>Mon–Sat, 9am – 7pm</p>
        </div>
      </div>
    </div>
  </div>
</section>
<?= $this->endSection() ?>
