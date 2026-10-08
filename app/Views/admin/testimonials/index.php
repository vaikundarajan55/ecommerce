<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<?= view('admin/partials/status_metrics', ['label' => 'Testimonial', 'plural' => 'testimonials', 'stats' => $stats]) ?>
<div class="panel list-card">
  <?= view('admin/partials/list_head', ['heading' => 'Consolidated testimonial directory', 'sub' => 'Customer quotes shown on the home and About us pages, in display order', 'toolbar' => view('admin/partials/table_toolbar', compact('perPage', 'q') + ['placeholder' => 'Search name, role, message']), 'action' => '<a href="' . base_url('admin/testimonials/create') . '" class="btn btn-grad"><i class="bi bi-plus-lg me-1"></i>Add Testimonial</a>']) ?>
  <div class="list-table table-responsive"><table class="table mb-0 row-anim">
    <thead><tr><th>S. No</th><th>Customer</th><th>Testimonial</th><th>Rating</th><th>Order</th><th>Modified date</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $i => $r): ?>
      <tr><td class="sno"><?= sprintf('%02d', $from + $i) ?></td>
        <td><div class="d-flex align-items-center gap-2"><img class="thumb-sm rounded-circle" src="<?= img_url($r['photo'], 'testimonials') ?>" alt=""><div><div class="fw-bold"><?= esc($r['name']) ?></div><div class="small text-muted"><?= esc($r['role'] ?: '—') ?></div></div></div></td>
        <td style="min-width:240px"><?= esc(character_limiter($r['message'], 80)) ?></td>
        <td class="text-nowrap text-warning" title="<?= (int) $r['rating'] ?> out of 5"><?= str_repeat('<i class="bi bi-star-fill"></i>', (int) $r['rating']) . str_repeat('<i class="bi bi-star"></i>', 5 - (int) $r['rating']) ?></td>
        <td><span class="pill pill-blue"><?= (int) $r['sort_order'] ?></span></td>
        <td><?= date_cell($r['updated_at'] ?? $r['created_at']) ?></td><td><?= active_badge($r['status']) ?></td>
        <td class="text-end"><?= row_menu([
          ['Edit', 'bi-pencil', base_url('admin/testimonials/edit/' . $r['id'])],
          ['Delete', 'bi-trash3', base_url('admin/testimonials/delete/' . $r['id']), 'post' => true, 'danger' => true, 'confirm' => 'Delete this testimonial?'],
        ]) ?></td></tr>
    <?php endforeach; if (! $rows): ?><tr><td colspan="8" class="text-center text-muted py-5">No testimonials yet. Add one to show it on the website.</td></tr><?php endif; ?>
    </tbody></table></div>
  <?= $this->include('admin/partials/table_footer') ?>
</div>
<?= $this->endSection() ?>
