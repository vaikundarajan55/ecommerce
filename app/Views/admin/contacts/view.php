<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="panel" style="max-width:760px"><div class="panel-head"><strong><?= esc($row['subject'] ?: 'Message from ' . $row['name']) ?></strong><span class="text-muted small"><?= date('d M Y, h:i A', strtotime($row['created_at'])) ?></span></div>
  <div class="panel-body">
    <p class="mb-1"><strong>Name:</strong> <?= esc($row['name']) ?></p>
    <p><strong>Email:</strong> <a href="mailto:<?= esc($row['email']) ?>"><?= esc($row['email']) ?></a></p><hr><p style="white-space:pre-line"><?= esc($row['message']) ?></p>
    <a href="mailto:<?= esc($row['email']) ?>" class="btn btn-brand"><i class="bi bi-reply me-1"></i>Reply by email</a> <a href="<?= base_url('admin/contacts') ?>" class="btn btn-light">Back</a></div></div>
<?= $this->endSection() ?>
