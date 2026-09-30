<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<?php $edit = (bool) $row; ?>
<div class="panel" style="max-width:640px"><div class="panel-head"><strong><?= esc($title) ?></strong></div><div class="panel-body">
<form action="<?= base_url($edit ? 'admin/categories/update/' . $row['id'] : 'admin/categories/store') ?>" method="post" enctype="multipart/form-data" class="row g-3">
  <?= csrf_field() ?>
  <div class="col-12"><label class="form-label">Category name</label><input class="form-control" name="name" value="<?= esc(old('name', $row['name'] ?? '')) ?>" required></div>
  <div class="col-12"><label class="form-label">Image</label><input type="file" class="form-control" name="image" accept="image/*" data-preview="prev"></div>
  <div class="col-12"><img id="prev" class="img-preview <?= ($row['image'] ?? '') ? '' : 'd-none' ?>" src="<?= img_url($row['image'] ?? null, 'categories') ?>" alt="Preview"></div>
  <div class="col-12"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="status" id="st" value="1" <?= ($row['status'] ?? 1) ? 'checked' : '' ?>><label class="form-check-label" for="st">Active</label></div></div>
  <div class="col-12"><button class="btn btn-brand"><?= $edit ? 'Save changes' : 'Add category' ?></button> <a href="<?= base_url('admin/categories') ?>" class="btn btn-light">Cancel</a></div>
</form></div></div>
<?= $this->endSection() ?>
