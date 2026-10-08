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
  ]; foreach ($cards as $i => $c): ?>
    <div class="col-6 col-xl-4 col-xxl-2 reveal" style="--rd: <?= $i * 70 ?>ms"><div class="stat"><div class="ico <?= $c[3] ?>"><i class="bi <?= $c[2] ?>"></i></div>
      <div><div class="text-muted small"><?= $c[0] ?></div><div class="num" data-count="<?= $c[1] ?>" data-prefix="<?= $c[4] ?>" data-dec="<?= $c[5] ?>">0</div></div></div></div>
  <?php endforeach; ?>
</div>

<?php
  // % change vs the previous 7 days
  $trend = static function (float $now, float $before): array {
      if ($before == 0) {
          return $now > 0 ? ['up', 'New'] : ['flat', '0%'];
      }
      $pct = round(($now - $before) / $before * 100);
      return [$pct > 0 ? 'up' : ($pct < 0 ? 'down' : 'flat'), ($pct > 0 ? '+' : '') . $pct . '%'];
  };
  [$oDir, $oPct] = $trend($week['orders'], $week['prevOrders']);
  [$rDir, $rPct] = $trend($week['revenue'], $week['prevRevenue']);
  $statusTotal   = array_sum($statusCounts);
  $statusColors  = ['placed' => '#3b82f6', 'processing' => '#06b6d4', 'shipped' => '#f59e0b', 'delivered' => '#10b981', 'cancelled' => '#ef4444'];
?>
<div class="row g-3 mb-4">
  <div class="col-xl-8 reveal" style="--rd: 120ms">
    <div class="panel chart-card h-100">
      <div class="chart-head">
        <div><h2 class="list-title">Sales overview</h2><p class="list-sub">Orders and paid revenue · last 7 days</p></div>
        <div class="chart-kpis">
          <div class="kpi"><span class="kpi-dot" style="--c:#3b82f6"></span><div><small>Orders</small><strong data-count="<?= $week['orders'] ?>">0</strong></div><span class="trend trend-<?= $oDir ?>"><i class="bi bi-arrow-<?= $oDir === 'down' ? 'down' : ($oDir === 'up' ? 'up' : 'right') ?>-short"></i><?= $oPct ?></span></div>
          <div class="kpi"><span class="kpi-dot" style="--c:#10b981"></span><div><small>Revenue</small><strong data-count="<?= $week['revenue'] ?>" data-prefix="₹" data-dec="0">0</strong></div><span class="trend trend-<?= $rDir ?>"><i class="bi bi-arrow-<?= $rDir === 'down' ? 'down' : ($rDir === 'up' ? 'up' : 'right') ?>-short"></i><?= $rPct ?></span></div>
        </div>
      </div>
      <div class="chart-box"><canvas id="salesChart"></canvas></div>
      <p class="small text-muted mb-0 mt-2"><i class="bi bi-info-circle me-1"></i>Change is compared with the 7 days before.</p>
    </div>
  </div>
  <div class="col-xl-4 reveal" style="--rd: 200ms">
    <div class="panel chart-card h-100">
      <div class="chart-head"><div><h2 class="list-title">Order status</h2><p class="list-sub">All orders by delivery stage</p></div></div>
      <div class="donut-box">
        <canvas id="statusChart"></canvas>
        <div class="donut-center"><strong data-count="<?= $statusTotal ?>">0</strong><small>Orders</small></div>
      </div>
      <ul class="status-legend">
        <?php foreach ($statusCounts as $s => $c): ?>
          <li><span class="kpi-dot" style="--c:<?= $statusColors[$s] ?>"></span><?= ucfirst($s) ?>
            <span class="legend-bar"><span style="--w: <?= $statusTotal ? round($c / $statusTotal * 100) : 0 ?>%; --c: <?= $statusColors[$s] ?>"></span></span><strong><?= $c ?></strong></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-xl-8 reveal" style="--rd: 120ms">
    <div class="panel list-card h-100">
      <?= view('admin/partials/list_head', ['heading' => 'Recent orders', 'sub' => 'The latest orders placed in the shop', 'toolbar' => '', 'action' => '<a href="' . base_url('admin/orders') . '" class="btn btn-grad">All orders<i class="bi bi-arrow-right ms-2"></i></a>']) ?>
      <div class="list-table table-responsive"><table class="table mb-0 table-reveal">
        <thead><tr><th>Order</th><th>Customer</th><th>Date</th><th>Total</th><th>Payment</th><th>Status</th><th class="text-end">View</th></tr></thead>
        <tbody>
          <?php foreach ($recent as $o): $ini = strtoupper(implode('', array_map(static fn ($w) => $w[0] ?? '', array_slice(preg_split('/\s+/', trim($o['name'])), 0, 2)))); ?>
            <tr><td class="fw-bold"><?= esc($o['order_no']) ?></td>
              <td><div class="d-flex align-items-center gap-2"><span class="mini-avatar" style="--h: <?= crc32($o['name']) % 360 ?>"><?= esc($ini) ?></span><div><?= esc($o['name']) ?><div class="small text-muted"><?= esc($o['email']) ?></div></div></div></td>
              <td class="text-nowrap"><?= date('d M Y', strtotime($o['created_at'])) ?></td><td class="fw-bold"><?= money($o['total']) ?></td><td><?= status_badge($o['payment_status']) ?></td><td><?= status_badge($o['status']) ?></td>
              <td class="text-end"><a class="btn-icon btn-icon-view" title="View order" href="<?= base_url('admin/orders/view/' . $o['id']) ?>"><i class="bi bi-eye"></i></a></td></tr>
          <?php endforeach; if (! $recent): ?><tr><td colspan="7" class="text-center text-muted py-5"><i class="bi bi-inbox fs-2 d-block mb-2"></i>No orders yet.</td></tr><?php endif; ?>
        </tbody></table></div>
    </div>
  </div>
  <div class="col-xl-4 reveal" style="--rd: 200ms">
    <div class="panel list-card h-100">
      <?= view('admin/partials/list_head', ['heading' => 'Low stock', 'sub' => 'Products with 5 or fewer left', 'toolbar' => '', 'action' => '<a href="' . base_url('admin/products') . '" class="btn-icon btn-icon-view" title="All products"><i class="bi bi-box-seam"></i></a>']) ?>
      <ul class="stock-list">
        <?php foreach ($lowStock as $p): ?>
          <li>
            <img class="thumb-sm" src="<?= img_url($p['image']) ?>" alt="">
            <div class="flex-grow-1 min-w-0">
              <div class="d-flex justify-content-between gap-2"><span class="fw-bold text-truncate"><?= esc($p['name']) ?></span><span class="pill <?= $p['stock'] < 1 ? 'pill-red' : 'pill-amber' ?>"><?= $p['stock'] < 1 ? 'Out of stock' : $p['stock'] . ' left' ?></span></div>
              <span class="stock-bar"><span style="--w: <?= max(4, min(100, $p['stock'] * 20)) ?>%" class="<?= $p['stock'] < 1 ? 'is-out' : '' ?>"></span></span>
            </div>
          </li>
        <?php endforeach; if (! $lowStock): ?><li class="justify-content-center text-muted py-4"><i class="bi bi-check2-circle me-2 text-success"></i>All products are well stocked.</li><?php endif; ?>
      </ul>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
(() => {
  const d = <?= json_encode($chart) ?>;
  const status = <?= json_encode(['labels' => array_map('ucfirst', array_keys($statusCounts)), 'data' => array_values($statusCounts), 'colors' => array_values($statusColors)]) ?>;
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  Chart.defaults.font.family = "'Nunito Sans', system-ui, sans-serif";
  Chart.defaults.color = '#66767f';

  // Sales chart: bars grow one after another, revenue line draws itself left to right
  const ctx = document.getElementById('salesChart').getContext('2d');
  const barGrad = ctx.createLinearGradient(0, 0, 0, 320);
  barGrad.addColorStop(0, '#3b82f6'); barGrad.addColorStop(1, '#06b6d4');
  const fillGrad = ctx.createLinearGradient(0, 0, 0, 320);
  fillGrad.addColorStop(0, 'rgba(16,185,129,.32)'); fillGrad.addColorStop(1, 'rgba(16,185,129,0)');
  const step = 140, n = d.labels.length;
  const lineAnim = {
    x: { type: 'number', easing: 'linear', duration: step, from: NaN, delay: c => (c.type !== 'data' || c.xStarted) ? 0 : (c.xStarted = true, c.index * step) },
    y: { type: 'number', easing: 'linear', duration: step, from: c => c.index === 0 ? c.chart.scales.y1.getPixelForValue(0) : c.chart.getDatasetMeta(c.datasetIndex).data[c.index - 1].getProps(['y'], true).y,
         delay: c => (c.type !== 'data' || c.yStarted) ? 0 : (c.yStarted = true, c.index * step) }
  };
  new Chart(ctx, {
    data: { labels: d.labels, datasets: [
      { type: 'bar', label: 'Orders', data: d.orders, backgroundColor: barGrad, hoverBackgroundColor: '#1d4ed8', borderRadius: 10, borderSkipped: false, maxBarThickness: 38, yAxisID: 'y', order: 2,
        animation: reduce ? false : { duration: 900, easing: 'easeOutBack', delay: c => c.type === 'data' ? c.dataIndex * 110 : 0 } },
      { type: 'line', label: 'Revenue (₹)', data: d.revenue, borderColor: '#10b981', borderWidth: 3, backgroundColor: fillGrad, fill: true, tension: .4, yAxisID: 'y1', order: 1,
        pointRadius: 5, pointHoverRadius: 8, pointBackgroundColor: '#fff', pointBorderColor: '#10b981', pointBorderWidth: 3,
        animations: reduce ? false : lineAnim }
    ]},
    options: {
      responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false },
      animation: reduce ? false : { duration: step * n },
      plugins: {
        legend: { position: 'top', align: 'end', labels: { usePointStyle: true, pointStyle: 'circle', boxWidth: 8, padding: 18, font: { weight: 700 } } },
        tooltip: { backgroundColor: '#0f1b2d', padding: 12, cornerRadius: 12, titleFont: { weight: 800 }, boxPadding: 6, usePointStyle: true,
          callbacks: { label: c => c.dataset.yAxisID === 'y1' ? ' Revenue: ₹' + c.parsed.y.toLocaleString('en-IN') : ' Orders: ' + c.parsed.y } }
      },
      scales: {
        x: { grid: { display: false }, border: { display: false }, ticks: { font: { weight: 700 } } },
        y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: 'rgba(30,50,90,.06)' }, border: { display: false } },
        y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false }, border: { display: false }, ticks: { callback: v => '₹' + Number(v).toLocaleString('en-IN') } }
      }
    }
  });

  // Status doughnut: spins and grows in
  const empty = status.data.every(v => v === 0);
  new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: { labels: empty ? ['No orders'] : status.labels, datasets: [{ data: empty ? [1] : status.data, backgroundColor: empty ? ['#e5ebef'] : status.colors, borderWidth: 0, hoverOffset: 10, spacing: empty ? 0 : 3, borderRadius: 8 }] },
    options: { cutout: '72%', responsive: true, maintainAspectRatio: false,
      animation: reduce ? false : { animateRotate: true, animateScale: true, duration: 1400, easing: 'easeOutQuart' },
      plugins: { legend: { display: false }, tooltip: { enabled: !empty, backgroundColor: '#0f1b2d', padding: 10, cornerRadius: 10 } } }
  });
})();
</script>
<?= $this->endSection() ?>
