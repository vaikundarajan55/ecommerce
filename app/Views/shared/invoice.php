<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= esc($title) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@800&family=Nunito+Sans:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body { background:#eef2f3; font-family:'Nunito Sans',sans-serif; }
    .invoice { max-width: 860px; margin: 24px auto; background:#fff; padding: 44px; border-radius: 14px; box-shadow: 0 10px 30px rgba(0,0,0,.06); animation: up .5s both; }
    @keyframes up { from { opacity:0; transform: translateY(16px);} to { opacity:1; transform:none; } }
    .inv-brand { font-family:'Bricolage Grotesque',sans-serif; font-size: 1.7rem; color:#0b6e6e; }
    .inv-title { font-family:'Bricolage Grotesque',sans-serif; font-size: 2.2rem; letter-spacing:.02em; }
    th { background:#f4f7f7 !important; }
    @media print { body { background:#fff; } .invoice { box-shadow:none; margin:0; padding:0; max-width:100%; animation:none; } .no-print { display:none !important; } }
  </style>
</head>
<body>
<div class="container no-print pt-3 text-center">
  <a href="<?= $backUrl ?>" class="btn btn-outline-secondary btn-sm me-2"><i class="bi bi-arrow-left"></i> Back</a>
  <button onclick="window.print()" class="btn btn-dark btn-sm"><i class="bi bi-printer"></i> Print / Save as PDF</button>
</div>
<div class="invoice">
  <div class="d-flex justify-content-between align-items-start mb-4">
    <div><div class="inv-brand fw-bold"><i class="bi bi-bag-heart-fill me-1"></i><?= site_name() ?></div>
      <div class="small text-muted">12 Beach Road, Puducherry 605001<br>support@shopkart.test &middot; +91 98765 43210</div></div>
    <div class="text-end"><div class="inv-title">INVOICE</div>
      <div class="small">No: <strong>INV-<?= esc($order['order_no']) ?></strong><br>Date: <?= date('d M Y', strtotime($order['created_at'])) ?></div></div>
  </div>
  <div class="row mb-4">
    <div class="col-6"><div class="text-muted small mb-1">Billed to</div><strong><?= esc($order['name']) ?></strong><br><?= esc($order['address']) ?><br><?= esc($order['city']) ?> - <?= esc($order['pincode']) ?><br><?= esc($order['phone']) ?><br><?= esc($order['email']) ?></div>
    <div class="col-6 text-end"><div class="text-muted small mb-1">Payment</div>Status: <strong class="text-uppercase <?= $order['payment_status'] === 'paid' ? 'text-success' : 'text-danger' ?>"><?= esc($order['payment_status']) ?></strong><br>Transaction: <?= esc($order['txn_id'] ?: '—') ?><br>Order status: <?= esc(ucfirst($order['status'])) ?></div>
  </div>
  <table class="table align-middle">
    <thead><tr><th>#</th><th>Item</th><th class="text-end">Price</th><th class="text-center">Qty</th><th class="text-end">Amount</th></tr></thead>
    <tbody><?php foreach ($items as $n => $i): ?>
      <tr><td><?= $n + 1 ?></td><td><?= esc($i['name']) ?></td><td class="text-end"><?= money($i['price']) ?></td><td class="text-center"><?= $i['qty'] ?></td><td class="text-end"><?= money($i['total']) ?></td></tr>
    <?php endforeach; ?></tbody>
  </table>
  <div class="row justify-content-end"><div class="col-sm-5">
    <div class="d-flex justify-content-between mb-1"><span>Subtotal</span><span><?= money($order['subtotal']) ?></span></div>
    <div class="d-flex justify-content-between mb-1"><span>Shipping</span><span><?= $order['shipping'] > 0 ? money($order['shipping']) : 'Free' ?></span></div>
    <hr class="my-2"><div class="d-flex justify-content-between fs-5 fw-bold"><span>Total</span><span><?= money($order['total']) ?></span></div>
  </div></div>
  <hr class="my-4"><p class="text-center text-muted small mb-0">Thank you for shopping with <?= site_name() ?>. This is a computer generated invoice.</p>
</div>
</body>
</html>
