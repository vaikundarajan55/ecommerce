<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="row g-3 mb-4">
  <?php $cards = [
    ['Revenue (paid)', $stats['revenue'], 'bi-currency-rupee', 'ico-a', '₹', 2],
    ['Orders', $stats['orders'], 'bi-receipt', 'ico-b', '', 0],
    ['Products', $stats['products'], 'bi-box-seam', 'ico-c', '', 0],
    ['Customers', $stats['users'], 'bi-people', 'ico-e', '', 0],
    ['New enquiries', $stats['enquiries'], 'bi-question-circle', 'ico-d', '', 0],
    ['New messages', $stats['contacts'], 'bi-envelope', 'ico-f', '', 0],
  ]; foreach ($cards as $c): ?>
    <div class="col-6 col-xl-4 col-xxl-2"><div class="stat"><div class="ico <?= $c[3] ?>"><i class="bi <?= $c[2] ?>"></i></div>
      <div><div class="text-muted small"><?= $c[0] ?></div><div class="num" data-count="<?= $c[1] ?>" data-prefix="<?= $c[4] ?>" data-dec="<?= $c[5] ?>">0</div></div></div></div>
  <?php endforeach; ?>
</div>

<div class="row g-3">
  <div class="col-xl-8">
    <div class="panel h-100"><div class="panel-head"><strong>Last 7 days</strong><span class="small text-muted">Orders and paid revenue</span></div>
      <div class="panel-body"><canvas id="salesChart" height="110"></canvas></div></div>
  </div>
  <div class="col-xl-4">
    <div class="panel h-100"><div class="panel-head"><strong>Low stock</strong><a href="<?= base_url('admin/products') ?>" class="small">All products</a></div>
      <ul class="list-group list-group-flush">
        <?php foreach ($lowStock as $p): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center"><span><?= esc($p['name']) ?></span><span class="badge <?= $p['stock'] < 1 ? 'text-bg-danger' : 'text-bg-warning' ?>"><?= $p['stock'] ?> left</span></li>
        <?php endforeach; if (! $lowStock): ?><li class="list-group-item text-muted">All products are well stocked.</li><?php endif; ?>
      </ul></div>
  </div>
  <div class="col-12">
    <div class="panel"><div class="panel-head"><strong>Recent orders</strong><a href="<?= base_url('admin/orders') ?>" class="btn btn-sm btn-outline-brand">All orders</a></div>
      <div class="table-responsive"><table class="table table-hover mb-0 row-anim">
        <thead><tr><th>Order</th><th>Customer</th><th>Date</th><th>Total</th><th>Payment</th><th>Status</th><th></th></tr></thead>
        <tbody>
          <?php foreach ($recent as $o): ?>
            <tr><td class="fw-bold"><?= esc($o['order_no']) ?></td><td><?= esc($o['name']) ?></td><td><?= date('d M Y', strtotime($o['created_at'])) ?></td><td><?= money($o['total']) ?></td><td><?= status_badge($o['payment_status']) ?></td><td><?= status_badge($o['status']) ?></td>
            <td class="text-end"><a class="btn btn-sm btn-outline-brand" href="<?= base_url('admin/orders/view/' . $o['id']) ?>">View</a></td></tr>
          <?php endforeach; if (! $recent): ?><tr><td colspan="7" class="text-center text-muted py-4">No orders yet.</td></tr><?php endif; ?>
        </tbody></table></div></div>
  </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
const d = <?= json_encode($chart) ?>;
new Chart(document.getElementById('salesChart'), {
  data: { labels: d.labels, datasets: [
    { type: 'bar', label: 'Orders', data: d.orders, backgroundColor: '#f5a524', borderRadius: 6, yAxisID: 'y' },
    { type: 'line', label: 'Revenue (₹)', data: d.revenue, borderColor: '#0b6e6e', backgroundColor: 'rgba(11,110,110,.12)', fill: true, tension: .35, yAxisID: 'y1' }
  ]},
  options: { animation: { duration: 1200, easing: 'easeOutQuart' }, interaction: { mode: 'index', intersect: false },
    scales: { y: { beginAtZero: true, ticks: { precision: 0 }, title: { display: true, text: 'Orders' } }, y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false }, title: { display: true, text: 'Revenue' } } } }
});
</script>
<?= $this->endSection() ?>
