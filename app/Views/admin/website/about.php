<?= $this->extend('admin/layout') ?>
<?= $this->section('hero_actions') ?>
<a href="<?= base_url('about') ?>" target="_blank" class="btn-back"><i class="bi bi-box-arrow-up-right"></i>View page</a>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php
  $v     = static fn (string $k) => esc(old($k, $s[$k] ?? ''));
  $stats = old('stats') ?? (json_decode($s['about_stats'] ?? '[]', true) ?: []);
  $proms = old('promises') ?? (json_decode($s['about_promises'] ?? '[]', true) ?: []);
?>
<form action="<?= base_url('admin/website/about') ?>" method="post" enctype="multipart/form-data" class="row g-4">
  <?= csrf_field() ?>
  <div class="col-lg-8">
    <div class="panel list-card mb-4">
      <div class="list-head"><div><h2 class="list-title">Page introduction</h2><p class="list-sub">Shown at the top of the About us page</p></div></div>
      <div class="row g-3 pb-3">
        <div class="col-12"><label class="form-label" for="tagline">Header tagline</label><input id="tagline" class="form-control" name="about_tagline" maxlength="200" value="<?= $v('about_tagline') ?>"></div>
        <div class="col-12"><label class="form-label" for="heading">Story heading</label><input id="heading" class="form-control" name="about_heading" maxlength="150" value="<?= $v('about_heading') ?>" required></div>
        <div class="col-12"><label class="form-label" for="body">Story text</label><textarea id="body" class="form-control" name="about_body" rows="7" required><?= $v('about_body') ?></textarea><div class="form-text">Leave a blank line between paragraphs.</div></div>
      </div>
    </div>

    <div class="panel list-card mb-4">
      <div class="list-head"><div><h2 class="list-title">Highlights</h2><p class="list-sub">Up to 4 number tiles, e.g. “10k+ / Orders delivered”. Leave a row empty to hide it.</p></div></div>
      <div class="row g-3 pb-3">
        <?php for ($i = 0; $i < 4; $i++): ?>
          <div class="col-4 col-md-3"><label class="form-label" for="sv<?= $i ?>">Value <?= $i + 1 ?></label><input id="sv<?= $i ?>" class="form-control" name="stats[<?= $i ?>][value]" maxlength="20" value="<?= esc($stats[$i]['value'] ?? '') ?>"></div>
          <div class="col-8 col-md-9"><label class="form-label" for="sl<?= $i ?>">Label <?= $i + 1 ?></label><input id="sl<?= $i ?>" class="form-control" name="stats[<?= $i ?>][label]" maxlength="60" value="<?= esc($stats[$i]['label'] ?? '') ?>"></div>
        <?php endfor; ?>
      </div>
    </div>

    <div class="panel list-card">
      <div class="list-head"><div><h2 class="list-title">What we promise</h2><p class="list-sub">Up to 3 promises shown under the story. Leave a row empty to hide it.</p></div></div>
      <div class="row g-3 pb-3">
        <?php for ($i = 0; $i < 3; $i++): ?>
          <div class="col-md-4"><label class="form-label" for="pt<?= $i ?>">Title <?= $i + 1 ?></label><input id="pt<?= $i ?>" class="form-control" name="promises[<?= $i ?>][title]" maxlength="60" value="<?= esc($proms[$i]['title'] ?? '') ?>"></div>
          <div class="col-md-8"><label class="form-label" for="px<?= $i ?>">Text <?= $i + 1 ?></label><input id="px<?= $i ?>" class="form-control" name="promises[<?= $i ?>][text]" maxlength="160" value="<?= esc($proms[$i]['text'] ?? '') ?>"></div>
        <?php endfor; ?>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="panel list-card mb-4">
      <div class="list-head"><div><h2 class="list-title">Story image</h2><p class="list-sub">Optional. Shown beside the story text.</p></div></div>
      <input type="file" class="form-control mb-3" name="image" accept="image/*" data-preview="prev">
      <img id="prev" class="img-preview mb-3 <?= ($s['about_image'] ?? '') ? '' : 'd-none' ?>" style="max-width:100%" src="<?= img_url($s['about_image'] ?? null, 'pages') ?>" alt="Preview">
      <?php if ($s['about_image'] ?? ''): ?><div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="remove_image" id="rmImg" value="1"><label class="form-check-label" for="rmImg">Remove image</label></div><?php endif; ?>
    </div>
    <button class="btn btn-grad w-100 justify-content-center"><i class="bi bi-check2-circle me-2"></i>Save About us page</button>
  </div>
</form>
<?= $this->endSection() ?>
