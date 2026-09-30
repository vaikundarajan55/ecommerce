<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="panel">
  <div class="panel-head"><strong>All subcategories</strong><a href="<?= base_url('admin/subcategories/create') ?>" class="btn btn-brand btn-sm"><i class="bi bi-plus-lg me-1"></i>Add subcategory</a></div>
  <div class="table-responsive"><table class="table table-hover mb-0 row-anim">
    <thead><tr><th>Name</th><th>Category</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
      <tr><td><strong><?= esc($r['name']) ?></strong><div class="small text-muted"><?= esc($r['slug']) ?></div></td><td><?= esc($r['category_name']) ?></td><td><?= active_badge($r['status']) ?></td>
        <td class="text-end text-nowrap"><a class="btn btn-sm btn-outline-brand" href="<?= base_url('admin/subcategories/edit/' . $r['id']) ?>"><i class="bi bi-pencil"></i></a> <?= delete_form(base_url('admin/subcategories/delete/' . $r['id'])) ?></td></tr>
    <?php endforeach; if (! $rows): ?><tr><td colspan="4" class="text-center text-muted py-4">No subcategories yet.</td></tr><?php endif; ?>
    </tbody></table></div>
</div>
<?= $this->endSection() ?>
