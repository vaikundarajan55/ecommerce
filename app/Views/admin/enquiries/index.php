<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<?php
  $tabs = ['' => ['All messages', 'bi-inbox'], 'product' => ['Product enquiries', 'bi-box-seam'], 'contact' => ['Contact us', 'bi-envelope']];
  $num  = static fn (string $k, string $f) => $k === '' ? array_sum(array_column($counts, $f)) : ($counts[$k][$f] ?? 0);
?>
<div class="inbox-tabs" role="tablist">
  <?php foreach ($tabs as $k => [$lbl, $ic]): ?>
    <a class="inbox-tab <?= $source === $k ? 'active' : '' ?>" href="<?= base_url('admin/enquiries' . ($k ? '?source=' . $k : '')) ?>" <?= $source === $k ? 'aria-current="page"' : '' ?>>
      <i class="bi <?= $ic ?>"></i><span><?= $lbl ?></span><b><?= $num($k, 'total') ?></b><?php if ($num($k, 'unread')): ?><em><?= $num($k, 'unread') ?> new</em><?php endif; ?></a>
  <?php endforeach; ?>
</div>
<div class="panel list-card">
  <?= view('admin/partials/list_head', ['heading' => $tabs[$source][0], 'sub' => 'Questions from product pages and messages from the website Contact us form', 'toolbar' => view('admin/partials/table_toolbar', compact('perPage', 'q') + ['placeholder' => 'Name, email, subject, message', 'keep' => $source ? ['source' => $source] : []])]) ?>
  <div class="list-table table-responsive"><table class="table mb-0 row-anim">
    <thead><tr><th>S. No</th><th>Received</th><th>From</th><th>Source</th><th>Product / subject</th><th>Message</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $i => $r): $isContact = $r['source'] === 'contact'; ?>
      <tr class="<?= $r['is_read'] ? '' : 'unread' ?>"><td class="sno"><?= sprintf('%02d', $from + $i) ?></td><td><?= date_cell($r['created_at']) ?></td>
        <td class="fw-bold"><?= esc($r['name']) ?><div class="small text-muted fw-normal"><?= esc($r['email']) ?></div></td>
        <td><span class="pill <?= $isContact ? 'pill-green' : 'pill-blue' ?>"><i class="bi <?= $isContact ? 'bi-envelope' : 'bi-box-seam' ?> me-1"></i><?= $isContact ? 'Contact us' : 'Product' ?></span></td>
        <td><?= esc(($isContact ? $r['subject'] : $r['product_name']) ?: '—') ?></td>
        <td><?= esc(character_limiter($r['message'], 60)) ?> <?= $r['is_read'] ? '' : '<span class="pill pill-amber">New</span>' ?></td>
        <td class="text-end"><?= row_menu([
          ['View', 'bi-eye', base_url('admin/enquiries/view/' . $r['id'])],
          ['Reply by email', 'bi-reply', 'mailto:' . esc($r['email'], 'url')],
          ['Delete', 'bi-trash3', base_url('admin/enquiries/delete/' . $r['id']), 'post' => true, 'danger' => true, 'confirm' => 'Delete this message? This cannot be undone.'],
        ]) ?></td></tr>
    <?php endforeach; if (! $rows): ?><tr><td colspan="7" class="text-center text-muted py-5">No messages found.</td></tr><?php endif; ?>
    </tbody></table></div>
  <?= $this->include('admin/partials/table_footer') ?>
</div>
<?= $this->endSection() ?>
