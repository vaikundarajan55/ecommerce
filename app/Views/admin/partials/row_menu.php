<?php
/**
 * "⋮" actions dropdown for a table row.
 * Expects: $items — each [label, icon, url] for a link, or [label, icon, url, 'post' => true, 'confirm' => msg, 'danger' => true] for a POST form.
 */
?>
<div class="dropdown row-menu">
  <button class="kebab" type="button" data-bs-toggle="dropdown" data-bs-popper-config='{"strategy":"fixed"}' aria-expanded="false" aria-label="Row actions"><i class="bi bi-three-dots-vertical"></i></button>
  <ul class="dropdown-menu dropdown-menu-end nav-drop">
    <?php foreach ($items as $it): $cls = 'dropdown-item' . (! empty($it['danger']) ? ' text-danger' : ''); ?>
      <li>
        <?php if (! empty($it['post'])): ?>
          <form action="<?= $it[2] ?>" method="post"<?= isset($it['confirm']) ? ' data-confirm="' . esc($it['confirm']) . '"' : '' ?>>
            <?= csrf_field() ?><button class="<?= $cls ?>"><i class="bi <?= $it[1] ?>"></i><?= esc($it[0]) ?></button>
          </form>
        <?php else: ?>
          <a class="<?= $cls ?>" href="<?= $it[2] ?>"><i class="bi <?= $it[1] ?>"></i><?= esc($it[0]) ?></a>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
