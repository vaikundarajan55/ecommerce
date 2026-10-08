<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<?php $cards = [
    ['m-blue',  'Unique visitors',   $stats['unique'],   'Distinct IP addresses',     'bi-people-fill'],
    ['m-green', 'Visitors today',    $stats['today'],    'IPs seen on ' . date('d M Y'), 'bi-calendar-check-fill'],
    ['m-amber', 'Page views',        $stats['views'],    'Total website page hits',   'bi-eye-fill'],
    ['m-blue',  'Purchase IPs',      $stats['buyerIps'], 'Distinct IPs that ordered', 'bi-bag-check-fill'],
]; ?>
<section class="panel metrics-card reveal">
  <h2 class="list-title">Website traffic</h2>
  <p class="list-sub">Visitors are counted once per IP address per day</p>
  <div class="row g-3 mt-1">
    <?php foreach ($cards as $i => [$tone, $label, $num, $caption, $icon]): ?>
      <div class="col-sm-6 col-xl-3">
        <div class="metric <?= $tone ?>" style="--rd: <?= $i * 90 ?>ms">
          <div class="metric-body">
            <span class="metric-label"><?= esc($label) ?></span>
            <strong class="metric-num" data-count="<?= $num ?>"><?= $num ?></strong>
            <span class="metric-caption"><?= esc($caption) ?></span>
          </div>
          <span class="metric-ico"><i class="bi <?= $icon ?>"></i></span>
          <span class="metric-dot"></span>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<div class="d-flex gap-2 my-3">
  <a class="btn btn-sm <?= $tab === 'visitors' ? 'btn-brand' : 'btn-outline-brand' ?>" href="<?= base_url('admin/visitors') ?>"><i class="bi bi-globe2 me-1"></i>Visitor IPs</a>
  <a class="btn btn-sm <?= $tab === 'purchases' ? 'btn-brand' : 'btn-outline-brand' ?>" href="<?= base_url('admin/visitors?tab=purchases') ?>"><i class="bi bi-bag-check me-1"></i>Purchase IPs</a>
</div>

<div class="panel list-card">
<?php if ($tab === 'visitors'): ?>
  <?= view('admin/partials/list_head', ['heading' => 'Visitor network IPs', 'sub' => 'Each row is one IP address on one day, with its page views', 'toolbar' => view('admin/partials/table_toolbar', compact('perPage', 'q') + ['placeholder' => 'IP, page, customer']) ]) ?>
  <div class="list-table table-responsive"><table class="table mb-0 row-anim">
    <thead><tr><th>S. No</th><th>IP address</th><th>Customer</th><th>Date</th><th>Page views</th><th>Last page</th><th>Last seen</th><th>Device / browser</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $i => $r): ?>
      <tr><td class="sno"><?= sprintf('%02d', $from + $i) ?></td><td class="fw-bold text-nowrap"><?= esc($r['ip_address']) ?></td>
        <td><?php if ($r['user_name']): ?><?= esc($r['user_name']) ?><div class="small text-muted"><?= esc($r['user_email']) ?></div><?php else: ?><span class="text-muted">Guest</span><?php endif; ?></td>
        <td class="text-nowrap"><?= date('d M Y', strtotime($r['visit_date'])) ?></td>
        <td><button type="button" class="pill pill-blue border-0 js-views" data-url="<?= base_url('admin/visitors/pages/' . $r['id']) ?>" title="Show pages viewed"><i class="bi bi-eye me-1"></i><?= (int) $r['hits'] ?> views</button></td>
        <td><?= esc($r['last_page']) ?></td>
        <td class="text-nowrap"><?= date('h:i A', strtotime($r['updated_at'])) ?></td>
        <td class="small text-muted" style="max-width: 260px"><?= esc($r['user_agent']) ?></td></tr>
    <?php endforeach; if (! $rows): ?><tr><td colspan="8" class="text-center text-muted py-5">No visitors recorded yet.</td></tr><?php endif; ?>
    </tbody></table></div>
<?php else: ?>
  <?= view('admin/partials/list_head', ['heading' => 'Purchase network IPs', 'sub' => 'IP address each order was placed from', 'toolbar' => view('admin/partials/table_toolbar', compact('perPage', 'q') + ['placeholder' => 'IP, order no, name, email', 'keep' => ['tab' => 'purchases']])]) ?>
  <div class="list-table table-responsive"><table class="table mb-0 row-anim">
    <thead><tr><th>S. No</th><th>IP address</th><th>Order</th><th>Customer</th><th>Date</th><th>Total</th><th>Payment</th><th>Orders from IP</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $i => $r): ?>
      <tr><td class="sno"><?= sprintf('%02d', $from + $i) ?></td><td class="fw-bold text-nowrap"><?= esc($r['ip_address']) ?></td><td><?= esc($r['order_no']) ?></td>
        <td><?= esc($r['name']) ?><div class="small text-muted"><?= esc($r['email']) ?></div></td>
        <td class="text-nowrap"><?= date('d M Y, h:i A', strtotime($r['created_at'])) ?></td>
        <td class="fw-bold"><?= money($r['total']) ?></td><td><?= status_badge($r['payment_status']) ?></td>
        <td><span class="pill <?= $r['ip_orders'] > 1 ? 'pill-amber' : 'pill-blue' ?>"><?= (int) $r['ip_orders'] ?> Orders</span></td>
        <td class="text-end"><div class="actions"><a class="btn-icon btn-icon-view" title="View order" href="<?= base_url('admin/orders/view/' . $r['id']) ?>"><i class="bi bi-eye"></i></a></div></td></tr>
    <?php endforeach; if (! $rows): ?><tr><td colspan="9" class="text-center text-muted py-5">No orders with an IP address yet.</td></tr><?php endif; ?>
    </tbody></table></div>
<?php endif; ?>
  <?= $this->include('admin/partials/table_footer') ?>
</div>

<div class="modal fade views-modal" id="viewsModal" tabindex="-1" aria-labelledby="viewsTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="vm-head">
        <span class="hero-icon"><i class="bi bi-globe"></i></span>
        <div class="vm-heading"><span class="vm-kicker">Visitor network IP</span><h5 class="vm-title" id="viewsTitle">Page views</h5></div>
        <button type="button" class="vm-close" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-lg"></i></button>
      </div>
      <div class="vm-chips" id="viewsChips"></div>
      <div class="modal-body">
        <div class="list-table table-responsive"><table class="table mb-0">
          <thead><tr><th>S. No</th><th>Page visited</th><th class="text-end">Time</th></tr></thead>
          <tbody id="viewsBody"></tbody>
        </table></div>
      </div>
      <div class="vm-foot"><button type="button" class="btn btn-brand px-4" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i>Close</button></div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(() => {
  const modalEl = document.getElementById('viewsModal');
  if (!modalEl) return;
  // .content is animated (transform), which would trap the popup under the backdrop, so move it to <body>
  document.body.appendChild(modalEl);
  const modal = new bootstrap.Modal(modalEl);
  const body  = document.getElementById('viewsBody');
  const title = document.getElementById('viewsTitle');
  const chips = document.getElementById('viewsChips');
  const el    = (tag, cls, text) => { const n = document.createElement(tag); if (cls) n.className = cls; if (text !== undefined) n.textContent = text; return n; };
  const icon  = (name) => el('i', 'bi ' + name);
  const msg   = (text) => { body.replaceChildren(); const td = body.insertRow().insertCell(); td.colSpan = 3; td.className = 'text-center text-muted py-5'; td.textContent = text; };
  const chip  = (ico, label, value) => { const c = el('div', 'vm-chip'); c.append(icon(ico), el('span', 'vm-chip-label', label), el('strong', '', value)); return c; };

  document.querySelectorAll('.js-views').forEach((btn) => btn.addEventListener('click', async () => {
    title.textContent = 'Page views'; chips.replaceChildren(); msg('Loading…'); modal.show();
    try {
      const res  = await fetch(btn.dataset.url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const data = await res.json();
      if (!res.ok) return msg(data.error || 'Could not load page views.');

      title.textContent = data.ip;
      chips.append(
        chip('bi-calendar3', 'Date', data.date),
        chip('bi-eye', 'Page views', String(data.hits)),
        chip('bi-files', 'Pages listed', String(data.views.length)),
        chip('bi-person', 'Customer', data.customer || 'Guest'),
      );
      if (!data.views.length) return msg('No page details recorded for this visit.');

      body.replaceChildren();
      data.views.forEach((v, i) => {
        const tr = body.insertRow();
        tr.insertCell().append(el('span', 'sno', String(data.views.length - i).padStart(2, '0')));
        const page = el('span', 'vm-page'); page.append(icon('bi-link-45deg'), el('span', '', v.page));
        tr.insertCell().append(page);
        const time = el('span', 'pill pill-blue'); time.append(icon('bi-clock me-1'), document.createTextNode(v.time));
        const tc = tr.insertCell(); tc.className = 'text-end'; tc.append(time);
      });
    } catch (e) { msg('Could not load page views.'); }
  }));
})();
</script>
<?= $this->endSection() ?>
