<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="panel list-card">
  <?= view('admin/partials/list_head', ['heading' => 'Banner directory', 'sub' => 'Home page slider banners, links and display order', 'toolbar' => view('admin/partials/table_toolbar', compact('perPage', 'q') + ['placeholder' => 'Search title, subtitle, link']), 'action' => '<a href="' . base_url('admin/banners/create') . '" class="btn btn-grad"><i class="bi bi-plus-lg me-1"></i>Add banner</a>']) ?>
  <div class="list-table table-responsive"><table class="table mb-0 row-anim">
    <thead><tr><th>S. No</th><th>Image</th><th>Title</th><th>Link</th><th>Order</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $i => $r): ?>
      <tr><td class="sno"><?= sprintf('%02d', $from + $i) ?></td><td><img class="thumb-sm" style="width:90px" src="<?= img_url($r['image'], 'banners') ?>" alt=""></td>
        <td class="fw-bold"><?= esc($r['title']) ?><div class="small text-muted fw-normal"><?= esc($r['subtitle']) ?></div></td>
        <td><?= esc($r['link']) ?></td><td><span class="pill pill-blue"><?= $r['sort_order'] ?></span></td><td><?= active_badge($r['status']) ?></td>
        <td class="text-end"><div class="actions"><a class="btn-icon btn-icon-view" title="Edit" href="<?= base_url('admin/banners/edit/' . $r['id']) ?>"><i class="bi bi-pencil"></i></a> <?= delete_form(base_url('admin/banners/delete/' . $r['id'])) ?></div></td></tr>
    <?php endforeach; if (! $rows): ?><tr><td colspan="7" class="text-center text-muted py-5">No banners found.</td></tr><?php endif; ?>
    </tbody></table></div>
  <?= $this->include('admin/partials/table_footer') ?>
</div>
<?= $this->endSection() ?>
