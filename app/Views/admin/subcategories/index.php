<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="panel list-card">
  <?= view('admin/partials/list_head', ['heading' => 'Subcategory directory', 'sub' => 'Subcategory names and the category each belongs to', 'toolbar' => view('admin/partials/table_toolbar', compact('perPage', 'q') + ['placeholder' => 'Search name or category']), 'action' => '<a href="' . base_url('admin/subcategories/create') . '" class="btn btn-grad"><i class="bi bi-plus-lg me-1"></i>Add subcategory</a>']) ?>
  <div class="list-table table-responsive"><table class="table mb-0 row-anim">
    <thead><tr><th>S. No</th><th>Subcategory name</th><th>Category</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $i => $r): ?>
      <tr><td class="sno"><?= sprintf('%02d', $from + $i) ?></td><td class="fw-bold"><?= esc($r['name']) ?><div class="small text-muted fw-normal"><?= esc($r['slug']) ?></div></td><td><span class="pill pill-blue"><?= esc($r['category_name']) ?></span></td><td><?= active_badge($r['status']) ?></td>
        <td class="text-end"><div class="actions"><a class="btn-icon btn-icon-view" title="Edit" href="<?= base_url('admin/subcategories/edit/' . $r['id']) ?>"><i class="bi bi-pencil"></i></a> <?= delete_form(base_url('admin/subcategories/delete/' . $r['id'])) ?></div></td></tr>
    <?php endforeach; if (! $rows): ?><tr><td colspan="5" class="text-center text-muted py-5">No subcategories found.</td></tr><?php endif; ?>
    </tbody></table></div>
  <?= $this->include('admin/partials/table_footer') ?>
</div>
<?= $this->endSection() ?>
