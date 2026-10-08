<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<?= view('admin/partials/status_metrics', ['label' => 'Category', 'plural' => 'categories', 'stats' => $stats]) ?>
<div class="panel list-card">
  <?= view('admin/partials/list_head', ['heading' => 'Consolidated category directory', 'sub' => 'Category names, status, and system timeline logs', 'toolbar' => view('admin/partials/table_toolbar', compact('perPage', 'q') + ['placeholder' => 'Search name or slug']), 'action' => '<a href="' . base_url('admin/categories/create') . '" class="btn btn-grad"><i class="bi bi-plus-lg me-1"></i>Add Category</a>']) ?>
  <div class="list-table table-responsive"><table class="table mb-0 row-anim">
    <thead><tr><th>S. No</th><th>Image</th><th>Category name</th><th>Subcategories</th><th>Products</th><th>Created date</th><th>Modified date</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $i => $r): ?>
      <tr><td class="sno"><?= sprintf('%02d', $from + $i) ?></td><td><img class="thumb-sm" src="<?= img_url($r['image'], 'categories') ?>" alt=""></td><td class="fw-bold"><?= esc($r['name']) ?><div class="small text-muted fw-normal"><?= esc($r['slug']) ?></div></td>
        <td><span class="pill pill-green"><?= $r['subs'] ?> Subcategories</span></td><td><span class="pill pill-blue"><?= $r['prods'] ?> Products</span></td>
        <td><?= date_cell($r['created_at']) ?></td><td><?= date_cell($r['updated_at'] ?? $r['created_at']) ?></td><td><?= active_badge($r['status']) ?></td>
        <td class="text-end"><?= row_menu([
          ['Edit', 'bi-pencil', base_url('admin/categories/edit/' . $r['id'])],
          ['Delete', 'bi-trash3', base_url('admin/categories/delete/' . $r['id']), 'post' => true, 'danger' => true, 'confirm' => 'Delete this category? Its subcategories and products will also be deleted.'],
        ]) ?></td></tr>
    <?php endforeach; if (! $rows): ?><tr><td colspan="9" class="text-center text-muted py-5">No categories found.</td></tr><?php endif; ?>
    </tbody></table></div>
  <?= $this->include('admin/partials/table_footer') ?>
</div>
<?= $this->endSection() ?>
