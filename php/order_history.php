<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Orders — Spice & Soul</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --brand:    #C8460A;
  --brand-dk: #1A0F0A;
  --brand-md: #8B6355;
  --cream:    #FFFAF6;
  --border:   #EDD8C8;
  --green:    #2C7A2C;
  --amber:    #856404;
}

body {
  font-family: 'DM Sans', sans-serif;
  background: var(--cream);
  min-height: 100vh;
  color: var(--brand-dk);
}

/* ── Navbar ── */
.navbar {
  background: var(--brand-dk);
  padding: 1rem 2rem;
  display: flex; align-items: center; justify-content: space-between;
}
.nav-brand {
  font-family: 'Playfair Display', serif;
  font-size: 1.4rem; color: #fff; font-weight: 900;
  text-decoration: none;
}
.nav-back {
  color: var(--border); font-size: 0.9rem;
  text-decoration: none;
  display: flex; align-items: center; gap: 0.4rem;
  transition: color 0.2s;
}
.nav-back:hover { color: #fff; }

/* ── Hero strip ── */
.hero-strip {
  background: linear-gradient(135deg, var(--brand-dk) 0%, #3D1A0A 100%);
  padding: 3rem 2rem;
  text-align: center;
  color: #fff;
}
.hero-strip .tag {
  font-size: 0.75rem; letter-spacing: 3px;
  text-transform: uppercase; color: var(--border);
  margin-bottom: 0.5rem;
}
.hero-strip h1 {
  font-family: 'Playfair Display', serif;
  font-size: 2.2rem; font-weight: 900;
  margin-bottom: 0.5rem;
}
.hero-strip p { color: #c9a899; font-size: 0.95rem; }

/* ── Search box ── */
.search-wrap {
  max-width: 500px; margin: -1.5rem auto 2rem;
  padding: 0 1rem;
  position: relative; z-index: 10;
}
.search-card {
  background: #fff;
  border-radius: 20px;
  padding: 1.8rem;
  box-shadow: 0 20px 60px rgba(0,0,0,0.15);
}
.search-card label {
  display: block; font-weight: 600; font-size: 0.85rem;
  color: var(--brand-md); margin-bottom: 0.6rem;
  text-transform: uppercase; letter-spacing: 1px;
}
.search-row {
  display: flex; gap: 0.6rem;
}
.search-row input {
  flex: 1;
  padding: 0.85rem 1rem;
  border: 2px solid var(--border);
  border-radius: 12px;
  font-family: 'DM Sans', sans-serif;
  font-size: 1rem;
  outline: none;
  background: var(--cream);
  transition: border-color 0.2s;
}
.search-row input:focus { border-color: var(--brand); background: #fff; }
.search-btn {
  padding: 0.85rem 1.4rem;
  background: var(--brand); color: #fff;
  border: none; border-radius: 12px;
  font-weight: 700; font-size: 0.95rem;
  cursor: pointer;
  transition: background 0.2s, transform 0.1s;
  white-space: nowrap;
}
.search-btn:hover { background: #E85D1E; transform: translateY(-1px); }

/* ── Results area ── */
.results {
  max-width: 700px; margin: 0 auto;
  padding: 0 1rem 3rem;
}

.state-box {
  text-align: center; padding: 3rem 1rem;
  color: var(--brand-md);
}
.state-box .state-icon { font-size: 3rem; display: block; margin-bottom: 0.8rem; }
.state-box h3 { font-size: 1.2rem; margin-bottom: 0.3rem; color: var(--brand-dk); }
.state-box p  { font-size: 0.9rem; }

/* ── Order card ── */
.orders-found {
  font-size: 0.85rem; color: var(--brand-md);
  margin-bottom: 1rem; font-weight: 500;
}

.order-card {
  background: #fff;
  border-radius: 16px;
  border: 1.5px solid var(--border);
  margin-bottom: 1.2rem;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0,0,0,0.06);
  animation: cardIn 0.3s ease both;
}
@keyframes cardIn {
  from { opacity: 0; transform: translateY(12px); }
  to   { opacity: 1; transform: translateY(0); }
}
.order-card:nth-child(2) { animation-delay: 0.05s; }
.order-card:nth-child(3) { animation-delay: 0.10s; }
.order-card:nth-child(4) { animation-delay: 0.15s; }

.card-head {
  padding: 1.1rem 1.4rem;
  display: flex; align-items: center; justify-content: space-between;
  flex-wrap: wrap; gap: 0.5rem;
  border-bottom: 1px solid #f5ece4;
}
.card-head-left { display: flex; align-items: center; gap: 0.8rem; }
.order-num {
  font-size: 1rem; font-weight: 700; color: var(--brand-dk);
}
.order-date { font-size: 0.78rem; color: #bbb; }

/* Status badges */
.badge {
  display: inline-flex; align-items: center; gap: 4px;
  padding: 4px 12px; border-radius: 20px;
  font-size: 0.72rem; font-weight: 700;
  text-transform: uppercase; letter-spacing: 0.5px;
}
.badge-pending    { background:#FFF3CD; color:#856404; border:1px solid #FFE69C; }
.badge-preparing  { background:#CCE5FF; color:#004085; border:1px solid #B8DAFF; }
.badge-served     { background:#D4EDDA; color:#155724; border:1px solid #C3E6CB; }
.badge-cancelled  { background:#F8D7DA; color:#721C24; border:1px solid #F5C6CB; }
.badge-paid       { background:#D4EDDA; color:#155724; border:1px solid #C3E6CB; }
.badge-unpaid     { background:#FFF3CD; color:#856404; border:1px solid #FFE69C; }

.card-body-inner { padding: 1.1rem 1.4rem; }

.items-list { margin-bottom: 1rem; }
.item-row {
  display: flex; justify-content: space-between;
  font-size: 0.88rem; padding: 0.25rem 0;
  color: #555;
}
.item-row .item-name { color: var(--brand-dk); }
.item-row .item-price { font-weight: 600; }

.divider { border: none; border-top: 1px dashed var(--border); margin: 0.8rem 0; }

.card-footer-row {
  display: flex; justify-content: space-between; align-items: center;
  flex-wrap: wrap; gap: 0.5rem;
}
.total-label { font-size: 0.85rem; color: var(--brand-md); }
.total-amount { font-size: 1.3rem; font-weight: 700; color: var(--brand); }

.payment-row {
  display: flex; align-items: center; gap: 0.6rem;
  margin-top: 0.6rem;
}
.paid-at {
  font-size: 0.75rem; color: #999;
}

/* ── Table chip ── */
.table-chip {
  display: inline-flex; align-items: center; gap: 4px;
  background: #FFF3ED; border: 1px solid var(--border);
  border-radius: 8px; padding: 2px 10px;
  font-size: 0.78rem; color: var(--brand-md);
}

/* ── Spinner ── */
.spinner {
  width: 32px; height: 32px;
  border: 3px solid var(--border);
  border-top-color: var(--brand);
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
  margin: 0 auto 1rem;
}
@keyframes spin { to { transform: rotate(360deg); } }
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
  <a href="index.php" class="nav-brand">🍽 Spice &amp; Soul</a>
  <a href="index.php" class="nav-back">← Back to Menu</a>
</nav>

<!-- Hero -->
<div class="hero-strip">
  <p class="tag">Order Tracking</p>
  <h1>My Orders</h1>
  <p>Enter your phone number to see your order history</p>
</div>

<!-- Search -->
<div class="search-wrap">
  <div class="search-card">
    <label>📱 Your Phone Number</label>
    <div class="search-row">
      <input
        type="tel"
        id="phoneInput"
        placeholder="e.g. 9876543210"
        maxlength="15"
        onkeydown="if(event.key==='Enter') searchOrders()"
      >
      <button class="search-btn" onclick="searchOrders()">View Orders</button>
    </div>
  </div>
</div>

<!-- Results -->
<div class="results" id="results">
  <div class="state-box">
    <span class="state-icon">🍽</span>
    <h3>Find Your Orders</h3>
    <p>Enter the phone number you used while placing your order</p>
  </div>
</div>

<script>
async function searchOrders() {
  const phone = document.getElementById('phoneInput').value.trim();
  const results = document.getElementById('results');

  if (!phone) {
    document.getElementById('phoneInput').focus();
    return;
  }

  // Loading state
  results.innerHTML = `
    <div class="state-box">
      <div class="spinner"></div>
      <p>Looking up your orders…</p>
    </div>`;

  try {
    const res  = await fetch('php/order_history.php?phone=' + encodeURIComponent(phone));
    const data = await res.json();

    if (!data.success || !data.orders.length) {
      results.innerHTML = `
        <div class="state-box">
          <span class="state-icon">🔍</span>
          <h3>No Orders Found</h3>
          <p>We couldn't find any orders for <strong>${escHtml(phone)}</strong>.<br>
          Make sure you used the same number while ordering.</p>
        </div>`;
      return;
    }

    const orders = data.orders;
    results.innerHTML =
      `<p class="orders-found">Found ${orders.length} order${orders.length>1?'s':''} for ${escHtml(phone)}</p>` +
      orders.map(renderOrder).join('');

  } catch (err) {
    results.innerHTML = `
      <div class="state-box">
        <span class="state-icon">⚠️</span>
        <h3>Something went wrong</h3>
        <p>Could not connect to server. Please try again.</p>
      </div>`;
  }
}

function renderOrder(o) {
  const statusClass = {
    pending:   'badge-pending',
    preparing: 'badge-preparing',
    served:    'badge-served',
    cancelled: 'badge-cancelled'
  }[o.status] || 'badge-pending';

  const statusIcon = {
    pending:   '⏳',
    preparing: '👨‍🍳',
    served:    '✅',
    cancelled: '❌'
  }[o.status] || '⏳';

  const payClass = o.payment_status === 'paid' ? 'badge-paid' : 'badge-unpaid';
  const payIcon  = o.payment_status === 'paid' ? '✅' : '⏳';
  const payLabel = o.payment_status === 'paid' ? 'Paid'       : 'Unpaid';

  const paidAt = o.paid_at
    ? `<span class="paid-at">Paid at ${formatDate(o.paid_at)}</span>`
    : '';

  const tableChip = o.table_number
    ? `<span class="table-chip">🪑 Table ${o.table_number}</span>`
    : '';

  const itemsHtml = o.items.map(i => `
    <div class="item-row">
      <span class="item-name">${escHtml(i.name)} × ${i.quantity}</span>
      <span class="item-price">₹${(parseFloat(i.price) * parseInt(i.quantity)).toFixed(2)}</span>
    </div>`).join('');

  return `
    <div class="order-card">
      <div class="card-head">
        <div class="card-head-left">
          <span class="order-num">Order #${o.id}</span>
          ${tableChip}
        </div>
        <span class="order-date">${formatDate(o.created_at)}</span>
      </div>
      <div class="card-body-inner">
        <div class="items-list">${itemsHtml}</div>
        <hr class="divider">
        <div class="card-footer-row">
          <div>
            <div class="total-label">Total Amount</div>
            <div class="total-amount">₹${parseFloat(o.total_amount).toFixed(2)}</div>
          </div>
          <div style="text-align:right">
            <div style="margin-bottom:6px">
              <span class="badge ${statusClass}">${statusIcon} ${o.status}</span>
            </div>
            <div class="payment-row" style="justify-content:flex-end">
              <span class="badge ${payClass}">${payIcon} ${payLabel}</span>
            </div>
            ${paidAt}
          </div>
        </div>
      </div>
    </div>`;
}

function formatDate(str) {
  if (!str) return '—';
  const d = new Date(str);
  return d.toLocaleDateString('en-IN', { day:'2-digit', month:'short', year:'numeric' })
       + ' · '
       + d.toLocaleTimeString('en-IN', { hour:'2-digit', minute:'2-digit' });
}

function escHtml(s) {
  return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}
</script>

</body>
</html>