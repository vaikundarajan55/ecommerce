<?php
/**
 * Home page product row with a heading and "View all" link. Hidden when there are no products.
 * Expects: $title, $eyebrow, $icon, $products, $link. Optional: $alt (grey background), $tone (eyebrow colour).
 */
?>
<?php if ($products): ?>
<section class="section <?= ! empty($alt) ? 'section-alt' : '' ?>">
  <div class="container">
    <div class="d-flex flex-wrap gap-3 justify-content-between align-items-end mb-4" data-aos="fade-up">
      <div><span class="eyebrow <?= $tone ?? '' ?>"><i class="bi <?= $icon ?>"></i><?= esc($eyebrow) ?></span><h2 class="section-title mb-0 mt-2"><?= esc($title) ?></h2></div>
      <a class="link-more" href="<?= $link ?>">View all</a>
    </div>
    <div class="row g-3 g-lg-4">
      <?php foreach ($products as $n => $p): ?>
        <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?= ($n % 4) * 80 ?>"><?= product_card($p) ?></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
