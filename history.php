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

/* ── Live indicator bar ── */
.live-bar {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 1rem;
  flex-wrap: wrap; gap: 0.4rem;
}
.orders-found {
  font-size: 0.85rem; color: var(--brand-md);
  font-weight: 500;
}
.live-dot-wrap {
  display: flex; align-items: center; gap: 0.4rem;
  font-size: 0.75rem; color: #aaa;
}
.live-dot {
  width: 8px; height: 8px; border-radius: 50%;
  background: #28a745;
  animation: livePulse 2s ease-in-out infinite;
}
@keyframes livePulse {
  0%,100% { opacity: 1; transform: scale(1); }
  50%      { opacity: 0.4; transform: scale(0.8); }
}

/* ── Order card ── */
.order-card {
  background: #fff;
  border-radius: 16px;
  border: 1.5px solid var(--border);
  margin-bottom: 1.2rem;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0,0,0,0.06);
  animation: cardIn 0.3s ease both;
  transition: border-color 0.4s;
}
@keyframes cardIn {
  from { opacity: 0; transform: translateY(12px); }
  to   { opacity: 1; transform: translateY(0); }
}
.order-card:nth-child(2) { animation-delay: 0.05s; }
.order-card:nth-child(3) { animation-delay: 0.10s; }
.order-card:nth-child(4) { animation-delay: 0.15s; }

/* Card flash when status updates */
.order-card.status-changed {
  border-color: #28a745;
  box-shadow: 0 0 0 3px rgba(40,167,69,0.15);
}

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
  transition: all 0.3s;
}
.badge-pending    { background:#FFF3CD; color:#856404; border:1px solid #FFE69C; }
.badge-preparing  { background:#CCE5FF; color:#004085; border:1px solid #B8DAFF; }
.badge-served     { background:#D4EDDA; color:#155724; border:1px solid #C3E6CB; }
.badge-cancelled  { background:#F8D7DA; color:#721C24; border:1px solid #F5C6CB; }
.badge-paid       { background:#D4EDDA; color:#155724; border:1px solid #C3E6CB; }
.badge-unpaid     { background:#FFF3CD; color:#856404; border:1px solid #FFE69C; }

/* ── Badge pulse animation on update ── */
@keyframes badgePop {
  0%   { transform: scale(1); }
  30%  { transform: scale(1.2); }
  60%  { transform: scale(0.95); }
  100% { transform: scale(1); }
}
.badge-updated {
  animation: badgePop 0.5s ease;
}

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

/* ── Status update toast ── */
.status-toast {
  position: fixed; bottom: 1.5rem; left: 50%;
  transform: translateX(-50%) translateY(20px);
  background: #1A0F0A; color: #fff;
  padding: 0.7rem 1.4rem;
  border-radius: 50px;
  font-size: 0.85rem; font-weight: 600;
  z-index: 99999;
  opacity: 0;
  transition: all 0.35s;
  white-space: nowrap;
  pointer-events: none;
  box-shadow: 0 8px 30px rgba(0,0,0,0.3);
}
.status-toast.show {
  opacity: 1; transform: translateX(-50%) translateY(0);
}
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

<!-- Status update toast -->
<div class="status-toast" id="statusToast"></div>

<script>
// ══════════════════════════════════════════════════════
//  LIVE STATUS POLLING — updates without page refresh
// ══════════════════════════════════════════════════════

let pollTimer   = null;    // interval handle
let liveOrders  = [];      // latest known order data
let currentPhone = '';     // phone used for last search

const STATUS_INFO = {
  pending   : { cls: 'badge-pending',   icon: '⏳', label: 'Pending'   },
  preparing : { cls: 'badge-preparing', icon: '👨‍🍳', label: 'Preparing' },
  served    : { cls: 'badge-served',    icon: '✅', label: 'Served'    },
  cancelled : { cls: 'badge-cancelled', icon: '❌', label: 'Cancelled' },
};

/* ── Search orders (called by button) ── */
async function searchOrders() {
  const phone = document.getElementById('phoneInput').value.trim();
  const results = document.getElementById('results');
  if (!phone) { document.getElementById('phoneInput').focus(); return; }

  stopPolling(); // stop any existing poll

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

    currentPhone = phone;
    liveOrders   = data.orders;
    renderAllOrders();
    startPolling();                      // ← begin live updates

  } catch (err) {
    results.innerHTML = `
      <div class="state-box">
        <span class="state-icon">⚠️</span>
        <h3>Something went wrong</h3>
        <p>Could not connect to server. Please try again.</p>
      </div>`;
  }
}

/* ── Render all orders into the results div ── */
function renderAllOrders() {
  const results = document.getElementById('results');
  results.innerHTML =
    `<div class="live-bar">
       <p class="orders-found">Found ${liveOrders.length} order${liveOrders.length > 1 ? 's' : ''} for ${escHtml(currentPhone)}</p>
       <div class="live-dot-wrap">
         <span class="live-dot"></span>
         <span id="liveLabel">Live updates on</span>
       </div>
     </div>` +
    liveOrders.map(renderOrder).join('');
}

/* ── Start polling every 5 seconds ── */
function startPolling() {
  if (pollTimer) clearInterval(pollTimer);
  pollTimer = setInterval(pollStatuses, 5000);
}

/* ── Stop polling ── */
function stopPolling() {
  if (pollTimer) { clearInterval(pollTimer); pollTimer = null; }
  currentPhone = '';
  liveOrders   = [];
}

/* ── Poll: fetch latest statuses and diff ── */
async function pollStatuses() {
  if (!currentPhone) return;
  try {
    const res  = await fetch('php/order_history.php?phone=' + encodeURIComponent(currentPhone) + '&_t=' + Date.now());
    const data = await res.json();
    if (!data.success) return;

    data.orders.forEach(fresh => {
      const old = liveOrders.find(o => o.id === fresh.id);
      if (!old) return;

      let changed = false;

      // ── Order status changed? ──
      if (old.status !== fresh.status) {
        old.status = fresh.status;
        changed = true;
        applyStatusBadge('statusBadge-' + fresh.id, fresh.status);
        flashCard(fresh.id);
        showStatusToast('Order #' + fresh.id + ' is now ' + (STATUS_INFO[fresh.status]?.icon || '') + ' ' + (fresh.status.charAt(0).toUpperCase() + fresh.status.slice(1)));
      }

      // ── Payment status changed? ──
      if (old.payment_status !== fresh.payment_status) {
        old.payment_status = fresh.payment_status;
        old.paid_at        = fresh.paid_at;
        changed = true;
        applyPaymentBadge('payBadge-' + fresh.id, fresh.payment_status);
        if (fresh.paid_at) {
          const el = document.getElementById('paidAt-' + fresh.id);
          if (el) el.textContent = 'Paid at ' + formatDate(fresh.paid_at);
        }
        if (fresh.payment_status === 'paid') {
          showStatusToast('💚 Payment confirmed for Order #' + fresh.id);
        }
      }
    });

  } catch(e) {
    // network hiccup — keep polling silently
  }
}

/* ── Update status badge in DOM ── */
function applyStatusBadge(id, status) {
  const el = document.getElementById(id);
  if (!el) return;
  const info = STATUS_INFO[status] || STATUS_INFO.pending;
  el.className = 'badge ' + info.cls + ' badge-updated';
  el.innerHTML = info.icon + ' ' + info.label;
  setTimeout(() => el.classList.remove('badge-updated'), 600);
}

/* ── Update payment badge in DOM ── */
function applyPaymentBadge(id, payStatus) {
  const el = document.getElementById(id);
  if (!el) return;
  const isPaid = payStatus === 'paid';
  el.className = 'badge ' + (isPaid ? 'badge-paid' : 'badge-unpaid') + ' badge-updated';
  el.innerHTML = (isPaid ? '✅ Paid' : '⏳ Unpaid');
  setTimeout(() => el.classList.remove('badge-updated'), 600);
}

/* ── Flash card border green on any change ── */
function flashCard(orderId) {
  const card = document.getElementById('orderCard-' + orderId);
  if (!card) return;
  card.classList.add('status-changed');
  setTimeout(() => card.classList.remove('status-changed'), 2000);
}

/* ── Show bottom toast ── */
function showStatusToast(msg) {
  const t = document.getElementById('statusToast');
  t.textContent = msg;
  t.classList.add('show');
  clearTimeout(t._tmr);
  t._tmr = setTimeout(() => t.classList.remove('show'), 3500);
}

/* ── Render a single order card ──
   Added id="statusBadge-X", id="payBadge-X", id="paidAt-X", id="orderCard-X"
   so pollStatuses() can find and update them in the DOM.
── */
function renderOrder(o) {
  const statusInfo = STATUS_INFO[o.status] || STATUS_INFO.pending;
  const isPaid  = o.payment_status === 'paid';

  const paidAt = o.paid_at
    ? `<span class="paid-at" id="paidAt-${o.id}">Paid at ${formatDate(o.paid_at)}</span>`
    : `<span class="paid-at" id="paidAt-${o.id}"></span>`;

  const tableChip = o.table_number
    ? `<span class="table-chip">🪑 Table ${o.table_number}</span>`
    : '';

  const itemsHtml = o.items.map(i => `
    <div class="item-row">
      <span class="item-name">${escHtml(i.name)} × ${i.quantity}</span>
      <span class="item-price">₹${(parseFloat(i.price) * parseInt(i.quantity)).toFixed(2)}</span>
    </div>`).join('');

  return `
    <div class="order-card" id="orderCard-${o.id}">
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
              <span class="badge ${statusInfo.cls}" id="statusBadge-${o.id}">${statusInfo.icon} ${o.status}</span>
            </div>
            <div class="payment-row" style="justify-content:flex-end">
              <span class="badge ${isPaid ? 'badge-paid' : 'badge-unpaid'}" id="payBadge-${o.id}">${isPaid ? '✅ Paid' : '⏳ Unpaid'}</span>
            </div>
            ${paidAt}
          </div>
        </div>
      </div>
    </div>`;
}

/* ── Helpers ── */
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

// Stop polling when user leaves the page
window.addEventListener('pagehide', stopPolling);
window.addEventListener('beforeunload', stopPolling);
</script>

</body>
</html>