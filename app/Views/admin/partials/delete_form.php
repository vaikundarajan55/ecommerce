<form action="<?= $action ?>" method="post" class="d-inline" data-confirm="<?= esc($msg ?? 'Delete this item? This cannot be undone.') ?>">
  <?= csrf_field() ?>
  <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash3"></i></button>
</form>
