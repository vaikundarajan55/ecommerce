<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="panel">
  <div class="panel-head"><strong>User list</strong><form class="d-flex" method="get"><input class="form-control form-control-sm" name="q" value="<?= esc($q) ?>" placeholder="Name, email or phone"><button class="btn btn-sm btn-outline-brand ms-1"><i class="bi bi-search"></i></button></form></div>
  <div class="table-responsive"><table class="table table-hover mb-0 row-anim">
    <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Orders</th><th>Joined</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $u): ?>
      <tr><td><?= $u['id'] ?></td><td><strong><?= esc($u['name']) ?></strong><div class="small text-muted"><?= esc($u['city']) ?></div></td><td><?= esc($u['email']) ?></td><td><?= esc($u['phone']) ?></td><td><?= $u['order_count'] ?></td><td><?= date('d M Y', strtotime($u['created_at'])) ?></td><td><?= active_badge($u['status']) ?></td>
        <td class="text-end text-nowrap">
          <form action="<?= base_url('admin/users/toggle/' . $u['id']) ?>" method="post" class="d-inline"><?= csrf_field() ?><button class="btn btn-sm btn-outline-secondary" title="Enable / disable"><i class="bi bi-toggle-<?= $u['status'] ? 'on' : 'off' ?>"></i></button></form>
          <?= delete_form(base_url('admin/users/delete/' . $u['id']), 'Delete this user and all their orders?') ?></td></tr>
    <?php endforeach; if (! $rows): ?><tr><td colspan="8" class="text-center text-muted py-4">No users found.</td></tr><?php endif; ?>
    </tbody></table></div>
  <div class="p-3"><?= $pager->links('default', 'bootstrap_full') ?></div>
</div>
<?= $this->endSection() ?>
