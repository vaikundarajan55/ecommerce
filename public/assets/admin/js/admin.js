document.addEventListener('DOMContentLoaded', () => {
  // Sidebar toggle: full <-> icons only on desktop (remembered), slide in/out on mobile
  const side = document.getElementById('sidebar'), scrim = document.getElementById('scrim');
  const closeMobile = () => { side.classList.remove('show'); scrim.classList.remove('show'); };
  document.getElementById('menuBtn')?.addEventListener('click', () => {
    if (window.matchMedia('(max-width: 991.98px)').matches) {
      side.classList.toggle('show'); scrim.classList.toggle('show');
    } else {
      const mini = document.body.classList.toggle('sb-mini');
      try { localStorage.setItem('adminSidebar', mini ? 'mini' : 'full'); } catch (e) {}
    }
  });
  scrim?.addEventListener('click', closeMobile);

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

  // Product gallery: mark existing images for removal, preview new picks (picking again adds to the list)
  const gGrid = document.getElementById('galleryGrid'), gInput = document.getElementById('galleryInput');
  if (gGrid && gInput) {
    const max = parseInt(gGrid.dataset.max, 10), addTile = document.getElementById('galleryAdd'), counter = document.getElementById('galleryCount');
    let picked = [];
    const kept = () => gGrid.querySelectorAll('.gallery-item input[type=checkbox]:not(:checked)').length;
    const sync = () => {
      const dt = new DataTransfer(); picked.forEach(f => dt.items.add(f)); gInput.files = dt.files;
      gGrid.querySelectorAll('.gallery-item.is-new').forEach(el => el.remove());
      picked.forEach((f, i) => {
        const tile = document.createElement('div'); tile.className = 'gallery-item is-new';
        tile.innerHTML = '<img alt=""><span class="gallery-new">New</span><button type="button" class="gallery-drop" aria-label="Remove"><i class="bi bi-x-lg"></i></button>';
        tile.querySelector('img').src = URL.createObjectURL(f);
        tile.querySelector('button').addEventListener('click', () => { picked.splice(i, 1); sync(); });
        gGrid.insertBefore(tile, addTile);
      });
      const total = kept() + picked.length;
      counter.textContent = total; addTile.classList.toggle('d-none', total >= max);
    };
    gInput.addEventListener('change', () => {
      const room = max - kept() - picked.length, files = [...gInput.files].filter(f => f.type.startsWith('image/'));
      if (files.length > room) alert(`You can add ${Math.max(room, 0)} more image(s). Up to ${max} per product.`);
      picked = picked.concat(files.slice(0, Math.max(room, 0))); sync();
    });
    gGrid.querySelectorAll('.gallery-item input[type=checkbox]').forEach(cb => cb.addEventListener('change', () => {
      cb.closest('.gallery-item').classList.toggle('marked', cb.checked); sync();
    }));
  }

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
