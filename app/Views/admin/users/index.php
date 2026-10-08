<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="panel list-card">
  <?= view('admin/partials/list_head', ['heading' => 'Customer directory', 'sub' => 'Registered customers, contact details and order counts', 'toolbar' => view('admin/partials/table_toolbar', compact('perPage', 'q') + ['placeholder' => 'Name, email, phone, city'])]) ?>
  <div class="list-table table-responsive"><table class="table mb-0 row-anim">
    <thead><tr><th>S. No</th><th>Name</th><th>Email</th><th>Phone</th><th>Orders</th><th>Joined</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $i => $r): ?>
      <tr><td class="sno"><?= sprintf('%02d', $from + $i) ?></td><td class="fw-bold"><?= esc($r['name']) ?><div class="small text-muted fw-normal"><?= esc($r['city']) ?></div></td><td><?= esc($r['email']) ?></td><td><?= esc($r['phone']) ?></td>
        <td><span class="pill pill-blue"><?= $r['order_count'] ?> Orders</span></td><td class="text-nowrap"><?= date('d M Y', strtotime($r['created_at'])) ?></td><td><?= active_badge($r['status']) ?></td>
        <td class="text-end"><div class="actions"><form action="<?= base_url('admin/users/toggle/' . $r['id']) ?>" method="post" class="d-inline"><?= csrf_field() ?><button class="btn-icon btn-icon-alt" title="Enable / disable"><i class="bi bi-toggle-<?= $r['status'] ? 'on' : 'off' ?>"></i></button></form> <?= delete_form(base_url('admin/users/delete/' . $r['id']), 'Delete this user and all their orders?') ?></div></td></tr>
    <?php endforeach; if (! $rows): ?><tr><td colspan="8" class="text-center text-muted py-5">No users found.</td></tr><?php endif; ?>
    </tbody></table></div>
  <?= $this->include('admin/partials/table_footer') ?>
</div>
<?= $this->endSection() ?>
