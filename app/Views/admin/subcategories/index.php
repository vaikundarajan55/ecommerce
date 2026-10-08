<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<?= view('admin/partials/status_metrics', ['label' => 'Subcategory', 'plural' => 'subcategories', 'stats' => $stats]) ?>
<div class="panel list-card">
  <?= view('admin/partials/list_head', ['heading' => 'Consolidated subcategory directory', 'sub' => 'Subcategory names, parent category, status, and system timeline logs', 'toolbar' => view('admin/partials/table_toolbar', compact('perPage', 'q') + ['placeholder' => 'Search name or category']), 'action' => '<a href="' . base_url('admin/subcategories/create') . '" class="btn btn-grad"><i class="bi bi-plus-lg me-1"></i>Add Subcategory</a>']) ?>
  <div class="list-table table-responsive"><table class="table mb-0 row-anim">
    <thead><tr><th>S. No</th><th>Subcategory name</th><th>Category</th><th>Created date</th><th>Modified date</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $i => $r): ?>
      <tr><td class="sno"><?= sprintf('%02d', $from + $i) ?></td><td class="fw-bold"><?= esc($r['name']) ?><div class="small text-muted fw-normal"><?= esc($r['slug']) ?></div></td><td><span class="pill pill-blue"><?= esc($r['category_name']) ?></span></td>
        <td><?= date_cell($r['created_at']) ?></td><td><?= date_cell($r['updated_at'] ?? $r['created_at']) ?></td><td><?= active_badge($r['status']) ?></td>
        <td class="text-end"><?= row_menu([
          ['Edit', 'bi-pencil', base_url('admin/subcategories/edit/' . $r['id'])],
          ['Delete', 'bi-trash3', base_url('admin/subcategories/delete/' . $r['id']), 'post' => true, 'danger' => true, 'confirm' => 'Delete this item? This cannot be undone.'],
        ]) ?></td></tr>
    <?php endforeach; if (! $rows): ?><tr><td colspan="7" class="text-center text-muted py-5">No subcategories found.</td></tr><?php endif; ?>
    </tbody></table></div>
  <?= $this->include('admin/partials/table_footer') ?>
</div>
<?= $this->endSection() ?>
