<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="row g-3">
  <div class="col-lg-8">
    <div class="panel"><div class="panel-head"><strong>Items in <?= esc($order['order_no']) ?></strong><a class="btn btn-sm btn-brand" href="<?= base_url('admin/orders/invoice/' . $order['id']) ?>" target="_blank"><i class="bi bi-receipt me-1"></i>Invoice</a></div>
      <div class="table-responsive"><table class="table mb-0"><thead><tr><th>Item</th><th>Price</th><th>Qty</th><th class="text-end">Total</th></tr></thead>
        <tbody><?php foreach ($items as $i): ?><tr><td><?= esc($i['name']) ?></td><td><?= money($i['price']) ?></td><td><?= $i['qty'] ?></td><td class="text-end"><?= money($i['total']) ?></td></tr><?php endforeach; ?></tbody>
        <tfoot><tr><td colspan="3" class="text-end">Subtotal</td><td class="text-end"><?= money($order['subtotal']) ?></td></tr><tr><td colspan="3" class="text-end">Shipping</td><td class="text-end"><?= money($order['shipping']) ?></td></tr>
          <tr class="fw-bold"><td colspan="3" class="text-end">Total</td><td class="text-end"><?= money($order['total']) ?></td></tr></tfoot></table></div></div>
  </div>
  <div class="col-lg-4">
    <div class="panel mb-3"><div class="panel-head"><strong>Update status</strong><?= status_badge($order['status']) ?></div><div class="panel-body">
      <form action="<?= base_url('admin/orders/status/' . $order['id']) ?>" method="post" class="d-flex gap-2"><?= csrf_field() ?>
        <select name="status" class="form-select"><?php foreach (['placed', 'processing', 'shipped', 'delivered', 'cancelled'] as $s): ?><option value="<?= $s ?>" <?= $order['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option><?php endforeach; ?></select>
        <button class="btn btn-brand">Save</button></form></div></div>
    <div class="panel mb-3"><div class="panel-head"><strong>Customer</strong></div><div class="panel-body"><?= esc($order['name']) ?><br><?= esc($order['email']) ?><br><?= esc($order['phone']) ?><hr><?= esc($order['address']) ?><br><?= esc($order['city']) ?> - <?= esc($order['pincode']) ?></div></div>
    <div class="panel"><div class="panel-head"><strong>Payment</strong><?= status_badge($order['payment_status']) ?></div><div class="panel-body">Method: <?= esc($order['payment_method']) ?><br>Transaction: <?= esc($order['txn_id'] ?: '—') ?><br>Placed: <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></div></div>
  </div>
</div>
<a href="<?= base_url('admin/orders') ?>" class="btn btn-light mt-3"><i class="bi bi-arrow-left me-1"></i>Back to orders</a>
<?= $this->endSection() ?>
