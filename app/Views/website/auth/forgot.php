<?= $this->extend('website/layout') ?>
<?= $this->section('content') ?>
<div class="auth-wrap"><div class="auth-card">
  <h2 class="fw-bold mb-1">Forgot password</h2><p class="text-muted-2 mb-4">Enter your email and we will create a reset link.</p>
  <form action="<?= base_url('forgot-password') ?>" method="post">
    <?= csrf_field() ?>
    <div class="mb-4"><label class="form-label">Email</label><input type="email" class="form-control form-control-lg" name="email" value="<?= esc(old('email')) ?>" required autofocus></div>
    <button class="btn btn-brand btn-lg w-100">Get reset link</button>
  </form>
  <p class="text-center mt-4 mb-0"><a href="<?= base_url('login') ?>">Back to login</a></p>
</div></div>
<?= $this->endSection() ?>
