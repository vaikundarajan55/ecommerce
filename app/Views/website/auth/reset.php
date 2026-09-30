<?= $this->extend('website/layout') ?>
<?= $this->section('content') ?>
<div class="auth-wrap"><div class="auth-card">
  <h2 class="fw-bold mb-1">Set a new password</h2><p class="text-muted-2 mb-4">Choose a password with at least 6 characters.</p>
  <form action="<?= base_url('reset-password/' . $token) ?>" method="post">
    <?= csrf_field() ?>
    <div class="mb-3"><label class="form-label">New password</label><input type="password" class="form-control form-control-lg" name="password" minlength="6" required></div>
    <div class="mb-4"><label class="form-label">Confirm password</label><input type="password" class="form-control form-control-lg" name="confirm_password" required></div>
    <button class="btn btn-brand btn-lg w-100">Update password</button>
  </form>
</div></div>
<?= $this->endSection() ?>
