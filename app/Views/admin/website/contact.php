<?= $this->extend('admin/layout') ?>
<?= $this->section('hero_actions') ?>
<a href="<?= base_url('contact') ?>" target="_blank" class="btn-back"><i class="bi bi-box-arrow-up-right"></i>View page</a>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php $v = static fn (string $k) => esc(old($k, $s[$k] ?? '')); ?>
<form action="<?= base_url('admin/website/contact') ?>" method="post" class="panel list-card" style="max-width:900px">
  <?= csrf_field() ?>
  <div class="list-head"><div><h2 class="list-title">Store contact details</h2><p class="list-sub">Shown on the Contact us page, in the top strip and in the footer</p></div></div>
  <div class="row g-3 pb-3">
    <div class="col-12"><label class="form-label" for="tagline">Contact page tagline</label><input id="tagline" class="form-control" name="contact_tagline" maxlength="200" value="<?= $v('contact_tagline') ?>"></div>
    <div class="col-12"><label class="form-label" for="addr">Address</label><input id="addr" class="form-control" name="contact_address" maxlength="255" value="<?= $v('contact_address') ?>" required></div>
    <div class="col-md-6"><label class="form-label" for="phone">Phone</label><input id="phone" class="form-control" name="contact_phone" maxlength="30" value="<?= $v('contact_phone') ?>" required></div>
    <div class="col-md-6"><label class="form-label" for="email">Email</label><input id="email" type="email" class="form-control" name="contact_email" value="<?= $v('contact_email') ?>" required></div>
    <div class="col-md-6"><label class="form-label" for="hours">Opening hours</label><input id="hours" class="form-control" name="contact_hours" maxlength="100" placeholder="Mon–Sat, 9am – 7pm" value="<?= $v('contact_hours') ?>"></div>
    <div class="col-12"><label class="form-label" for="map">Google Maps embed link <span class="text-muted fw-normal">(optional)</span></label><input id="map" class="form-control" name="contact_map" placeholder="https://www.google.com/maps/embed?pb=..." value="<?= $v('contact_map') ?>">
      <div class="form-text">In Google Maps: Share → Embed a map → copy only the link inside <code>src="…"</code>. Leave empty to hide the map.</div></div>
    <div class="col-12"><button class="btn btn-grad"><i class="bi bi-check2-circle me-2"></i>Save contact details</button></div>
  </div>
</form>
<?= $this->endSection() ?>
