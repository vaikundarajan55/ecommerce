<?= $this->extend('website/layout') ?>
<?= $this->section('content') ?>
<div class="auth-wrap"><div class="auth-card">
  <h2 class="fw-bold mb-1">Login</h2><p class="text-muted-2 mb-4">Welcome back. Enter your details to continue.</p>
  <form action="<?= base_url('login') ?>" method="post">
    <?= csrf_field() ?>
    <div class="mb-3"><label class="form-label" for="email">Email</label><input type="email" class="form-control form-control-lg" id="email" name="email" value="<?= esc(old('email')) ?>" required autofocus></div>
    <div class="mb-2"><label class="form-label" for="password">Password</label><input type="password" class="form-control form-control-lg" id="password" name="password" required></div>
    <div class="text-end mb-4"><a href="<?= base_url('forgot-password') ?>" class="small">Forgot password?</a></div>
    <button class="btn btn-brand btn-lg w-100">Login</button>
  </form>
  <p class="text-center mt-4 mb-0">New here? <a href="<?= base_url('register') ?>" class="fw-bold">Create an account</a></p>
  <div class="alert alert-light border small mt-3 mb-0">Demo customer: user@example.com / user123</div>
</div></div>
<?= $this->endSection() ?>
