<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<?= view('admin/partials/status_metrics', ['label' => 'Product', 'plural' => 'products', 'stats' => $stats]) ?>
<?php
  // Home page sections a product can be tagged for: column => [label, icon, pill tone]
  $tags = ['featured' => ['Featured', 'bi-star-fill', 'pill-amber'], 'is_current' => ['Current', 'bi-lightning-charge-fill', 'pill-blue'], 'is_peak' => ['Peak', 'bi-fire', 'pill-red']];
  $tagSelect = '<select name="tag" class="filter-select" onchange="this.form.submit()"><option value="">All home tags</option>';
  foreach ($tags as $k => [$lbl]) { $tagSelect .= '<option value="' . $k . '"' . ($tag === $k ? ' selected' : '') . '>' . $lbl . '</option>'; }
  $tagSelect .= '</select>';
?>
<div class="panel list-card">
  <?= view('admin/partials/list_head', ['heading' => 'Consolidated product directory', 'sub' => 'Products with category, price, stock, home page tags, status, and system timeline logs', 'toolbar' => view('admin/partials/table_toolbar', compact('perPage', 'q') + ['placeholder' => 'Search name, SKU, category', 'extra' => $tagSelect]),'action' => '<a href="' . base_url('admin/products/create') . '" class="btn btn-grad"><i class="bi bi-plus-lg me-1"></i>Add Product</a>']) ?>
  <div class="list-table table-responsive"><table class="table mb-0 row-anim">
    <thead><tr><th>S. No</th><th>Image</th><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Created date</th><th>Modified date</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $i => $r): ?>
      <tr><td class="sno"><?= sprintf('%02d', $from + $i) ?></td><td><img class="thumb-sm" src="<?= img_url($r['image']) ?>" alt=""></td>
        <td class="fw-bold"><?= esc($r['name']) ?><div class="small text-muted fw-normal"><?= esc($r['sku']) ?></div>
          <?php $on = array_filter($tags, static fn ($k) => ! empty($r[$k]), ARRAY_FILTER_USE_KEY); if ($on): ?><div class="d-flex flex-wrap gap-1 mt-1"><?php foreach ($on as [$lbl, $ic, $tone]): ?><span class="pill <?= $tone ?>"><i class="bi <?= $ic ?> me-1"></i><?= $lbl ?></span><?php endforeach; ?></div><?php endif; ?></td>
        <td><?= esc($r['category_name']) ?><div class="small text-muted"><?= esc($r['sub_name']) ?></div></td>
        <td class="fw-bold"><?= money(current_price($r)) ?><?php if (discount_pct($r)): ?><div class="small text-muted fw-normal text-decoration-line-through"><?= money($r['price']) ?></div><?php endif; ?></td>
        <td><span class="pill <?= $r['stock'] < 6 ? 'pill-amber' : 'pill-green' ?>"><?= $r['stock'] ?> in stock</span></td>
        <td><?= date_cell($r['created_at']) ?></td><td><?= date_cell($r['updated_at'] ?? $r['created_at']) ?></td><td><?= active_badge($r['status']) ?></td>
        <td class="text-end"><?= row_menu([
          ['Edit', 'bi-pencil', base_url('admin/products/edit/' . $r['id'])],
          ['Delete', 'bi-trash3', base_url('admin/products/delete/' . $r['id']), 'post' => true, 'danger' => true, 'confirm' => 'Delete this item? This cannot be undone.'],
        ]) ?></td></tr>
    <?php endforeach; if (! $rows): ?><tr><td colspan="10" class="text-center text-muted py-5">No products found.</td></tr><?php endif; ?>
    </tbody></table></div>
  <?= $this->include('admin/partials/table_footer') ?>
</div>
<?= $this->endSection() ?>
