<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<?php $edit = (bool) $row; $v = fn ($k, $d = '') => esc(old($k, $row[$k] ?? $d)); ?>
<form action="<?= base_url($edit ? 'admin/products/update/' . $row['id'] : 'admin/products/store') ?>" method="post" enctype="multipart/form-data" class="row g-3">
  <?= csrf_field() ?>
  <div class="col-lg-8">
    <div class="panel"><div class="panel-head"><strong><?= esc($title) ?></strong></div><div class="panel-body row g-3">
      <div class="col-12"><label class="form-label">Product name</label><input class="form-control" name="name" value="<?= $v('name') ?>" required></div>
      <div class="col-md-6"><label class="form-label">Category</label>
        <select class="form-select" name="category_id" id="categorySelect" required><option value="">Choose category</option>
          <?php foreach ($categories as $c): ?><option value="<?= $c['id'] ?>" <?= old('category_id', $row['category_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= esc($c['name']) ?></option><?php endforeach; ?></select></div>
      <div class="col-md-6"><label class="form-label">Subcategory</label>
        <select class="form-select" name="subcategory_id" id="subSelect"><option value="">None</option>
          <?php foreach ($subs as $s): ?><option value="<?= $s['id'] ?>" data-cat="<?= $s['category_id'] ?>" <?= old('subcategory_id', $row['subcategory_id'] ?? '') == $s['id'] ? 'selected' : '' ?>><?= esc($s['name']) ?></option><?php endforeach; ?></select></div>
      <div class="col-12"><label class="form-label">Short description</label><input class="form-control" name="short_desc" maxlength="300" value="<?= $v('short_desc') ?>"></div>
      <div class="col-12"><label class="form-label">Full description</label><textarea class="form-control" name="description" rows="6"><?= $v('description') ?></textarea></div>
    </div></div>
  </div>
  <div class="col-lg-4">
    <div class="panel mb-3"><div class="panel-body row g-3">
      <div class="col-6"><label class="form-label">Price (₹)</label><input type="number" step="0.01" min="0" class="form-control" name="price" value="<?= $v('price') ?>" required></div>
      <div class="col-6"><label class="form-label">Sale price (₹)</label><input type="number" step="0.01" min="0" class="form-control" name="sale_price" value="<?= $v('sale_price') ?>"></div>
      <div class="col-6"><label class="form-label">Stock</label><input type="number" min="0" class="form-control" name="stock" value="<?= $v('stock', 0) ?>" required></div>
      <div class="col-6"><label class="form-label">SKU</label><input class="form-control" name="sku" value="<?= $v('sku') ?>"></div>
      <div class="col-12"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="featured" id="ft" value="1" <?= ($row['featured'] ?? 0) ? 'checked' : '' ?>><label class="form-check-label" for="ft">Featured on home page</label></div></div>
      <div class="col-12"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="status" id="st" value="1" <?= ($row['status'] ?? 1) ? 'checked' : '' ?>><label class="form-check-label" for="st">Active (visible in shop)</label></div></div>
    </div></div>
    <div class="panel"><div class="panel-body">
      <label class="form-label">Main image</label><input type="file" class="form-control mb-3" name="image" accept="image/*" data-preview="prev">
      <img id="prev" class="img-preview <?= ($row['image'] ?? '') ? '' : 'd-none' ?>" src="<?= img_url($row['image'] ?? null) ?>" alt="Preview">
    </div></div>
  </div>

  <div class="col-12">
    <div class="panel"><div class="panel-head"><strong>More images</strong><span class="small text-muted"><span id="galleryCount"><?= count($gallery) ?></span> / <?= $maxGallery ?> images · JPG, PNG or WEBP, up to 3 MB each</span></div>
      <div class="panel-body">
        <div class="gallery-grid" id="galleryGrid" data-max="<?= $maxGallery ?>">
          <?php foreach ($gallery as $g): ?>
            <label class="gallery-item" title="Click to mark for removal">
              <img src="<?= img_url($g['image']) ?>" alt="">
              <input type="checkbox" name="remove_gallery[]" value="<?= $g['id'] ?>" class="d-none">
              <span class="gallery-remove"><i class="bi bi-trash3"></i></span>
              <span class="gallery-undo">Will be removed · click to undo</span>
            </label>
          <?php endforeach; ?>
          <label class="gallery-add" id="galleryAdd">
            <i class="bi bi-cloud-arrow-up"></i><span>Add images</span><small>Select several at once</small>
            <input type="file" name="gallery[]" id="galleryInput" accept="image/*" multiple class="d-none">
          </label>
        </div>
        <?php if ($gallery): ?><p class="small text-muted mt-3 mb-0"><i class="bi bi-info-circle me-1"></i>Click an existing image to mark it for removal. Changes are saved when you press <?= $edit ? '“Save changes”' : '“Add product”' ?>.</p><?php endif; ?>
      </div></div>
  </div>
  <div class="col-12"><button class="btn btn-brand"><?= $edit ? 'Save changes' : 'Add product' ?></button> <a href="<?= base_url('admin/products') ?>" class="btn btn-light">Cancel</a></div>
</form>
<?= $this->endSection() ?>
