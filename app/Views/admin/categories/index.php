<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="panel">
  <div class="panel-head"><strong>All categories</strong><a href="<?= base_url('admin/categories/create') ?>" class="btn btn-brand btn-sm"><i class="bi bi-plus-lg me-1"></i>Add category</a></div>
  <div class="table-responsive"><table class="table table-hover mb-0 row-anim">
    <thead><tr><th>Image</th><th>Name</th><th>Subcategories</th><th>Products</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
      <tr><td><img class="thumb-sm" src="<?= img_url($r['image'], 'categories') ?>" alt=""></td><td><strong><?= esc($r['name']) ?></strong><div class="small text-muted"><?= esc($r['slug']) ?></div></td>
        <td><?= $r['subs'] ?></td><td><?= $r['prods'] ?></td><td><?= active_badge($r['status']) ?></td>
        <td class="text-end text-nowrap"><a class="btn btn-sm btn-outline-brand" href="<?= base_url('admin/categories/edit/' . $r['id']) ?>"><i class="bi bi-pencil"></i></a>
          <?= delete_form(base_url('admin/categories/delete/' . $r['id']), 'Delete this category? Its subcategories and products will also be deleted.') ?></td></tr>
    <?php endforeach; if (! $rows): ?><tr><td colspan="6" class="text-center text-muted py-4">No categories yet.</td></tr><?php endif; ?>
    </tbody></table></div>
</div>
<?= $this->endSection() ?>
