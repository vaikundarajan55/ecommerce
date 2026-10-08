<?= $this->extend('website/layout') ?>
<?= $this->section('content') ?>
<div class="page-head"><div class="container">
  <h1 class="fw-bold mb-1"><?= esc($activeSub['name'] ?? $activeCat['name'] ?? ($q ? 'Results for “' . $q . '”' : ($tagTitle ?? 'All products'))) ?></h1>
  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0"><li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li><li class="breadcrumb-item"><a href="<?= base_url('shop') ?>">Shop</a></li><?php if ($activeCat): ?><li class="breadcrumb-item active"><?= esc($activeCat['name']) ?></li><?php endif; ?></ol></nav>
</div></div>
<section class="section pt-4">
  <div class="container">
    <div class="row g-4">
      <aside class="col-lg-3">
        <div class="filter-card">
          <h6 class="mb-3">Categories</h6>
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><a href="<?= base_url('shop') ?>" class="<?= ! $activeCat ? 'active' : '' ?>">All products</a></li>
            <?php foreach ($categories as $c): ?>
              <li class="list-group-item">
                <a href="<?= base_url('shop?cat=' . $c['slug']) ?>" class="<?= ($activeCat['id'] ?? 0) == $c['id'] ? 'active' : '' ?>"><?= esc($c['name']) ?></a>
                <?php if (($activeCat['id'] ?? 0) == $c['id']): ?>
                  <ul class="list-unstyled ps-3 mt-1">
                    <?php foreach ($subs as $s): if ($s['category_id'] == $c['id']): ?>
                      <li><a class="small <?= ($activeSub['id'] ?? 0) == $s['id'] ? 'active' : '' ?>" href="<?= base_url('shop?cat=' . $c['slug'] . '&sub=' . $s['slug']) ?>"><?= esc($s['name']) ?></a></li>
                    <?php endif; endforeach; ?>
                  </ul>
                <?php endif; ?>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </aside>
      <div class="col-lg-9">
        <form class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3" method="get">
          <?php if ($activeCat): ?><input type="hidden" name="cat" value="<?= esc($activeCat['slug']) ?>"><?php endif; ?>
          <?php if ($activeSub): ?><input type="hidden" name="sub" value="<?= esc($activeSub['slug']) ?>"><?php endif; ?>
          <?php if ($q): ?><input type="hidden" name="q" value="<?= esc($q) ?>"><?php endif; ?>
          <?php if ($tag): ?><input type="hidden" name="tag" value="<?= esc($tag) ?>"><?php endif; ?>
          <span class="text-muted-2"><?= count($products) ?> products on this page</span>
          <select name="sort" class="form-select w-auto" onchange="this.form.submit()" aria-label="Sort products">
            <option value="">Newest first</option>
            <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price: low to high</option>
            <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price: high to low</option>
            <option value="name" <?= $sort === 'name' ? 'selected' : '' ?>>Name A–Z</option>
          </select>
        </form>
        <?php if ($products): ?>
          <div class="row g-3 g-lg-4">
            <?php foreach ($products as $n => $p): ?>
              <div class="col-6 col-xl-4" data-aos="fade-up" data-aos-delay="<?= ($n % 3) * 70 ?>"><?= product_card($p) ?></div>
            <?php endforeach; ?>
          </div>
          <div class="mt-4"><?= $pager->links('default', 'bootstrap_full') ?></div>
        <?php else: ?>
          <div class="text-center py-5"><i class="bi bi-search fs-1 text-muted-2"></i><h5 class="mt-3">No products found</h5><p class="text-muted-2">Try a different search or clear the filters.</p><a href="<?= base_url('shop') ?>" class="btn btn-brand">Show all products</a></div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<?= $this->endSection() ?>
