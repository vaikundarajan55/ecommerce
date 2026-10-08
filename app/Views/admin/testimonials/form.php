<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<?php $edit = (bool) $row; $rating = (int) old('rating', $row['rating'] ?? 5); ?>
<div class="panel" style="max-width:820px"><div class="panel-head"><strong><?= esc($title) ?></strong></div><div class="panel-body">
<form action="<?= base_url($edit ? 'admin/testimonials/update/' . $row['id'] : 'admin/testimonials/store') ?>" method="post" enctype="multipart/form-data" class="row g-3">
  <?= csrf_field() ?>
  <div class="col-md-6"><label class="form-label" for="tName">Customer name</label><input id="tName" class="form-control" name="name" maxlength="100" value="<?= esc(old('name', $row['name'] ?? '')) ?>" required></div>
  <div class="col-md-6"><label class="form-label" for="tRole">Role or city <span class="text-muted fw-normal">(optional)</span></label><input id="tRole" class="form-control" name="role" maxlength="120" placeholder="e.g. Verified buyer, Chennai" value="<?= esc(old('role', $row['role'] ?? '')) ?>"></div>
  <div class="col-12"><label class="form-label" for="tMsg">Testimonial</label><textarea id="tMsg" class="form-control" name="message" rows="4" maxlength="1000" required><?= esc(old('message', $row['message'] ?? '')) ?></textarea></div>
  <div class="col-md-4"><label class="form-label" for="tRate">Rating</label>
    <select id="tRate" class="form-select" name="rating"><?php for ($n = 5; $n >= 1; $n--): ?><option value="<?= $n ?>" <?= $rating === $n ? 'selected' : '' ?>><?= $n ?> star<?= $n > 1 ? 's' : '' ?></option><?php endfor; ?></select></div>
  <div class="col-md-4"><label class="form-label" for="tSort">Display order</label><input id="tSort" type="number" class="form-control" name="sort_order" value="<?= esc(old('sort_order', $row['sort_order'] ?? 0)) ?>"></div>
  <div class="col-md-4"><label class="form-label" for="tPhoto">Photo <span class="text-muted fw-normal">(optional)</span></label><input id="tPhoto" type="file" class="form-control" name="photo" accept="image/*" data-preview="prev"></div>
  <div class="col-12"><img id="prev" class="img-preview rounded-circle <?= ($row['photo'] ?? '') ? '' : 'd-none' ?>" style="width:110px;height:110px;object-fit:cover" src="<?= img_url($row['photo'] ?? null, 'testimonials') ?>" alt="Preview"></div>
  <div class="col-12"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="status" id="st" value="1" <?= ($row['status'] ?? 1) ? 'checked' : '' ?>><label class="form-check-label" for="st">Show on website</label></div></div>
  <div class="col-12"><button class="btn btn-brand"><?= $edit ? 'Save changes' : 'Add testimonial' ?></button> <a href="<?= base_url('admin/testimonials') ?>" class="btn btn-light">Cancel</a></div>
</form></div></div>
<?= $this->endSection() ?>
