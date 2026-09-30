<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="panel" style="max-width:520px">
  <div class="panel-head"><strong>Change password</strong></div>
  <div class="panel-body">
    <form action="<?= base_url('admin/change-password') ?>" method="post">
      <?= csrf_field() ?>
      <div class="mb-3"><label class="form-label">Current password</label><input type="password" class="form-control" name="current_password" required></div>
      <div class="mb-3"><label class="form-label">New password</label><input type="password" class="form-control" name="new_password" minlength="6" required></div>
      <div class="mb-4"><label class="form-label">Confirm new password</label><input type="password" class="form-control" name="confirm_password" required></div>
      <button class="btn btn-brand">Update password</button>
    </form>
  </div>
</div>
<?= $this->endSection() ?>
