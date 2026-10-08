<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin login | <?= site_name() ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@800&family=Nunito+Sans:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="<?= base_url('assets/admin/css/admin.css') ?>" rel="stylesheet">
</head>
<body>
<div class="login-page">
  <div class="login-bg" aria-hidden="true"></div>
  <!-- Vegetables drifting up the background -->
  <div class="veg-float" aria-hidden="true">
    <span style="--x:6%;  --s:2.4rem; --t:16s; --d:0s">🥕</span>
    <span style="--x:18%; --s:1.8rem; --t:20s; --d:-6s">🥦</span>
    <span style="--x:30%; --s:2.2rem; --t:18s; --d:-11s">🍅</span>
    <span style="--x:44%; --s:1.6rem; --t:22s; --d:-3s">🌽</span>
    <span style="--x:58%; --s:2.6rem; --t:17s; --d:-9s">🥬</span>
    <span style="--x:70%; --s:1.9rem; --t:21s; --d:-14s">🫑</span>
    <span style="--x:82%; --s:2.3rem; --t:19s; --d:-5s">🧅</span>
    <span style="--x:92%; --s:1.7rem; --t:23s; --d:-12s">🥒</span>
  </div>

  <div class="login-card">
    <div class="login-logo"><i class="bi bi-shield-lock-fill"></i></div>
    <h2 class="text-center fw-bold mb-1 login-title">Admin login</h2>
    <p class="text-center text-muted mb-4 stagger" style="--d:.35s">Sign in to manage <?= site_name() ?></p>
    <?= flash_alerts() ?>
    <form action="<?= base_url('admin/login') ?>" method="post">
      <?= csrf_field() ?>
      <div class="mb-3 stagger" style="--d:.45s"><label class="form-label" for="email">Email</label>
        <div class="input-group glow-field"><span class="input-group-text"><i class="bi bi-envelope"></i></span><input type="email" class="form-control" id="email" name="email" value="<?= esc(old('email')) ?>" required autofocus></div></div>
      <div class="mb-4 stagger" style="--d:.55s"><label class="form-label" for="password">Password</label>
        <div class="input-group glow-field"><span class="input-group-text"><i class="bi bi-lock"></i></span><input type="password" class="form-control" id="password" name="password" required>
          <button class="btn btn-outline-secondary" type="button" data-toggle-pass="#password" aria-label="Show password"><i class="bi bi-eye"></i></button></div></div>
      <button class="btn btn-lg w-100 btn-radiant stagger" style="--d:.65s">Login <i class="bi bi-arrow-right ms-1"></i></button>
    </form>
    <div class="text-center small text-muted mt-3 stagger" style="--d:.75s">Demo: admin@example.com / admin123</div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/admin/js/admin.js') ?>"></script>
</body>
</html>
