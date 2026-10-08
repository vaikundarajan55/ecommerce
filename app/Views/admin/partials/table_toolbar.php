<?php
/**
 * Search bar (with optional extra filter fields) shown in the head of admin list cards.
 * Expects: $perPage, $q. Optional: $placeholder, $extra (HTML of extra filter fields), $keep (GET params to carry over).
 */
?>
<form class="table-tools" method="get">
  <?php foreach (($keep ?? []) + ['per_page' => $perPage] as $k => $v): ?><input type="hidden" name="<?= esc($k) ?>" value="<?= esc($v) ?>"><?php endforeach; ?>
  <?= $extra ?? '' ?>
  <div class="search-box">
    <i class="bi bi-search"></i>
    <input type="search" name="q" value="<?= esc($q) ?>" placeholder="<?= esc($placeholder ?? 'Search…') ?>" aria-label="Search">
    <?php if ($q !== ''): ?><a class="search-clear" title="Clear search" href="?<?= esc(http_build_query(array_diff_key(service('request')->getGet(), ['q' => 1, 'page' => 1]))) ?>"><i class="bi bi-x-lg"></i></a><?php endif; ?>
  </div>
</form>
