<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<?php $statusSelect = '<select name="status" class="filter-select" onchange="this.form.submit()"><option value="">All statuses</option>';
  foreach (['placed', 'processing', 'shipped', 'delivered', 'cancelled'] as $s) { $statusSelect .= '<option value="' . $s . '"' . ($status === $s ? ' selected' : '') . '>' . ucfirst($s) . '</option>'; }
  $statusSelect .= '</select>'; ?>
<div class="panel list-card">
  <?= view('admin/partials/list_head', ['heading' => 'Order directory', 'sub' => 'Customer orders with totals, payment and delivery status', 'toolbar' => view('admin/partials/table_toolbar', compact('perPage', 'q') + ['placeholder' => 'Order no, name, email, phone', 'extra' => $statusSelect])]) ?>
  <div class="list-table table-responsive"><table class="table mb-0 row-anim">
    <thead><tr><th>S. No</th><th>Order</th><th>Customer</th><th>Date</th><th>Total</th><th>Payment</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $i => $r): ?>
      <tr><td class="sno"><?= sprintf('%02d', $from + $i) ?></td><td class="fw-bold"><?= esc($r['order_no']) ?></td><td><?= esc($r['name']) ?><div class="small text-muted"><?= esc($r['email']) ?></div></td><td class="text-nowrap"><?= date('d M Y, h:i A', strtotime($r['created_at'])) ?></td>
        <td class="fw-bold"><?= money($r['total']) ?></td><td><?= status_badge($r['payment_status']) ?></td><td><?= status_badge($r['status']) ?></td>
        <td class="text-end"><div class="actions"><a class="btn-icon btn-icon-view" title="View" href="<?= base_url('admin/orders/view/' . $r['id']) ?>"><i class="bi bi-eye"></i></a> <a class="btn-icon btn-icon-alt" title="Invoice" target="_blank" href="<?= base_url('admin/orders/invoice/' . $r['id']) ?>"><i class="bi bi-receipt"></i></a></div></td></tr>
    <?php endforeach; if (! $rows): ?><tr><td colspan="8" class="text-center text-muted py-5">No orders found.</td></tr><?php endif; ?>
    </tbody></table></div>
  <?= $this->include('admin/partials/table_footer') ?>
</div>
<?= $this->endSection() ?>
