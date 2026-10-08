<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<?= view('admin/partials/status_metrics', ['label' => 'Customer', 'plural' => 'customers', 'stats' => $stats]) ?>
<div class="panel list-card">
  <?= view('admin/partials/list_head', ['heading' => 'Consolidated customer directory', 'sub' => 'Registered customers, contact details, order counts, and system timeline logs', 'toolbar' => view('admin/partials/table_toolbar', compact('perPage', 'q') + ['placeholder' => 'Name, email, phone, city'])]) ?>
  <div class="list-table table-responsive"><table class="table mb-0 row-anim">
    <thead><tr><th>S. No</th><th>Name</th><th>Email</th><th>Phone</th><th>Orders</th><th>Joined date</th><th>Modified date</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $i => $r): ?>
      <tr><td class="sno"><?= sprintf('%02d', $from + $i) ?></td><td class="fw-bold"><?= esc($r['name']) ?><div class="small text-muted fw-normal"><?= esc($r['city']) ?></div></td><td><?= esc($r['email']) ?></td><td><?= esc($r['phone']) ?></td>
        <td><span class="pill pill-blue"><?= $r['order_count'] ?> Orders</span></td>
        <td><?= date_cell($r['created_at']) ?></td><td><?= date_cell($r['updated_at'] ?? $r['created_at']) ?></td><td><?= active_badge($r['status']) ?></td>
        <td class="text-end"><?= row_menu([
          [$r['status'] ? 'Disable' : 'Enable', 'bi-toggle-' . ($r['status'] ? 'off' : 'on'), base_url('admin/users/toggle/' . $r['id']), 'post' => true],
          ['Delete', 'bi-trash3', base_url('admin/users/delete/' . $r['id']), 'post' => true, 'danger' => true, 'confirm' => 'Delete this user and all their orders?'],
        ]) ?></td></tr>
    <?php endforeach; if (! $rows): ?><tr><td colspan="9" class="text-center text-muted py-5">No users found.</td></tr><?php endif; ?>
    </tbody></table></div>
  <?= $this->include('admin/partials/table_footer') ?>
</div>
<?= $this->endSection() ?>
