<?php
/** "Rows per page" + "x–y of z" + prev/next arrows. Expects: $perPage, $sizes, $from, $to, $total, $pager (null when showing all). */
$keepGet = array_diff_key(service('request')->getGet(), ['per_page' => 1, 'page' => 1]);
?>
<div class="table-foot">
  <form method="get" class="d-flex align-items-center gap-2">
    <?php foreach ($keepGet as $k => $v): if (is_string($v)): ?><input type="hidden" name="<?= esc($k) ?>" value="<?= esc($v) ?>"><?php endif; endforeach; ?>
    <label for="perPage" class="mb-0">Rows per page:</label>
    <select id="perPage" name="per_page" class="rows-select" onchange="this.form.submit()">
      <?php foreach ($sizes as $s): ?><option value="<?= $s ?>" <?= $perPage === $s ? 'selected' : '' ?>><?= $s ?></option><?php endforeach; ?>
      <option value="all" <?= $perPage === 'all' ? 'selected' : '' ?>>All</option>
    </select>
  </form>
  <span><?= $from ?>–<?= $to ?> of <?= $total ?></span>
  <div class="d-flex gap-1">
    <?php $prev = $pager?->getPreviousPageURI(); $next = $pager?->getNextPageURI(); ?>
    <a class="page-arrow <?= $prev ? '' : 'disabled' ?>" href="<?= $prev ?? '#' ?>" aria-label="Previous page"><i class="bi bi-chevron-left"></i></a>
    <a class="page-arrow <?= $next ? '' : 'disabled' ?>" href="<?= $next ?? '#' ?>" aria-label="Next page"><i class="bi bi-chevron-right"></i></a>
  </div>
</div>
