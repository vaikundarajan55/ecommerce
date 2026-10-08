<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<?= view('admin/partials/status_metrics', ['label' => 'Banner', 'plural' => 'banners', 'stats' => $stats]) ?>
<div class="panel list-card">
  <?= view('admin/partials/list_head', ['heading' => 'Consolidated banner directory', 'sub' => 'Home page slider banners, links, display order, and system timeline logs', 'toolbar' => view('admin/partials/table_toolbar', compact('perPage', 'q') + ['placeholder' => 'Search title, subtitle, link']), 'action' => '<a href="' . base_url('admin/banners/create') . '" class="btn btn-grad"><i class="bi bi-plus-lg me-1"></i>Add Banner</a>']) ?>
  <div class="list-table table-responsive"><table class="table mb-0 row-anim">
    <thead><tr><th>S. No</th><th>Image</th><th>Title</th><th>Link</th><th>Order</th><th>Created date</th><th>Modified date</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $i => $r): ?>
      <tr><td class="sno"><?= sprintf('%02d', $from + $i) ?></td><td><img class="thumb-sm" style="width:90px" src="<?= img_url($r['image'], 'banners') ?>" alt=""></td>
        <td class="fw-bold"><?= esc($r['title']) ?><div class="small text-muted fw-normal"><?= esc($r['subtitle']) ?></div></td>
        <td><?= esc($r['link']) ?></td><td><span class="pill pill-blue"><?= $r['sort_order'] ?></span></td>
        <td><?= date_cell($r['created_at']) ?></td><td><?= date_cell($r['updated_at'] ?? $r['created_at']) ?></td><td><?= active_badge($r['status']) ?></td>
        <td class="text-end"><?= row_menu([
          ['Edit', 'bi-pencil', base_url('admin/banners/edit/' . $r['id'])],
          ['Delete', 'bi-trash3', base_url('admin/banners/delete/' . $r['id']), 'post' => true, 'danger' => true, 'confirm' => 'Delete this item? This cannot be undone.'],
        ]) ?></td></tr>
    <?php endforeach; if (! $rows): ?><tr><td colspan="9" class="text-center text-muted py-5">No banners found.</td></tr><?php endif; ?>
    </tbody></table></div>
  <?= $this->include('admin/partials/table_footer') ?>
</div>
<?= $this->endSection() ?>
