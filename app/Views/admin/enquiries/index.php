<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="panel"><div class="panel-head"><strong>Product enquiries</strong></div>
  <div class="table-responsive"><table class="table table-hover mb-0 row-anim">
    <thead><tr><th>Date</th><th>From</th><th>Product</th><th>Message</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
      <tr class="<?= $r['is_read'] ? '' : 'fw-bold' ?>"><td class="text-nowrap"><?= date('d M Y', strtotime($r['created_at'])) ?></td><td><?= esc($r['name']) ?><div class="small text-muted fw-normal"><?= esc($r['email']) ?></div></td><td><?= esc($r['product_name'] ?: '—') ?></td>
        <td><?= esc(character_limiter($r['message'], 60)) ?> <?= $r['is_read'] ? '' : '<span class="badge text-bg-warning">New</span>' ?></td>
        <td class="text-end text-nowrap"><a class="btn btn-sm btn-outline-brand" href="<?= base_url('admin/enquiries/view/' . $r['id']) ?>"><i class="bi bi-eye"></i></a> <?= delete_form(base_url('admin/enquiries/delete/' . $r['id'])) ?></td></tr>
    <?php endforeach; if (! $rows): ?><tr><td colspan="5" class="text-center text-muted py-4">No enquiries yet.</td></tr><?php endif; ?>
    </tbody></table></div>
  <div class="p-3"><?= $pager->links('default', 'bootstrap_full') ?></div></div>
<?= $this->endSection() ?>
