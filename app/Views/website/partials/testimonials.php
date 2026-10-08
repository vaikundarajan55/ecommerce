<?php /** Customer testimonials (Admin > Testimonials). Expects: $testimonials. Optional: $alt (grey section background). */ ?>
<?php if ($testimonials): ?>
<section class="section <?= ! empty($alt) ? 'section-alt' : '' ?> testi-section">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <span class="eyebrow"><i class="bi bi-chat-heart"></i>Testimonials</span>
      <h2 class="section-title text-center d-inline-block mt-2">What our customers say</h2>
    </div>
    <div class="row g-4 justify-content-center">
      <?php foreach ($testimonials as $n => $t): $rating = max(1, min(5, (int) $t['rating'])); ?>
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= ($n % 3) * 100 ?>">
          <figure class="testi-card">
            <i class="bi bi-quote testi-quote" aria-hidden="true"></i>
            <div class="testi-stars" aria-label="<?= $rating ?> out of 5 stars"><?= str_repeat('<i class="bi bi-star-fill"></i>', $rating) . str_repeat('<i class="bi bi-star"></i>', 5 - $rating) ?></div>
            <blockquote class="testi-text"><?= esc($t['message']) ?></blockquote>
            <figcaption class="testi-person">
              <?php if ($t['photo'] && is_file(FCPATH . 'uploads/testimonials/' . $t['photo'])): ?>
                <img class="testi-avatar" src="<?= img_url($t['photo'], 'testimonials') ?>" alt="">
              <?php else: ?>
                <span class="testi-avatar"><?= esc(mb_strtoupper(mb_substr($t['name'], 0, 1))) ?></span>
              <?php endif; ?>
              <span><strong><?= esc($t['name']) ?></strong><?php if ($t['role']): ?><small><?= esc($t['role']) ?></small><?php endif; ?></span>
            </figcaption>
          </figure>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
