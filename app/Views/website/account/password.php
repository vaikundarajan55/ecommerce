<?= $this->extend('website/layout') ?>
<?= $this->section('content') ?>
<div class="page-head"><div class="container"><h1 class="fw-bold mb-0">Change password</h1></div></div>
<section class="section pt-4"><div class="container"><div class="row g-4">
  <div class="col-lg-3"><?= $this->include('website/account/nav') ?></div>
  <div class="col-lg-9">
    <form action="<?= base_url('account/change-password') ?>" method="post" class="order-card form-card">
      <?= csrf_field() ?>
      <div class="order-head">
        <div><h2 class="metrics-title">Update password</h2><p class="metrics-sub mb-0">Use at least 6 characters for your new password.</p></div>
        <span class="form-badge"><i class="bi bi-shield-lock"></i></span>
      </div>

      <div class="form-group-card" style="--i: 0">
        <h3 class="form-group-title"><i class="bi bi-key-fill"></i>Password</h3>
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label" for="pwCur">Current password</label>
            <div class="pf-field"><i class="bi bi-lock"></i><input id="pwCur" type="password" class="form-control" name="current_password" autocomplete="current-password" required><button type="button" class="pf-eye" data-toggle-pass="#pwCur" aria-label="Show password"><i class="bi bi-eye"></i></button></div></div>
          <div class="w-100 m-0"></div>
          <div class="col-md-6"><label class="form-label" for="pwNew">New password</label>
            <div class="pf-field"><i class="bi bi-shield-lock"></i><input id="pwNew" type="password" class="form-control" name="new_password" minlength="6" autocomplete="new-password" required><button type="button" class="pf-eye" data-toggle-pass="#pwNew" aria-label="Show password"><i class="bi bi-eye"></i></button></div></div>
          <div class="col-md-6"><label class="form-label" for="pwConf">Confirm new password</label>
            <div class="pf-field"><i class="bi bi-shield-check"></i><input id="pwConf" type="password" class="form-control" name="confirm_password" autocomplete="new-password" required><button type="button" class="pf-eye" data-toggle-pass="#pwConf" aria-label="Show password"><i class="bi bi-eye"></i></button></div></div>
        </div>
      </div>

      <div class="form-foot">
        <button class="btn-grad-save"><i class="bi bi-arrow-repeat"></i>Update password</button>
      </div>
    </form>
  </div>
</div></div></section>
<?= $this->endSection() ?>
