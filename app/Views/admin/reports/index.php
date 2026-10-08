<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<?php
  $months       = array_map(static fn ($m) => date('F', mktime(0, 0, 0, $m, 1)), array_combine(range(1, 12), range(1, 12)));
  $statusTotal  = array_sum($statusCounts);
  $statusColors = ['placed' => '#3b82f6', 'processing' => '#06b6d4', 'shipped' => '#f59e0b', 'delivered' => '#10b981', 'cancelled' => '#ef4444'];
  $maxRevenue   = max(array_column($breakdown, 'revenue') ?: [0]);
  $maxQty       = max(array_column($topProducts, 'qty') ?: [0]);
?>

<div class="panel chart-card report-filter-card mb-4 reveal">
  <form class="report-filter" method="get">
    <div class="report-period"><span class="hero-icon"><i class="bi bi-bar-chart-line"></i></span>
      <div><small>Report period</small><strong><?= esc($periodLabel) ?></strong></div></div>
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
    <div class="d-flex gap-2 align-items-end flex-wrap">
      <button class="btn btn-brand btn-sm"><i class="bi bi-funnel me-1"></i>Apply</button>
      <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('admin/reports') ?>"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</a>
      <a class="btn btn-outline-brand btn-sm" href="<?= base_url('admin/reports/export') . '?' . http_build_query(['year' => $year, 'month' => $month]) ?>"><i class="bi bi-download me-1"></i>Export CSV</a>
    </div>
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
  ]; foreach ($cards as $i => $c): ?>
    <div class="col-6 col-xl-4 col-xxl-2 reveal" style="--rd: <?= $i * 70 ?>ms"><div class="stat"><div class="ico <?= $c[3] ?>"><i class="bi <?= $c[2] ?>"></i></div>
      <div><div class="text-muted small"><?= $c[0] ?></div><div class="num" data-count="<?= (float) $c[1] ?>" data-prefix="<?= $c[4] ?>" data-dec="<?= $c[5] ?>">0</div></div></div></div>
  <?php endforeach; ?>
</div>

<div class="row g-3 mb-4">
  <div class="col-xl-8 reveal" style="--rd: 120ms">
    <div class="panel chart-card h-100">
      <div class="chart-head">
        <div><h2 class="list-title"><?= $bucketName ?>-wise sales</h2><p class="list-sub">Orders and paid revenue · <?= esc($periodLabel) ?></p></div>
        <div class="chart-kpis">
          <div class="kpi"><span class="kpi-dot" style="--c:#3b82f6"></span><div><small>Orders</small><strong data-count="<?= (int) $sum['orders'] ?>">0</strong></div></div>
          <div class="kpi"><span class="kpi-dot" style="--c:#10b981"></span><div><small>Revenue</small><strong data-count="<?= (float) $sum['revenue'] ?>" data-prefix="₹" data-dec="0">0</strong></div></div>
        </div>
      </div>
      <div class="chart-box"><canvas id="reportChart"></canvas></div>
    </div>
  </div>
  <div class="col-xl-4 reveal" style="--rd: 200ms">
    <div class="panel chart-card h-100">
      <div class="chart-head"><div><h2 class="list-title">Order status</h2><p class="list-sub">Orders by delivery stage</p></div></div>
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

<div class="row g-3 mb-4">
  <div class="col-xl-7 reveal" style="--rd: 120ms">
    <div class="panel chart-card report-card h-100">
      <div class="chart-head"><div><h2 class="list-title"><?= $bucketName ?>-wise summary</h2><p class="list-sub">Busiest <?= strtolower($bucketName) ?> is marked with a star</p></div></div>
      <div class="table-responsive report-scroll"><table class="table table-hover table-sm mb-0 table-reveal fast">
        <thead><tr><th><?= $bucketName ?></th><th class="text-end">Orders</th><th class="text-end">Paid</th><th class="text-end">Cancelled</th><th class="rev-col">Revenue</th></tr></thead>
        <tbody>
        <?php foreach ($breakdown as $b): $peak = $maxRevenue > 0 && $b['revenue'] == $maxRevenue; ?>
          <tr class="<?= $b['orders'] ? '' : 'is-empty' ?> <?= $peak ? 'is-peak' : '' ?>">
            <td class="fw-bold"><?= esc($b['label']) ?><?= $peak ? ' <i class="bi bi-star-fill peak-star"></i>' : '' ?></td>
            <td class="text-end"><?= (int) $b['orders'] ?></td>
            <td class="text-end"><?= (int) $b['paid_orders'] ? '<span class="pill pill-green">' . (int) $b['paid_orders'] . '</span>' : 0 ?></td>
            <td class="text-end"><?= (int) $b['cancelled'] ? '<span class="pill pill-red">' . (int) $b['cancelled'] . '</span>' : 0 ?></td>
            <td class="rev-col"><span class="rev-cell"><span class="legend-bar"><span style="--w: <?= $maxRevenue > 0 ? max(2, round($b['revenue'] / $maxRevenue * 100)) : 0 ?>%; --c: linear-gradient(90deg, #06b6d4, #10b981)"></span></span><b><?= money($b['revenue']) ?></b></span></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot><tr class="fw-bold"><td>Total</td><td class="text-end"><?= (int) $sum['orders'] ?></td><td class="text-end"><?= (int) $sum['paid_orders'] ?></td><td class="text-end"><?= (int) $sum['cancelled'] ?></td><td class="text-end"><?= money($sum['revenue']) ?></td></tr></tfoot>
      </table></div>
    </div>
  </div>
  <div class="col-xl-5 reveal" style="--rd: 200ms">
    <div class="panel chart-card report-card h-100">
      <div class="chart-head"><div><h2 class="list-title">Top selling products</h2><p class="list-sub">By quantity · paid orders</p></div></div>
      <ul class="top-list">
        <?php foreach ($topProducts as $i => $p): ?>
          <li style="--i: <?= $i ?>">
            <span class="rank rank-<?= min($i + 1, 4) ?>"><?= $i < 3 ? '<i class="bi bi-trophy-fill"></i>' : $i + 1 ?></span>
            <div class="flex-grow-1 min-w-0">
              <div class="d-flex justify-content-between gap-2"><span class="fw-bold text-truncate"><?= esc($p['name']) ?></span><span class="fw-bold text-nowrap"><?= money($p['amount']) ?></span></div>
              <div class="d-flex align-items-center gap-2 mt-1"><span class="stock-bar flex-grow-1 mt-0"><span class="top-bar" style="--w: <?= $maxQty ? max(4, round($p['qty'] / $maxQty * 100)) : 0 ?>%"></span></span><small class="text-muted fw-bold text-nowrap"><?= (int) $p['qty'] ?> sold</small></div>
            </div>
          </li>
        <?php endforeach; if (! $topProducts): ?><li class="justify-content-center text-muted py-4"><i class="bi bi-bag-x me-2"></i>No sales in this period.</li><?php endif; ?>
      </ul>
    </div>
  </div>
</div>

<div class="panel list-card reveal">
  <?= view('admin/partials/list_head', ['heading' => 'Orders · ' . $periodLabel, 'sub' => 'Every order placed in the selected period', 'toolbar' => view('admin/partials/table_toolbar', compact('perPage', 'q') + ['placeholder' => 'Order no, name, email, phone', 'keep' => ['year' => $year, 'month' => $month]])]) ?>
  <div class="list-table table-responsive"><table class="table mb-0 table-reveal">
    <thead><tr><th>S. No</th><th>Order</th><th>Customer</th><th>Date</th><th>Total</th><th>Payment</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $i => $o): $ini = strtoupper(implode('', array_map(static fn ($w) => $w[0] ?? '', array_slice(preg_split('/\s+/', trim($o['name'])), 0, 2)))); ?>
      <tr><td class="sno"><?= sprintf('%02d', $from + $i) ?></td><td class="fw-bold"><?= esc($o['order_no']) ?></td>
        <td><div class="d-flex align-items-center gap-2"><span class="mini-avatar" style="--h: <?= crc32($o['name']) % 360 ?>"><?= esc($ini) ?></span><div><?= esc($o['name']) ?><div class="small text-muted"><?= esc($o['email']) ?></div></div></div></td>
        <td class="text-nowrap"><?= date('d M Y, h:i A', strtotime($o['created_at'])) ?></td>
        <td class="fw-bold"><?= money($o['total']) ?></td><td><?= status_badge($o['payment_status']) ?></td><td><?= status_badge($o['status']) ?></td>
        <td class="text-end"><div class="actions"><a class="btn-icon btn-icon-view" title="View" href="<?= base_url('admin/orders/view/' . $o['id']) ?>"><i class="bi bi-eye"></i></a></div></td></tr>
    <?php endforeach; if (! $rows): ?><tr><td colspan="8" class="text-center text-muted py-5"><i class="bi bi-inbox fs-2 d-block mb-2"></i>No orders in this period.</td></tr><?php endif; ?>
    </tbody></table></div>
  <?= $this->include('admin/partials/table_footer') ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
document.getElementById('fYear').addEventListener('change', e => { const m = document.getElementById('fMonth'); m.disabled = e.target.value === '0'; if (m.disabled) m.value = '0'; });
(() => {
  const r = <?= json_encode(['labels' => array_column($breakdown, 'label'), 'orders' => array_column($breakdown, 'orders'), 'revenue' => array_column($breakdown, 'revenue')]) ?>;
  const status = <?= json_encode(['labels' => array_map('ucfirst', array_keys($statusCounts)), 'data' => array_values($statusCounts), 'colors' => array_values($statusColors)]) ?>;
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  Chart.defaults.font.family = "'Nunito Sans', system-ui, sans-serif";
  Chart.defaults.color = '#66767f';

  // Sales chart: bars rise one after another, revenue line draws itself left to right
  const ctx = document.getElementById('reportChart').getContext('2d');
  const barGrad = ctx.createLinearGradient(0, 0, 0, 320);
  barGrad.addColorStop(0, '#3b82f6'); barGrad.addColorStop(1, '#06b6d4');
  const fillGrad = ctx.createLinearGradient(0, 0, 0, 320);
  fillGrad.addColorStop(0, 'rgba(16,185,129,.32)'); fillGrad.addColorStop(1, 'rgba(16,185,129,0)');
  const n = r.labels.length, step = Math.min(140, 1800 / Math.max(n, 1));
  const lineAnim = {
    x: { type: 'number', easing: 'linear', duration: step, from: NaN, delay: c => (c.type !== 'data' || c.xStarted) ? 0 : (c.xStarted = true, c.index * step) },
    y: { type: 'number', easing: 'linear', duration: step, from: c => c.index === 0 ? c.chart.scales.y1.getPixelForValue(0) : c.chart.getDatasetMeta(c.datasetIndex).data[c.index - 1].getProps(['y'], true).y,
         delay: c => (c.type !== 'data' || c.yStarted) ? 0 : (c.yStarted = true, c.index * step) }
  };
  const salesChart = () => new Chart(ctx, {
    data: { labels: r.labels, datasets: [
      { type: 'bar', label: 'Orders', data: r.orders, backgroundColor: barGrad, hoverBackgroundColor: '#1d4ed8', borderRadius: 10, borderSkipped: false, maxBarThickness: 38, yAxisID: 'y', order: 2,
        animation: reduce ? false : { duration: 900, easing: 'easeOutBack', delay: c => c.type === 'data' ? c.dataIndex * step * .8 : 0 } },
      { type: 'line', label: 'Revenue (₹)', data: r.revenue, borderColor: '#10b981', borderWidth: 3, backgroundColor: fillGrad, fill: true, tension: .4, yAxisID: 'y1', order: 1,
        pointRadius: n > 15 ? 3 : 5, pointHoverRadius: 8, pointBackgroundColor: '#fff', pointBorderColor: '#10b981', pointBorderWidth: 3,
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
  const donut = () => new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: { labels: empty ? ['No orders'] : status.labels, datasets: [{ data: empty ? [1] : status.data, backgroundColor: empty ? ['#e5ebef'] : status.colors, borderWidth: 0, hoverOffset: 10, spacing: empty ? 0 : 3, borderRadius: 8 }] },
    options: { cutout: '72%', responsive: true, maintainAspectRatio: false,
      animation: reduce ? false : { animateRotate: true, animateScale: true, duration: 1400, easing: 'easeOutQuart' },
      plugins: { legend: { display: false }, tooltip: { enabled: !empty, backgroundColor: '#0f1b2d', padding: 10, cornerRadius: 10 } } }
  });

  // Start each chart's animation only once its card scrolls into view
  const whenVisible = (el, fn) => {
    if (!('IntersectionObserver' in window)) return fn();
    const io = new IntersectionObserver(es => { if (es[0].isIntersecting) { io.disconnect(); fn(); } }, { threshold: .2 });
    io.observe(el);
  };
  whenVisible(document.getElementById('reportChart'), salesChart);
  whenVisible(document.getElementById('statusChart'), donut);
})();
</script>
<?= $this->endSection() ?>
