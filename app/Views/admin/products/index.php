<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="panel">
  <div class="panel-head"><strong>All products</strong>
    <div class="d-flex gap-2"><form class="d-flex" method="get"><input class="form-control form-control-sm" name="q" value="<?= esc($q) ?>" placeholder="Search name or SKU"><button class="btn btn-sm btn-outline-brand ms-1"><i class="bi bi-search"></i></button></form>
    <a href="<?= base_url('admin/products/create') ?>" class="btn btn-brand btn-sm"><i class="bi bi-plus-lg me-1"></i>Add product</a></div></div>
  <div class="table-responsive"><table class="table table-hover mb-0 row-anim">
    <thead><tr><th>Image</th><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
      <tr><td><img class="thumb-sm" src="<?= img_url($r['image']) ?>" alt=""></td>
        <td><strong><?= esc($r['name']) ?></strong> <?= $r['featured'] ? '<i class="bi bi-star-fill text-warning" title="Featured"></i>' : '' ?><div class="small text-muted"><?= esc($r['sku']) ?></div></td>
        <td><?= esc($r['category_name']) ?><div class="small text-muted"><?= esc($r['sub_name']) ?></div></td>
        <td><?= money(current_price($r)) ?><?php if (discount_pct($r)): ?><div class="small text-muted text-decoration-line-through"><?= money($r['price']) ?></div><?php endif; ?></td>
        <td><span class="badge <?= $r['stock'] < 6 ? 'text-bg-warning' : 'text-bg-light border' ?>"><?= $r['stock'] ?></span></td><td><?= active_badge($r['status']) ?></td>
        <td class="text-end text-nowrap"><a class="btn btn-sm btn-outline-brand" href="<?= base_url('admin/products/edit/' . $r['id']) ?>"><i class="bi bi-pencil"></i></a> <?= delete_form(base_url('admin/products/delete/' . $r['id'])) ?></td></tr>
    <?php endforeach; if (! $rows): ?><tr><td colspan="7" class="text-center text-muted py-4">No products found.</td></tr><?php endif; ?>
    </tbody></table></div>
  <div class="p-3"><?= $pager->links('default', 'bootstrap_full') ?></div>
</div>
<?= $this->endSection() ?>
