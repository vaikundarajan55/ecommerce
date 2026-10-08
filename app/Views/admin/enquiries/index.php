<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="panel list-card">
  <?= view('admin/partials/list_head', ['heading' => 'Product enquiries', 'sub' => 'Questions customers sent from product pages', 'toolbar' => view('admin/partials/table_toolbar', compact('perPage', 'q') + ['placeholder' => 'Name, email, product, message'])]) ?>
  <div class="list-table table-responsive"><table class="table mb-0 row-anim">
    <thead><tr><th>S. No</th><th>Date</th><th>From</th><th>Product</th><th>Message</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $i => $r): ?>
      <tr class="<?= $r['is_read'] ? '' : 'unread' ?>"><td class="sno"><?= sprintf('%02d', $from + $i) ?></td><td class="text-nowrap"><?= date('d M Y', strtotime($r['created_at'])) ?></td><td class="fw-bold"><?= esc($r['name']) ?><div class="small text-muted fw-normal"><?= esc($r['email']) ?></div></td><td><?= esc($r['product_name'] ?: '—') ?></td>
        <td><?= esc(character_limiter($r['message'], 60)) ?> <?= $r['is_read'] ? '' : '<span class="pill pill-amber">New</span>' ?></td>
        <td class="text-end"><div class="actions"><a class="btn-icon btn-icon-view" title="View" href="<?= base_url('admin/enquiries/view/' . $r['id']) ?>"><i class="bi bi-eye"></i></a> <?= delete_form(base_url('admin/enquiries/delete/' . $r['id'])) ?></div></td></tr>
    <?php endforeach; if (! $rows): ?><tr><td colspan="6" class="text-center text-muted py-5">No enquiries found.</td></tr><?php endif; ?>
    </tbody></table></div>
  <?= $this->include('admin/partials/table_footer') ?>
</div>
<?= $this->endSection() ?>
