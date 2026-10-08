document.addEventListener('DOMContentLoaded', () => {
  // AOS scroll reveal (loaded from CDN in layout)
  if (window.AOS) { AOS.init({ duration: 600, once: true, offset: 40 }); }

  // Quantity +/- steppers
  document.querySelectorAll('.qty-box').forEach(box => {
    const input = box.querySelector('input');
    box.querySelectorAll('button').forEach(btn => btn.addEventListener('click', () => {
      const max = parseInt(input.max || '99', 10);
      let v = parseInt(input.value || '1', 10) + (btn.dataset.step === '+' ? 1 : -1);
      input.value = Math.min(Math.max(v, 1), max);
    }));
  });

  // Bump the cart badge when the page shows a "Added to cart" flash
  if (document.querySelector('.flash-alert.alert-success') && /Added to cart/i.test(document.body.innerText)) {
    const b = document.querySelector('.cart-badge'); if (b) b.classList.add('bump');
  }

  // Auto-dismiss flash messages (not the long demo reset link)
  setTimeout(() => document.querySelectorAll('.flash-alert.alert-success:not([data-keep])').forEach(a => {
    if (a.textContent.length < 120) bootstrap.Alert.getOrCreateInstance(a).close();
  }), 5000);

  // Confetti on success page
  const c = document.querySelector('.confetti');
  if (c) {
    const colors = ['#0b6e6e', '#f5a524', '#d94a2b', '#1a9e5c', '#16222b'];
    for (let i = 0; i < 46; i++) {
      const p = document.createElement('i');
      p.style.left = Math.random() * 100 + '%';
      p.style.background = colors[i % colors.length];
      p.style.animationDuration = (2.2 + Math.random() * 2.4) + 's';
      p.style.animationDelay = (Math.random() * 0.8) + 's';
      c.appendChild(p);
    }
  }

  // Dummy gateway: choose outcome, show spinner, then submit
  const gw = document.getElementById('gwForm');
  if (gw) {
    gw.querySelectorAll('button[data-result]').forEach(btn => btn.addEventListener('click', e => {
      e.preventDefault();
      if (!gw.checkValidity() && btn.dataset.result === 'success') { gw.reportValidity(); return; }
      gw.querySelector('input[name=result]').value = btn.dataset.result;
      document.getElementById('gwFields').style.display = 'none';
      document.getElementById('gwSpinner').style.display = 'block';
      setTimeout(() => gw.submit(), 1800);
    }));
    const num = document.getElementById('cardNumber');
    if (num) num.addEventListener('input', () => { num.value = num.value.replace(/\D/g, '').slice(0, 16).replace(/(.{4})/g, '$1 ').trim(); });
  }

  // Product page gallery: click a thumbnail to show it as the main image
  const galleryMain = document.getElementById('galleryMain');
  document.querySelectorAll('.gallery-thumb').forEach(t => t.addEventListener('click', () => {
    document.querySelectorAll('.gallery-thumb.active').forEach(a => a.classList.remove('active'));
    t.classList.add('active');
    galleryMain.style.opacity = 0;
    setTimeout(() => { galleryMain.src = t.dataset.src; galleryMain.style.opacity = 1; }, 150);
  }));
});
