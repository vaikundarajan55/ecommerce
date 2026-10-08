<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="panel list-card">
  <?= view('admin/partials/list_head', ['heading' => 'Product directory', 'sub' => 'Products with category, price, stock and status', 'toolbar' => view('admin/partials/table_toolbar', compact('perPage', 'q') + ['placeholder' => 'Search name, SKU, category']), 'action' => '<a href="' . base_url('admin/products/create') . '" class="btn btn-grad"><i class="bi bi-plus-lg me-1"></i>Add product</a>']) ?>
  <div class="list-table table-responsive"><table class="table mb-0 row-anim">
    <thead><tr><th>S. No</th><th>Image</th><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $i => $r): ?>
      <tr><td class="sno"><?= sprintf('%02d', $from + $i) ?></td><td><img class="thumb-sm" src="<?= img_url($r['image']) ?>" alt=""></td>
        <td class="fw-bold"><?= esc($r['name']) ?> <?= $r['featured'] ? '<i class="bi bi-star-fill text-warning" title="Featured"></i>' : '' ?><div class="small text-muted fw-normal"><?= esc($r['sku']) ?></div></td>
        <td><?= esc($r['category_name']) ?><div class="small text-muted"><?= esc($r['sub_name']) ?></div></td>
        <td class="fw-bold"><?= money(current_price($r)) ?><?php if (discount_pct($r)): ?><div class="small text-muted fw-normal text-decoration-line-through"><?= money($r['price']) ?></div><?php endif; ?></td>
        <td><span class="pill <?= $r['stock'] < 6 ? 'pill-amber' : 'pill-green' ?>"><?= $r['stock'] ?> in stock</span></td><td><?= active_badge($r['status']) ?></td>
        <td class="text-end"><div class="actions"><a class="btn-icon btn-icon-view" title="Edit" href="<?= base_url('admin/products/edit/' . $r['id']) ?>"><i class="bi bi-pencil"></i></a> <?= delete_form(base_url('admin/products/delete/' . $r['id'])) ?></div></td></tr>
    <?php endforeach; if (! $rows): ?><tr><td colspan="8" class="text-center text-muted py-5">No products found.</td></tr><?php endif; ?>
    </tbody></table></div>
  <?= $this->include('admin/partials/table_footer') ?>
</div>
<?= $this->endSection() ?>
