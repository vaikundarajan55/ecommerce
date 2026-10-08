<?php
/** Customer order table (account dashboard + My orders). Expects: $orders (rows with first_item, item_count, qty). */
// status => [badge tone, icon, progress %]
$steps = [
    'placed'     => ['st-blue',   'bi-bag-check-fill',        25],
    'processing' => ['st-amber',  'bi-clipboard2-pulse-fill', 50],
    'shipped'    => ['st-violet', 'bi-truck',                 75],
    'delivered'  => ['st-green',  'bi-check-circle-fill',     100],
    'cancelled'  => ['st-red',    'bi-x-octagon-fill',        0],
];
$payTone = ['paid' => 'tag-green', 'pending' => 'tag-amber', 'failed' => 'tag-red'];
?>
<div class="order-table table-responsive"><table class="table mb-0">
  <thead><tr><th>Order ID</th><th>Items</th><th>Payment</th><th class="text-center">Total</th><th class="text-center">Status</th><th class="text-center">Progress</th><th class="text-center">Actions</th></tr></thead>
  <tbody>
  <?php foreach ($orders as $i => $o): [$tone, $icon, $pct] = $steps[$o['status']] ?? ['st-blue', 'bi-circle', 0]; $item = $o['first_item'] ?: 'Order'; ?>
    <tr style="--i: <?= $i ?>">
      <td><div class="fw-bold"><?= esc($o['order_no']) ?></div><small class="text-muted-2 text-nowrap"><?= date('d M Y, h:i A', strtotime($o['created_at'])) ?></small></td>
      <td><div class="item-cell"><span class="item-avatar"><?= esc(mb_strtoupper(mb_substr($item, 0, 1))) ?></span>
        <div class="min-w-0"><div class="item-name"><?= esc($item) ?></div><small class="text-muted-2">Qty <?= (int) $o['qty'] ?><?= $o['item_count'] > 1 ? ' · +' . ($o['item_count'] - 1) . ' more' : '' ?></small></div></div></td>
      <td><span class="tag <?= $payTone[$o['payment_status']] ?? '' ?>"><?= esc(ucfirst($o['payment_status'])) ?></span></td>
      <td class="text-center fw-bold amount"><?= money($o['total']) ?></td>
      <td class="text-center"><span class="st-badge <?= $tone ?>"><i class="bi <?= $icon ?>"></i><?= esc(ucfirst($o['status'])) ?></span></td>
      <td class="text-center"><div class="prog"><strong><?= $o['status'] === 'cancelled' ? '—' : $pct . '%' ?></strong><span class="prog-bar"><span style="--w: <?= $pct ?>%"></span></span></div></td>
      <td class="text-center text-nowrap">
        <a class="act-icon" href="<?= base_url('account/orders/' . $o['id']) ?>" title="View order" aria-label="View order"><i class="bi bi-eye-fill"></i></a>
        <a class="act-icon" href="<?= base_url('account/invoice/' . $o['id']) ?>" title="Invoice" aria-label="Invoice"><i class="bi bi-receipt"></i></a>
        <?php if ($o['payment_status'] !== 'paid'): ?><a class="act-icon act-pay" href="<?= base_url('payment/' . $o['order_no']) ?>" title="Pay now" aria-label="Pay now"><i class="bi bi-credit-card-fill"></i></a><?php endif; ?>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table></div>
