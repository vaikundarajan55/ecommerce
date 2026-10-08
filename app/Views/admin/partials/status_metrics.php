<?php
/** Total / active / inactive summary cards shown above admin list cards. Expects: $label (e.g. "Category"), $plural (e.g. "categories"), $stats (from AdminBase::statusCounts). */
$pct   = static fn (int $n) => $stats['total'] ? round($n / $stats['total'] * 100) : 0;
$cards = [
    ['m-blue',  'Total ' . $plural,     $stats['total'],    'Complete registry records', 'bi-ui-checks-grid',      100],
    ['m-green', 'Active entries',      $stats['active'],   'Currently live & verified', 'bi-bar-chart-line-fill', $pct($stats['active'])],
    ['m-amber', 'Archived / inactive', $stats['inactive'], 'Deactivated records',       'bi-archive-fill',        $pct($stats['inactive'])],
];
?>
<section class="panel metrics-card reveal">
  <h2 class="list-title"><?= esc($label) ?> status metrics</h2>
  <p class="list-sub">Summary of system-wide records and registry items</p>
  <div class="row g-3 mt-1">
    <?php foreach ($cards as $i => [$tone, $title, $num, $caption, $icon, $fill]): ?>
      <div class="col-md-4">
        <div class="metric <?= $tone ?>" style="--rd: <?= $i * 90 ?>ms">
          <div class="metric-body">
            <span class="metric-label"><?= esc($title) ?></span>
            <strong class="metric-num" data-count="<?= $num ?>"><?= $num ?></strong>
            <span class="metric-caption"><?= esc($caption) ?></span>
            <span class="metric-bar"><span style="--w: <?= $fill ?>%"></span></span>
          </div>
          <span class="metric-ico"><i class="bi <?= $icon ?>"></i></span>
          <span class="metric-dot"></span>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
