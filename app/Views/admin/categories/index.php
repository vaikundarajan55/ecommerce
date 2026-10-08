<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="panel list-card">
  <?= view('admin/partials/list_head', ['heading' => 'Category directory', 'sub' => 'Category names, subcategory counts and product counts', 'toolbar' => view('admin/partials/table_toolbar', compact('perPage', 'q') + ['placeholder' => 'Search name or slug']), 'action' => '<a href="' . base_url('admin/categories/create') . '" class="btn btn-grad"><i class="bi bi-plus-lg me-1"></i>Add category</a>']) ?>
  <div class="list-table table-responsive"><table class="table mb-0 row-anim">
    <thead><tr><th>S. No</th><th>Image</th><th>Category name</th><th>Subcategories</th><th>Products</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $i => $r): ?>
      <tr><td class="sno"><?= sprintf('%02d', $from + $i) ?></td><td><img class="thumb-sm" src="<?= img_url($r['image'], 'categories') ?>" alt=""></td><td class="fw-bold"><?= esc($r['name']) ?><div class="small text-muted fw-normal"><?= esc($r['slug']) ?></div></td>
        <td><span class="pill pill-green"><?= $r['subs'] ?> Subcategories</span></td><td><span class="pill pill-blue"><?= $r['prods'] ?> Products</span></td><td><?= active_badge($r['status']) ?></td>
        <td class="text-end"><div class="actions"><a class="btn-icon btn-icon-view" title="Edit" href="<?= base_url('admin/categories/edit/' . $r['id']) ?>"><i class="bi bi-pencil"></i></a> <?= delete_form(base_url('admin/categories/delete/' . $r['id']), 'Delete this category? Its subcategories and products will also be deleted.') ?></div></td></tr>
    <?php endforeach; if (! $rows): ?><tr><td colspan="7" class="text-center text-muted py-5">No categories found.</td></tr><?php endif; ?>
    </tbody></table></div>
  <?= $this->include('admin/partials/table_footer') ?>
</div>
<?= $this->endSection() ?>
