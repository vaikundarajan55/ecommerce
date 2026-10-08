<?php /** Title / subtitle on the left, search + optional action button on the right. Expects: $heading, $sub, $toolbar (HTML). Optional: $action (HTML). */ ?>
<div class="list-head">
  <div><h2 class="list-title"><?= esc($heading) ?></h2><p class="list-sub"><?= esc($sub) ?></p></div>
  <div class="list-actions"><?= $toolbar ?><?= $action ?? '' ?></div>
</div>
