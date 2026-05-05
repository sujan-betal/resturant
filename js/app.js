// ===== STATE =====
let cart = [];
let allItems = [];

// ===== INIT =====
document.addEventListener('DOMContentLoaded', () => {
  loadCategories();
  loadMenu();
});

// ===== LOAD CATEGORIES =====
async function loadCategories() {
  try {
    const res = await fetch('php/menu.php?action=categories');
    const cats = await res.json();
    const tabs = document.getElementById('categoryTabs');
    cats.forEach(cat => {
      const btn = document.createElement('button');
      btn.className = 'tab';
      btn.dataset.id = cat.id;
      btn.textContent = `${cat.icon} ${cat.name}`;
      btn.onclick = () => filterMenu(cat.id, btn);
      tabs.appendChild(btn);
    });
  } catch (e) { console.error('Category load error', e); }
}

// ===== LOAD MENU =====
async function loadMenu(categoryId = null) {
  const grid = document.getElementById('menuGrid');
  grid.innerHTML = '<div class="loading">Loading delicious items... 🍽</div>';
  try {
    const url = categoryId && categoryId !== 'all'
      ? `php/menu.php?action=menu&category=${categoryId}`
      : 'php/menu.php?action=menu';
    const res = await fetch(url);
    allItems = await res.json();
    renderMenu(allItems);
  } catch (e) {
    grid.innerHTML = '<div class="loading">⚠️ Could not load menu. Check PHP connection.</div>';
  }
}

// ===== RENDER MENU =====
function renderMenu(items) {
  const grid = document.getElementById('menuGrid');
  if (!items.length) {
    grid.innerHTML = '<div class="loading">No items found 🍽</div>';
    return;
  }
  grid.innerHTML = items.map(item => `
    <div class="menu-card">
      <img class="card-img" src="${item.image_url}" alt="${item.name}" onerror="this.src='https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400'">
      <div class="card-body">
        <p class="card-category">${item.icon} ${item.category_name}</p>
        <h3 class="card-name">${item.name}</h3>
        <p class="card-desc">${item.description}</p>
        <div class="card-footer">
          <span class="card-price">₹${parseFloat(item.price).toFixed(2)}</span>
          <button class="add-btn" onclick="addToCart(${item.id}, '${escHtml(item.name)}', ${item.price})">+ Add</button>
        </div>
      </div>
    </div>
  `).join('');
}

function escHtml(str) { return str.replace(/'/g, "\\'"); }

// ===== FILTER =====
function filterMenu(categoryId, btn) {
  document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
  btn.classList.add('active');
  loadMenu(categoryId === 'all' ? null : categoryId);
}

// ===== CART =====
function addToCart(id, name, price) {
  const existing = cart.find(c => c.id === id);
  if (existing) {
    existing.qty++;
  } else {
    cart.push({ id, name, price: parseFloat(price), qty: 1 });
  }
  updateCartUI();
  showToast(`✅ ${name} added to cart!`);
}

function updateCartUI() {
  const count = cart.reduce((s, c) => s + c.qty, 0);
  document.getElementById('cartCount').textContent = count;

  const cartItems = document.getElementById('cartItems');
  const cartFooter = document.getElementById('cartFooter');

  if (!cart.length) {
    cartItems.innerHTML = '<div class="empty-cart">Your cart is empty<br>🍽 Add something tasty!</div>';
    cartFooter.style.display = 'none';
    return;
  }

  cartItems.innerHTML = cart.map(item => `
    <div class="cart-item">
      <div class="cart-item-name">${item.name}</div>
      <div class="qty-controls">
        <button class="qty-btn" onclick="changeQty(${item.id}, -1)">−</button>
        <span class="qty-num">${item.qty}</span>
        <button class="qty-btn" onclick="changeQty(${item.id}, 1)">+</button>
      </div>
      <div class="cart-item-price">₹${(item.price * item.qty).toFixed(2)}</div>
    </div>
  `).join('');

  const total = cart.reduce((s, c) => s + c.price * c.qty, 0);
  document.getElementById('cartTotal').textContent = `₹${total.toFixed(2)}`;
  cartFooter.style.display = 'block';
}

function changeQty(id, delta) {
  const item = cart.find(c => c.id === id);
  if (!item) return;
  item.qty += delta;
  if (item.qty <= 0) cart = cart.filter(c => c.id !== id);
  updateCartUI();
}

function toggleCart() {
  document.getElementById('cartSidebar').classList.toggle('open');
  document.getElementById('cartOverlay').classList.toggle('open');
}

// ===== ORDER MODAL =====
function showOrderForm() {
  const summary = document.getElementById('orderSummary');
  const total = cart.reduce((s, c) => s + c.price * c.qty, 0);
  summary.innerHTML = cart.map(c =>
    `<div style="display:flex;justify-content:space-between;padding:0.3rem 0">
      <span>${c.name} × ${c.qty}</span>
      <strong>₹${(c.price * c.qty).toFixed(2)}</strong>
    </div>`
  ).join('') + `<hr style="margin:0.5rem 0;border-color:#EDD8C8">
    <div style="display:flex;justify-content:space-between;font-weight:700">
      <span>Total</span><span>₹${total.toFixed(2)}</span>
    </div>`;
  document.getElementById('orderModal').classList.add('open');
}

function closeModal() {
  document.getElementById('orderModal').classList.remove('open');
}

// ===== PLACE ORDER =====
document.getElementById('orderForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const name = document.getElementById('custName').value;
  const phone = document.getElementById('custPhone').value;
  const table = document.getElementById('tableNo').value;

  if (cart.length === 0) {
    showToast('❌ Your cart is empty!');
    return;
  }

  // Calculate total before clearing the cart
  const orderTotal = cart.reduce((s, c) => s + c.price * c.qty, 0);

  const payload = {
    name, phone, table,
    items: cart.map(c => ({ id: c.id, qty: c.qty, price: c.price }))
  };

  try {
    const res = await fetch('php/order.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const data = await res.json();
    
    if (data.success) {
      cart = []; // Clear the cart
      updateCartUI();
      closeModal();
      toggleCart();
      document.getElementById('orderForm').reset();
      
      // ✅ NAYA CODE: Success hone par direct HTML wala QR Modal open karein
      if (typeof showPaymentQR === 'function') {
        const finalTotal = data.total || orderTotal; 
        showPaymentQR(data.order_id, finalTotal);
      } else {
        showToast(`🎉 Order #${data.order_id} placed! Total: ₹${data.total}`);
      }

    } else {
      showToast('❌ Order failed. Please try again.');
    }
  } catch (err) {
    console.error(err);
    showToast('❌ Network error. Check server connection.');
  }
});

// ===== TOAST =====
function showToast(msg) {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 3000);
}