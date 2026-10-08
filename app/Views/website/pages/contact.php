<?= $this->extend('website/layout') ?>
<?= $this->section('content') ?>
<?php
  // Store details are edited in Admin > Website > Contact details
  $details = [
      ['bi-geo-alt', 'Address', setting('contact_address'), null],
      ['bi-telephone', 'Phone', setting('contact_phone'), setting('contact_phone') ? tel_link(setting('contact_phone')) : null],
      ['bi-envelope', 'Email', setting('contact_email'), setting('contact_email') ? 'mailto:' . setting('contact_email') : null],
      ['bi-clock', 'Opening hours', setting('contact_hours'), null],
  ];
  $map = setting('contact_map');
?>
<div class="page-head"><div class="container"><h1 class="fw-bold mb-1">Contact us</h1><?php if (setting('contact_tagline')): ?><p class="text-muted-2 mb-0"><?= esc(setting('contact_tagline')) ?></p><?php endif; ?></div></div>
<section class="section">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-7" data-aos="fade-up">
        <form action="<?= base_url('contact') ?>" method="post" class="order-card form-card">
          <?= csrf_field() ?>
          <div class="order-head"><div><h2 class="metrics-title">Send us a message</h2><p class="metrics-sub mb-0">Fill in the form and our team will get back to you.</p></div><span class="form-badge"><i class="bi bi-send"></i></span></div>
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label" for="name">Your name</label><div class="pf-field"><i class="bi bi-person"></i><input class="form-control" id="name" name="name" value="<?= esc(old('name', (string) session('user_name'))) ?>" required></div></div>
            <div class="col-md-6"><label class="form-label" for="email">Email</label><div class="pf-field"><i class="bi bi-envelope"></i><input type="email" class="form-control" id="email" name="email" value="<?= esc(old('email')) ?>" required></div></div>
            <div class="col-md-6"><label class="form-label" for="phone">Phone <span class="text-muted-2 fw-normal">(optional)</span></label><div class="pf-field"><i class="bi bi-telephone"></i><input class="form-control" id="phone" name="phone" inputmode="tel" maxlength="20" value="<?= esc(old('phone')) ?>"></div></div>
            <div class="col-md-6"><label class="form-label" for="subject">Subject</label><div class="pf-field"><i class="bi bi-chat-left-text"></i><input class="form-control" id="subject" name="subject" maxlength="200" value="<?= esc(old('subject')) ?>"></div></div>
            <div class="col-12"><label class="form-label" for="message">Message</label><div class="pf-field pf-area"><i class="bi bi-pencil"></i><textarea class="form-control" id="message" name="message" rows="5" required><?= esc(old('message')) ?></textarea></div></div>
            <div class="col-12 form-foot"><button class="btn-grad-save"><i class="bi bi-send"></i>Send message</button></div>
          </div>
        </form>
      </div>
      <div class="col-lg-5" data-aos="fade-up" data-aos-delay="100">
        <div class="summary-card">
          <h5 class="mb-3">Store details</h5>
          <ul class="contact-list">
            <?php foreach ($details as [$icon, $label, $value, $href]): if ($value === '') continue; ?>
              <li><span class="contact-ico"><i class="bi <?= $icon ?>"></i></span><div><small><?= $label ?></small><?php if ($href): ?><a href="<?= esc($href) ?>"><?= esc($value) ?></a><?php else: ?><span><?= esc($value) ?></span><?php endif; ?></div></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php if ($map): ?>
          <div class="map-card mt-4"><iframe src="<?= esc($map) ?>" title="Store location map" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe></div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<?= $this->endSection() ?>
