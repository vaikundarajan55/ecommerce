<?= $this->extend('website/layout') ?>
<?= $this->section('content') ?>
<div class="page-head"><div class="container"><h1 class="fw-bold mb-0">Change password</h1></div></div>
<section class="section pt-4"><div class="container"><div class="row g-4">
  <div class="col-lg-3"><?= $this->include('website/account/nav') ?></div>
  <div class="col-lg-9"><form action="<?= base_url('account/change-password') ?>" method="post" style="max-width:460px">
    <?= csrf_field() ?>
    <div class="mb-3"><label class="form-label">Current password</label><input type="password" class="form-control" name="current_password" required></div>
    <div class="mb-3"><label class="form-label">New password</label><input type="password" class="form-control" name="new_password" minlength="6" required></div>
    <div class="mb-4"><label class="form-label">Confirm new password</label><input type="password" class="form-control" name="confirm_password" required></div>
    <button class="btn btn-brand">Update password</button>
  </form></div>
</div></div></section>
<?= $this->endSection() ?>
