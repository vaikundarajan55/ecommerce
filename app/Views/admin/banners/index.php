<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="panel">
  <div class="panel-head"><strong>All banners</strong><a href="<?= base_url('admin/banners/create') ?>" class="btn btn-brand btn-sm"><i class="bi bi-plus-lg me-1"></i>Add banner</a></div>
  <div class="table-responsive"><table class="table table-hover mb-0 row-anim">
    <thead><tr><th>Image</th><th>Title</th><th>Link</th><th>Order</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
      <tr><td><img class="thumb-sm" style="width:90px" src="<?= img_url($r['image'], 'banners') ?>" alt=""></td>
        <td><strong><?= esc($r['title']) ?></strong><div class="small text-muted"><?= esc($r['subtitle']) ?></div></td>
        <td><?= esc($r['link']) ?></td><td><?= $r['sort_order'] ?></td><td><?= active_badge($r['status']) ?></td>
        <td class="text-end text-nowrap"><a class="btn btn-sm btn-outline-brand" href="<?= base_url('admin/banners/edit/' . $r['id']) ?>"><i class="bi bi-pencil"></i></a> <?= delete_form(base_url('admin/banners/delete/' . $r['id'])) ?></td></tr>
    <?php endforeach; if (! $rows): ?><tr><td colspan="6" class="text-center text-muted py-4">No banners yet. Add one to show it on the home page.</td></tr><?php endif; ?>
    </tbody></table></div>
</div>
<?= $this->endSection() ?>
