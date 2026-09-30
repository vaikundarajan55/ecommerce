<?= $this->extend('website/layout') ?>
<?= $this->section('content') ?>
<div class="page-head"><div class="container"><h1 class="fw-bold mb-0">My profile</h1></div></div>
<section class="section pt-4"><div class="container"><div class="row g-4">
  <div class="col-lg-3"><?= $this->include('website/account/nav') ?></div>
  <div class="col-lg-9"><form action="<?= base_url('account/profile') ?>" method="post" class="row g-3" style="max-width:720px">
    <?= csrf_field() ?>
    <div class="col-md-6"><label class="form-label">Full name</label><input class="form-control" name="name" value="<?= esc(old('name', $user['name'])) ?>" required></div>
    <div class="col-md-6"><label class="form-label">Email</label><input type="email" class="form-control" name="email" value="<?= esc(old('email', $user['email'])) ?>" required></div>
    <div class="col-md-6"><label class="form-label">Phone</label><input class="form-control" name="phone" value="<?= esc(old('phone', $user['phone'])) ?>"></div>
    <div class="col-md-6"><label class="form-label">Pincode</label><input class="form-control" name="pincode" value="<?= esc(old('pincode', $user['pincode'])) ?>"></div>
    <div class="col-12"><label class="form-label">Address</label><textarea class="form-control" name="address" rows="2"><?= esc(old('address', $user['address'])) ?></textarea></div>
    <div class="col-md-6"><label class="form-label">City</label><input class="form-control" name="city" value="<?= esc(old('city', $user['city'])) ?>"></div>
    <div class="col-12"><button class="btn btn-brand">Save changes</button></div>
  </form></div>
</div></div></section>
<?= $this->endSection() ?>
