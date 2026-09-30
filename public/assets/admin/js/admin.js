document.addEventListener('DOMContentLoaded', () => {
  // Mobile sidebar
  const side = document.getElementById('sidebar'), scrim = document.getElementById('scrim');
  document.getElementById('menuBtn')?.addEventListener('click', () => { side.classList.toggle('show'); scrim.classList.toggle('show'); });
  scrim?.addEventListener('click', () => { side.classList.remove('show'); scrim.classList.remove('show'); });

  // Confirm on delete forms
  document.querySelectorAll('form[data-confirm]').forEach(f => f.addEventListener('submit', e => {
    if (!confirm(f.dataset.confirm)) e.preventDefault();
  }));

  // Count-up numbers on the dashboard
  document.querySelectorAll('[data-count]').forEach(el => {
    const target = parseFloat(el.dataset.count), prefix = el.dataset.prefix || '', dec = parseInt(el.dataset.dec || '0', 10);
    const t0 = performance.now(), dur = 1100;
    const tick = now => {
      const p = Math.min((now - t0) / dur, 1), v = target * (1 - Math.pow(1 - p, 3));
      el.textContent = prefix + v.toLocaleString('en-IN', { minimumFractionDigits: dec, maximumFractionDigits: dec });
      if (p < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
  });

  // Stagger table rows
  document.querySelectorAll('.row-anim tbody tr').forEach((tr, i) => tr.style.animationDelay = (i * 40) + 'ms');

  // Image preview for file inputs
  document.querySelectorAll('input[type=file][data-preview]').forEach(inp => inp.addEventListener('change', () => {
    const img = document.getElementById(inp.dataset.preview); if (!img || !inp.files[0]) return;
    img.src = URL.createObjectURL(inp.files[0]); img.classList.remove('d-none');
  }));

  // Password show/hide
  document.querySelectorAll('[data-toggle-pass]').forEach(b => b.addEventListener('click', () => {
    const i = document.querySelector(b.dataset.togglePass); i.type = i.type === 'password' ? 'text' : 'password';
    b.querySelector('i').classList.toggle('bi-eye'); b.querySelector('i').classList.toggle('bi-eye-slash');
  }));

  // Subcategory dropdown follows the chosen category (product form)
  const cat = document.getElementById('categorySelect'), sub = document.getElementById('subSelect');
  if (cat && sub) {
    const filter = () => { [...sub.options].forEach(o => { o.hidden = o.value && o.dataset.cat !== cat.value; });
      if (sub.selectedOptions[0] && sub.selectedOptions[0].hidden) sub.value = ''; };
    cat.addEventListener('change', filter); filter();
  }
});
