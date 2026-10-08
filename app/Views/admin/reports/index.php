<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<?php $months = array_map(static fn ($m) => date('F', mktime(0, 0, 0, $m, 1)), array_combine(range(1, 12), range(1, 12))); ?>

<div class="panel mb-4">
  <form class="panel-body report-filter" method="get">
    <div><label class="form-label small text-muted mb-1" for="fYear">Year</label>
      <select class="form-select form-select-sm" id="fYear" name="year">
        <option value="0" <?= $year === 0 ? 'selected' : '' ?>>All years</option>
        <?php foreach ($years as $y): ?><option value="<?= $y ?>" <?= $year === $y ? 'selected' : '' ?>><?= $y ?></option><?php endforeach; ?>
      </select></div>
    <div><label class="form-label small text-muted mb-1" for="fMonth">Month</label>
      <select class="form-select form-select-sm" id="fMonth" name="month" <?= $year === 0 ? 'disabled' : '' ?>>
        <option value="0">All months</option>
        <?php foreach ($months as $m => $name): ?><option value="<?= $m ?>" <?= $month === $m ? 'selected' : '' ?>><?= $name ?></option><?php endforeach; ?>
      </select></div>
    <div class="d-flex gap-2 align-items-end">
      <button class="btn btn-brand btn-sm"><i class="bi bi-funnel me-1"></i>Apply</button>
      <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('admin/reports') ?>">Reset</a>
      <a class="btn btn-outline-brand btn-sm" href="<?= base_url('admin/reports/export') . '?' . http_build_query(['year' => $year, 'month' => $month]) ?>"><i class="bi bi-download me-1"></i>Export CSV</a>
    </div>
    <div class="ms-auto align-self-end fw-bold"><i class="bi bi-calendar3 me-1 text-muted"></i><?= esc($periodLabel) ?></div>
  </form>
</div>

<div class="row g-3 mb-4">
  <?php $cards = [
    ['Revenue (paid)', $sum['revenue'], 'bi-currency-rupee', 'ico-a', '₹', 2],
    ['Orders', $sum['orders'], 'bi-receipt', 'ico-b', '', 0],
    ['Avg. order value', $sum['avg'], 'bi-graph-up-arrow', 'ico-c', '₹', 2],
    ['Items sold', $sum['items'], 'bi-box-seam', 'ico-e', '', 0],
    ['New customers', $sum['newUsers'], 'bi-person-plus', 'ico-d', '', 0],
    ['Cancelled', $sum['cancelled'], 'bi-x-circle', 'ico-f', '', 0],
  ]; foreach ($cards as $c): ?>
    <div class="col-6 col-xl-4 col-xxl-2"><div class="stat"><div class="ico <?= $c[3] ?>"><i class="bi <?= $c[2] ?>"></i></div>
      <div><div class="text-muted small"><?= $c[0] ?></div><div class="num" data-count="<?= (float) $c[1] ?>" data-prefix="<?= $c[4] ?>" data-dec="<?= $c[5] ?>">0</div></div></div></div>
  <?php endforeach; ?>
</div>

<div class="row g-3 mb-4">
  <div class="col-xl-8">
    <div class="panel h-100"><div class="panel-head"><strong><?= $bucketName ?>-wise sales</strong><span class="small text-muted">Orders and paid revenue · <?= esc($periodLabel) ?></span></div>
      <div class="panel-body"><canvas id="reportChart" height="120"></canvas></div></div>
  </div>
  <div class="col-xl-4">
    <div class="panel h-100"><div class="panel-head"><strong>Order status</strong></div>
      <ul class="list-group list-group-flush">
        <?php foreach ($statusCounts as $s => $c): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center"><?= status_badge($s) ?><span class="fw-bold"><?= $c ?></span></li>
        <?php endforeach; ?>
      </ul></div>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-xl-7">
    <div class="panel h-100"><div class="panel-head"><strong><?= $bucketName ?>-wise summary</strong></div>
      <div class="table-responsive report-scroll"><table class="table table-hover table-sm mb-0">
        <thead><tr><th><?= $bucketName ?></th><th class="text-end">Orders</th><th class="text-end">Paid</th><th class="text-end">Cancelled</th><th class="text-end">Revenue</th></tr></thead>
        <tbody>
        <?php foreach ($breakdown as $b): ?>
          <tr class="<?= $b['orders'] ? '' : 'text-muted' ?>"><td><?= esc($b['label']) ?></td><td class="text-end"><?= (int) $b['orders'] ?></td><td class="text-end"><?= (int) $b['paid_orders'] ?></td><td class="text-end"><?= (int) $b['cancelled'] ?></td><td class="text-end"><?= money($b['revenue']) ?></td></tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot><tr class="fw-bold"><td>Total</td><td class="text-end"><?= (int) $sum['orders'] ?></td><td class="text-end"><?= (int) $sum['paid_orders'] ?></td><td class="text-end"><?= (int) $sum['cancelled'] ?></td><td class="text-end"><?= money($sum['revenue']) ?></td></tr></tfoot>
      </table></div></div>
  </div>
  <div class="col-xl-5">
    <div class="panel h-100"><div class="panel-head"><strong>Top selling products</strong><span class="small text-muted">Paid orders</span></div>
      <div class="table-responsive"><table class="table table-sm mb-0">
        <thead><tr><th>#</th><th>Product</th><th class="text-end">Qty</th><th class="text-end">Amount</th></tr></thead>
        <tbody>
        <?php foreach ($topProducts as $i => $p): ?>
          <tr><td><?= $i + 1 ?></td><td><?= esc($p['name']) ?></td><td class="text-end"><?= (int) $p['qty'] ?></td><td class="text-end"><?= money($p['amount']) ?></td></tr>
        <?php endforeach; if (! $topProducts): ?><tr><td colspan="4" class="text-center text-muted py-4">No sales in this period.</td></tr><?php endif; ?>
        </tbody></table></div></div>
  </div>
</div>

<div class="panel list-card">
  <?= view('admin/partials/list_head', ['heading' => 'Orders · ' . $periodLabel, 'sub' => 'Every order placed in the selected period', 'toolbar' => view('admin/partials/table_toolbar', compact('perPage', 'q') + ['placeholder' => 'Order no, name, email, phone', 'keep' => ['year' => $year, 'month' => $month]])]) ?>
  <div class="list-table table-responsive"><table class="table mb-0 row-anim">
    <thead><tr><th>S. No</th><th>Order</th><th>Customer</th><th>Date</th><th>Total</th><th>Payment</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $i => $o): ?>
      <tr><td class="sno"><?= sprintf('%02d', $from + $i) ?></td><td class="fw-bold"><?= esc($o['order_no']) ?></td><td><?= esc($o['name']) ?><div class="small text-muted"><?= esc($o['email']) ?></div></td><td class="text-nowrap"><?= date('d M Y, h:i A', strtotime($o['created_at'])) ?></td>
        <td class="fw-bold"><?= money($o['total']) ?></td><td><?= status_badge($o['payment_status']) ?></td><td><?= status_badge($o['status']) ?></td>
        <td class="text-end"><div class="actions"><a class="btn-icon btn-icon-view" title="View" href="<?= base_url('admin/orders/view/' . $o['id']) ?>"><i class="bi bi-eye"></i></a></div></td></tr>
    <?php endforeach; if (! $rows): ?><tr><td colspan="8" class="text-center text-muted py-5">No orders in this period.</td></tr><?php endif; ?>
    </tbody></table></div>
  <?= $this->include('admin/partials/table_footer') ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
document.getElementById('fYear').addEventListener('change', e => { const m = document.getElementById('fMonth'); m.disabled = e.target.value === '0'; if (m.disabled) m.value = '0'; });
const r = <?= json_encode(['labels' => array_column($breakdown, 'label'), 'orders' => array_column($breakdown, 'orders'), 'revenue' => array_column($breakdown, 'revenue')]) ?>;
new Chart(document.getElementById('reportChart'), {
  data: { labels: r.labels, datasets: [
    { type: 'bar', label: 'Orders', data: r.orders, backgroundColor: '#f5a524', borderRadius: 6, yAxisID: 'y' },
    { type: 'line', label: 'Revenue (₹)', data: r.revenue, borderColor: '#0b6e6e', backgroundColor: 'rgba(11,110,110,.12)', fill: true, tension: .35, yAxisID: 'y1' }
  ]},
  options: { animation: { duration: 1000, easing: 'easeOutQuart' }, interaction: { mode: 'index', intersect: false },
    scales: { y: { beginAtZero: true, ticks: { precision: 0 }, title: { display: true, text: 'Orders' } }, y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false }, title: { display: true, text: 'Revenue' } } } }
});
</script>
<?= $this->endSection() ?>
