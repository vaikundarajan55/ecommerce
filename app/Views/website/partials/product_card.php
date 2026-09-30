<?php $off = discount_pct($p); ?>
<div class="pcard">
  <?php if ($off): ?><span class="off-badge">-<?= $off ?>%</span><?php endif; ?>
  <?php if ($p['stock'] < 1): ?><span class="oos-badge">Sold out</span><?php endif; ?>
  <a class="thumb" href="<?= base_url('shop/' . $p['slug']) ?>"><img src="<?= img_url($p['image']) ?>" alt="<?= esc($p['name']) ?>" loading="lazy"></a>
  <div class="body">
    <?php if (! empty($p['category_name'])): ?><div class="cat"><?= esc($p['category_name']) ?></div><?php endif; ?>
    <a class="pname mb-2" href="<?= base_url('shop/' . $p['slug']) ?>"><?= esc($p['name']) ?></a>
    <div class="mb-3"><span class="price"><?= money(current_price($p)) ?></span><?php if ($off): ?><span class="price-old"><?= money($p['price']) ?></span><?php endif; ?></div>
    <form class="mt-auto" action="<?= base_url('cart/add/' . $p['id']) ?>" method="post">
      <?= csrf_field() ?>
      <button class="btn btn-brand btn-sm w-100 add-btn" <?= $p['stock'] < 1 ? 'disabled' : '' ?>><i class="bi bi-cart-plus me-1"></i> Add to cart</button>
    </form>
  </div>
</div>
