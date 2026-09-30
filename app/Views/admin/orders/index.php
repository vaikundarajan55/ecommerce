<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="panel">
  <div class="panel-head"><strong>Order list</strong>
    <form class="d-flex gap-2" method="get">
      <select name="status" class="form-select form-select-sm" onchange="this.form.submit()"><option value="">All statuses</option>
        <?php foreach (['placed', 'processing', 'shipped', 'delivered', 'cancelled'] as $s): ?><option value="<?= $s ?>" <?= $status === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option><?php endforeach; ?></select>
      <input class="form-control form-control-sm" name="q" value="<?= esc($q) ?>" placeholder="Order no, name, email"><button class="btn btn-sm btn-outline-brand"><i class="bi bi-search"></i></button></form></div>
  <div class="table-responsive"><table class="table table-hover mb-0 row-anim">
    <thead><tr><th>Order</th><th>Customer</th><th>Date</th><th>Total</th><th>Payment</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $o): ?>
      <tr><td class="fw-bold"><?= esc($o['order_no']) ?></td><td><?= esc($o['name']) ?><div class="small text-muted"><?= esc($o['email']) ?></div></td><td><?= date('d M Y, h:i A', strtotime($o['created_at'])) ?></td>
        <td><?= money($o['total']) ?></td><td><?= status_badge($o['payment_status']) ?></td><td><?= status_badge($o['status']) ?></td>
        <td class="text-end text-nowrap"><a class="btn btn-sm btn-outline-brand" href="<?= base_url('admin/orders/view/' . $o['id']) ?>"><i class="bi bi-eye"></i></a> <a class="btn btn-sm btn-brand" href="<?= base_url('admin/orders/invoice/' . $o['id']) ?>" target="_blank"><i class="bi bi-receipt"></i> Invoice</a></td></tr>
    <?php endforeach; if (! $rows): ?><tr><td colspan="7" class="text-center text-muted py-4">No orders found.</td></tr><?php endif; ?>
    </tbody></table></div>
  <div class="p-3"><?= $pager->links('default', 'bootstrap_full') ?></div>
</div>
<?= $this->endSection() ?>
