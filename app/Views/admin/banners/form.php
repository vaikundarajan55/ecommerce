<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<?php $edit = (bool) $row; ?>
<div class="panel" style="max-width:820px"><div class="panel-head"><strong><?= esc($title) ?></strong></div><div class="panel-body">
<form action="<?= base_url($edit ? 'admin/banners/update/' . $row['id'] : 'admin/banners/store') ?>" method="post" enctype="multipart/form-data" class="row g-3">
  <?= csrf_field() ?>
  <div class="col-md-8"><label class="form-label">Title</label><input class="form-control" name="title" value="<?= esc(old('title', $row['title'] ?? '')) ?>" required></div>
  <div class="col-md-4"><label class="form-label">Sort order</label><input type="number" class="form-control" name="sort_order" value="<?= esc(old('sort_order', $row['sort_order'] ?? 0)) ?>"></div>
  <div class="col-12"><label class="form-label">Subtitle</label><input class="form-control" name="subtitle" value="<?= esc(old('subtitle', $row['subtitle'] ?? '')) ?>"></div>
  <div class="col-md-6"><label class="form-label">Button link</label><input class="form-control" name="link" placeholder="/shop" value="<?= esc(old('link', $row['link'] ?? '')) ?>"></div>
  <div class="col-md-6"><label class="form-label">Image (1600×600 works well)</label><input type="file" class="form-control" name="image" accept="image/*" data-preview="prev"></div>
  <div class="col-12"><img id="prev" class="img-preview <?= ($row['image'] ?? '') ? '' : 'd-none' ?>" src="<?= img_url($row['image'] ?? null, 'banners') ?>" alt="Preview"></div>
  <div class="col-12"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="status" id="st" value="1" <?= ($row['status'] ?? 1) ? 'checked' : '' ?>><label class="form-check-label" for="st">Show on website</label></div></div>
  <div class="col-12"><button class="btn btn-brand"><?= $edit ? 'Save changes' : 'Add banner' ?></button> <a href="<?= base_url('admin/banners') ?>" class="btn btn-light">Cancel</a></div>
</form></div></div>
<?= $this->endSection() ?>
