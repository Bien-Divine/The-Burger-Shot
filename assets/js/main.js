document.addEventListener('DOMContentLoaded', () => {
  const API = 'api/cart.php';
  let cart = { items: [], total: 0, count: 0 };

  const drawerEl = document.getElementById('cartDrawer');
  const drawer   = (drawerEl && window.bootstrap)
      ? bootstrap.Offcanvas.getOrCreateInstance(drawerEl) : null;

  const money = n => '$' + Number(n || 0).toFixed(2);

  async function api(action, data = {}) {
    const fd = new FormData();
    fd.append('action', action);
    for (const [k, v] of Object.entries(data)) fd.append(k, v);

    const res = await fetch(API, {
      method: 'POST',
      body: fd,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    if (!res.ok) throw new Error('Network error');
    return res.json();
  }

  /* ---------- Render ---------- */
  function render() {
    // badges
    document.querySelectorAll('[data-cart-count]').forEach(el => {
      el.textContent = cart.count;
      el.style.display = cart.count > 0 ? 'grid' : 'none';
    });

    // sticky mobile bar
    const bar = document.getElementById('orderBar');
    if (bar) {
      bar.hidden = cart.count === 0;
      document.body.classList.toggle('has-order-bar', cart.count > 0);
      const bt = document.getElementById('barTotal');
      if (bt) bt.textContent = money(cart.total);
    }

    // drawer
    const wrap    = document.getElementById('cartItems');
    const totalEl = document.getElementById('cartTotal');
    if (!wrap) return;

    if (!cart.items.length) {
      wrap.innerHTML = `
        <div class="text-center py-5 text-secondary">
          <i class="bi bi-bag-x" style="font-size:2.6rem"></i>
          <p class="mt-3 mb-3">Your bag is empty</p>
          <a href="home.php" class="btn btn-outline-accent btn-sm">Browse the menu</a>
        </div>`;
      if (totalEl) totalEl.textContent = money(0);
      return;
    }

    wrap.innerHTML = cart.items.map(i => `
      <div class="cart-line d-flex gap-2 align-items-center">
        <img src="${i.image_url}" alt="" class="cart-thumb">
        <div class="flex-grow-1">
          <div class="fw-semibold small">${i.name}</div>
          <div class="text-secondary small">${money(i.price)}</div>
          <div class="qty-control mt-1">
            <button class="qty-btn" data-cart-set="${i.id}" data-qty="${i.qty - 1}" aria-label="Decrease">&minus;</button>
            <span class="qty-val">${i.qty}</span>
            <button class="qty-btn" data-cart-set="${i.id}" data-qty="${i.qty + 1}" aria-label="Increase">+</button>
          </div>
        </div>
        <div class="text-end">
          <div class="fw-bold small">${money(i.line_total)}</div>
          <button class="btn btn-sm text-danger p-0 mt-1" data-cart-remove="${i.id}" aria-label="Remove">
            <i class="bi bi-trash"></i>
          </button>
        </div>
      </div>`).join('');

    if (totalEl) totalEl.textContent = money(cart.total);
  }

  async function sync(promise) {
    try {
      cart = await promise;
      render();
    } catch (err) {
      toast('Something went wrong. Please try again.', true);
    }
  }

  /* ---------- Toast ---------- */
  let toastTimer;
  function toast(msg, isError = false) {
    let el = document.getElementById('bsToast');
    if (!el) {
      el = document.createElement('div');
      el.id = 'bsToast';
      el.className = 'bs-toast';
      document.body.appendChild(el);
    }
    el.textContent = msg;
    el.classList.toggle('error', isError);
    el.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => el.classList.remove('show'), 2400);
  }

  /* ---------- Delegated events ---------- */
  document.addEventListener('click', async (e) => {
    const addBtn = e.target.closest('[data-add-to-cart]');
    if (addBtn) {
      e.preventDefault();
      addBtn.disabled = true;
      await sync(api('add', { id: addBtn.dataset.addToCart, qty: 1 }));
      addBtn.disabled = false;
      toast('Added to your bag 🍔');
      if (drawer) drawer.show();
      return;
    }

    const setBtn = e.target.closest('[data-cart-set]');
    if (setBtn) {
      await sync(api('set', { id: setBtn.dataset.cartSet, qty: setBtn.dataset.qty }));
      return;
    }

    const remBtn = e.target.closest('[data-cart-remove]');
    if (remBtn) {
      await sync(api('remove', { id: remBtn.dataset.cartRemove }));
      return;
    }
  });

  /* ---------- Boot ---------- */
  sync(api('get'));
});