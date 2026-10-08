<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<?php $isContact = $row['source'] === 'contact'; ?>
<div class="panel" style="max-width:760px"><div class="panel-head"><strong><?= $isContact ? 'Message' : 'Enquiry' ?> from <?= esc($row['name']) ?></strong>
  <span class="d-flex align-items-center gap-2"><span class="pill <?= $isContact ? 'pill-green' : 'pill-blue' ?>"><?= $isContact ? 'Contact us form' : 'Product page' ?></span><span class="text-muted small"><?= date('d M Y, h:i A', strtotime($row['created_at'])) ?></span></span></div>
  <div class="panel-body">
    <?php if ($isContact): ?>
      <p class="mb-1"><strong>Subject:</strong> <?= esc($row['subject'] ?: '—') ?></p>
    <?php else: ?>
      <p class="mb-1"><strong>Product:</strong> <?php if ($row['product_slug']): ?><a href="<?= base_url('shop/' . $row['product_slug']) ?>" target="_blank"><?= esc($row['product_name']) ?></a><?php else: ?>—<?php endif; ?></p>
    <?php endif; ?>
    <p class="mb-1"><strong>Email:</strong> <a href="mailto:<?= esc($row['email']) ?>"><?= esc($row['email']) ?></a></p>
    <p><strong>Phone:</strong> <?= esc($row['phone'] ?: '—') ?></p><hr><p style="white-space:pre-line"><?= esc($row['message']) ?></p>
    <a href="mailto:<?= esc($row['email']) ?>?subject=<?= rawurlencode('Re: ' . ($row['subject'] ?: ($row['product_name'] ?: site_name()))) ?>" class="btn btn-brand"><i class="bi bi-reply me-1"></i>Reply by email</a> <a href="<?= base_url('admin/enquiries' . ($isContact ? '?source=contact' : '')) ?>" class="btn btn-light">Back</a></div></div>
<?= $this->endSection() ?>
