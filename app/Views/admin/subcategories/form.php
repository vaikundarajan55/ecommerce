<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<?php $edit = (bool) $row; ?>
<div class="panel" style="max-width:640px"><div class="panel-head"><strong><?= esc($title) ?></strong></div><div class="panel-body">
<form action="<?= base_url($edit ? 'admin/subcategories/update/' . $row['id'] : 'admin/subcategories/store') ?>" method="post" class="row g-3">
  <?= csrf_field() ?>
  <div class="col-12"><label class="form-label">Parent category</label>
    <select class="form-select" name="category_id" required><option value="">Choose category</option>
      <?php foreach ($categories as $c): ?><option value="<?= $c['id'] ?>" <?= old('category_id', $row['category_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= esc($c['name']) ?></option><?php endforeach; ?></select></div>
  <div class="col-12"><label class="form-label">Subcategory name</label><input class="form-control" name="name" value="<?= esc(old('name', $row['name'] ?? '')) ?>" required></div>
  <div class="col-12"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="status" id="st" value="1" <?= ($row['status'] ?? 1) ? 'checked' : '' ?>><label class="form-check-label" for="st">Active</label></div></div>
  <div class="col-12"><button class="btn btn-brand"><?= $edit ? 'Save changes' : 'Add subcategory' ?></button> <a href="<?= base_url('admin/subcategories') ?>" class="btn btn-light">Cancel</a></div>
</form></div></div>
<?= $this->endSection() ?>
