<?= $this->extend('website/layout') ?>
<?= $this->section('content') ?>
<div class="page-head"><div class="container"><h1 class="fw-bold mb-0">My profile</h1></div></div>
<section class="section pt-4"><div class="container"><div class="row g-4">
  <div class="col-lg-3"><?= $this->include('website/account/nav') ?></div>
  <div class="col-lg-9">
    <form action="<?= base_url('account/profile') ?>" method="post" class="order-card form-card">
      <?= csrf_field() ?>
      <div class="order-head">
        <div><h2 class="metrics-title">Profile details</h2><p class="metrics-sub mb-0">Keep your contact and delivery details up to date</p></div>
        <span class="form-badge"><i class="bi bi-person-badge"></i></span>
      </div>

      <div class="form-group-card" style="--i: 0">
        <h3 class="form-group-title"><i class="bi bi-person-lines-fill"></i>Personal details</h3>
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label" for="pfName">Full name</label>
            <div class="pf-field"><i class="bi bi-person"></i><input id="pfName" class="form-control" name="name" value="<?= esc(old('name', $user['name'])) ?>" required></div></div>
          <div class="col-md-6"><label class="form-label" for="pfEmail">Email</label>
            <div class="pf-field"><i class="bi bi-envelope"></i><input id="pfEmail" type="email" class="form-control" name="email" value="<?= esc(old('email', $user['email'])) ?>" required></div></div>
          <div class="col-md-6"><label class="form-label" for="pfPhone">Phone</label>
            <div class="pf-field"><i class="bi bi-telephone"></i><input id="pfPhone" class="form-control" name="phone" inputmode="tel" value="<?= esc(old('phone', $user['phone'])) ?>"></div></div>
        </div>
      </div>

      <div class="form-group-card" style="--i: 1">
        <h3 class="form-group-title"><i class="bi bi-geo-alt-fill"></i>Delivery address</h3>
        <div class="row g-3">
          <div class="col-12"><label class="form-label" for="pfAddress">Address</label>
            <div class="pf-field pf-area"><i class="bi bi-house-door"></i><textarea id="pfAddress" class="form-control" name="address" rows="2"><?= esc(old('address', $user['address'])) ?></textarea></div></div>
          <div class="col-md-6"><label class="form-label" for="pfCity">City</label>
            <div class="pf-field"><i class="bi bi-buildings"></i><input id="pfCity" class="form-control" name="city" value="<?= esc(old('city', $user['city'])) ?>"></div></div>
          <div class="col-md-6"><label class="form-label" for="pfPin">Pincode</label>
            <div class="pf-field"><i class="bi bi-mailbox"></i><input id="pfPin" class="form-control" name="pincode" inputmode="numeric" value="<?= esc(old('pincode', $user['pincode'])) ?>"></div></div>
        </div>
      </div>

      <div class="form-foot">
        <button type="reset" class="btn-soft">Reset</button>
        <button class="btn-grad-save"><i class="bi bi-check2-circle"></i>Save changes</button>
      </div>
    </form>
  </div>
</div></div></section>
<?= $this->endSection() ?>
