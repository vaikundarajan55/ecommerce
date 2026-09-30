<div class="list-group acc-nav">
  <a class="list-group-item <?= is_active('account') && ! str_contains(uri_string(), '/') ? 'active' : '' ?>" href="<?= base_url('account') ?>"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
  <a class="list-group-item <?= str_contains(uri_string(), 'orders') || str_contains(uri_string(), 'invoice') ? 'active' : '' ?>" href="<?= base_url('account/orders') ?>"><i class="bi bi-box-seam me-2"></i>My orders</a>
  <a class="list-group-item <?= str_contains(uri_string(), 'profile') ? 'active' : '' ?>" href="<?= base_url('account/profile') ?>"><i class="bi bi-person me-2"></i>Profile</a>
  <a class="list-group-item <?= str_contains(uri_string(), 'change-password') ? 'active' : '' ?>" href="<?= base_url('account/change-password') ?>"><i class="bi bi-key me-2"></i>Change password</a>
  <a class="list-group-item text-danger" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
</div>
