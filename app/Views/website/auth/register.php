<?= $this->extend('website/layout') ?>
<?= $this->section('content') ?>
<div class="auth-wrap"><div class="auth-card" style="width:min(520px,92vw)">
  <h2 class="fw-bold mb-1">Create account</h2><p class="text-muted-2 mb-4">Track orders and check out faster.</p>
  <form action="<?= base_url('register') ?>" method="post" class="row g-3">
    <?= csrf_field() ?>
    <div class="col-12"><label class="form-label">Full name</label><input class="form-control" name="name" value="<?= esc(old('name')) ?>" required></div>
    <div class="col-md-6"><label class="form-label">Email</label><input type="email" class="form-control" name="email" value="<?= esc(old('email')) ?>" required></div>
    <div class="col-md-6"><label class="form-label">Phone</label><input class="form-control" name="phone" value="<?= esc(old('phone')) ?>" required></div>
    <div class="col-md-6"><label class="form-label">Password</label><input type="password" class="form-control" name="password" minlength="6" required></div>
    <div class="col-md-6"><label class="form-label">Confirm password</label><input type="password" class="form-control" name="confirm_password" required></div>
    <div class="col-12"><button class="btn btn-brand btn-lg w-100">Create account</button></div>
  </form>
  <p class="text-center mt-4 mb-0">Already registered? <a href="<?= base_url('login') ?>" class="fw-bold">Login</a></p>
</div></div>
<?= $this->endSection() ?>
