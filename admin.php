<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel - Spice & Soul</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/admin.css">
<style>
/* ── Payment badge & button ── */
.badge-paid   { display:inline-block; background:#D4EDDA; color:#155724; border:1px solid #C3E6CB; padding:3px 10px; border-radius:20px; font-size:.75rem; font-weight:700; white-space:nowrap; }
.badge-unpaid { display:inline-block; background:#FFF3CD; color:#856404; border:1px solid #FFE69C; padding:3px 10px; border-radius:20px; font-size:.75rem; font-weight:700; white-space:nowrap; }
.btn-mark-paid { background:#2C7A2C; color:white; border:none; border-radius:20px; padding:5px 12px; font-size:.75rem; font-weight:700; cursor:pointer; transition:background .2s,transform .1s; white-space:nowrap; margin-top:5px; display:block; }
.btn-mark-paid:hover     { background:#3A9A3A; transform:translateY(-1px); }
.btn-mark-paid:disabled  { background:#aaa; cursor:not-allowed; transform:none; }
.paid-at { font-size:.68rem; color:#888; display:block; margin-top:2px; }
tr.row-paid   { background:#F0FFF0 !important; }
tr.row-unpaid { background:#FFFEF6 !important; }

/* ════════════════════════════════════
   NEW ORDER — blinking green dot
════════════════════════════════════ */
.new-order-row { animation: rowFlash 1.2s ease-in-out 3; }
@keyframes rowFlash {
  0%,100% { background: #F0FFF0; }
  50%      { background: #b2f0b2; }
}
.new-dot {
  display: inline-block;
  width: 10px; height: 10px;
  border-radius: 50%;
  background: #22c55e;
  margin-right: 5px;
  box-shadow: 0 0 0 0 rgba(34,197,94,.6);
  animation: greenPulse 1.2s ease-in-out infinite;
  vertical-align: middle;
}
@keyframes greenPulse {
  0%   { box-shadow: 0 0 0 0 rgba(34,197,94,.7); }
  70%  { box-shadow: 0 0 0 8px rgba(34,197,94,0); }
  100% { box-shadow: 0 0 0 0 rgba(34,197,94,0); }
}

/* ════════════════════════════════════
   NEW ORDER NOTIFICATION BANNER
════════════════════════════════════ */
.new-order-banner {
  position: fixed;
  top: 1.2rem; left: 50%; transform: translateX(-50%) translateY(-100px);
  z-index: 99999;
  background: linear-gradient(135deg,#1a4d1a,#2C7A2C);
  color: #fff;
  border-radius: 16px;
  padding: .9rem 1.6rem;
  display: flex; align-items: center; gap: .8rem;
  box-shadow: 0 8px 40px rgba(34,197,94,.35);
  font-family: 'DM Sans', sans-serif;
  font-size: .95rem; font-weight: 600;
  transition: transform .45s cubic-bezier(.34,1.56,.64,1), opacity .35s;
  opacity: 0;
  min-width: 280px;
  pointer-events: none;
}
.new-order-banner.show { transform: translateX(-50%) translateY(0); opacity: 1; pointer-events: auto; }
.new-order-banner .nb-icon { font-size: 1.5rem; }
.new-order-banner .nb-close { margin-left:auto; background:rgba(255,255,255,.2); border:none; color:#fff; border-radius:8px; width:28px; height:28px; cursor:pointer; font-size:1rem; display:flex;align-items:center;justify-content:center; pointer-events:auto; }

/* ════════════════════════════════════
   CANCEL WITH REASON MODAL
════════════════════════════════════ */
.cancel-modal-overlay {
  position:fixed; inset:0;
  background:rgba(0,0,0,.6);
  display:flex; align-items:center; justify-content:center;
  z-index:999999;
  opacity:0; pointer-events:none;
  transition:opacity .25s;
  padding:1rem;
}
.cancel-modal-overlay.open { opacity:1; pointer-events:all; }
.cancel-modal {
  background:#fff; border-radius:20px;
  padding:2rem 1.8rem; max-width:440px; width:100%;
  box-shadow:0 30px 80px rgba(0,0,0,.35);
  transform:scale(.88) translateY(16px);
  transition:transform .35s cubic-bezier(.34,1.4,.64,1);
  font-family:'DM Sans',sans-serif;
}
.cancel-modal-overlay.open .cancel-modal { transform:scale(1) translateY(0); }
.cancel-modal .cm-icon   { font-size:2.5rem; display:block; text-align:center; margin-bottom:.5rem; }
.cancel-modal h3         { text-align:center; font-size:1.3rem; font-weight:700; color:#1A0F0A; margin-bottom:.25rem; }
.cancel-modal .cm-sub    { text-align:center; font-size:.85rem; color:#8B6355; margin-bottom:1.2rem; }
.cancel-modal label      { font-size:.8rem; font-weight:600; color:#2C1810; display:block; margin-bottom:.4rem; }
.cm-reason-grid          { display:grid; grid-template-columns:1fr 1fr; gap:.5rem; margin-bottom:.9rem; }
.cm-reason-chip {
  padding:.55rem .7rem;
  border:1.5px solid #EDD8C8;
  border-radius:10px;
  font-size:.78rem; font-weight:600;
  background:#FFFAF6; color:#2C1810;
  cursor:pointer; transition:all .18s; text-align:center;
}
.cm-reason-chip:hover { border-color:#C8460A; background:#FFF3ED; }
.cm-reason-chip.selected { border-color:#C8460A; background:#C8460A; color:#fff; }
.cm-custom-input {
  width:100%; padding:.7rem .9rem;
  border:1.5px solid #EDD8C8; border-radius:10px;
  font-family:'DM Sans',sans-serif; font-size:.9rem;
  outline:none; background:#FFFAF6; resize:none;
  transition:border-color .2s;
  margin-bottom:.9rem;
}
.cm-custom-input:focus { border-color:#C8460A; background:#fff; }
.cm-refund-box {
  background:#FFF3CD; border:1px solid #FFE69C;
  border-radius:10px; padding:.8rem 1rem;
  font-size:.82rem; color:#856404;
  margin-bottom:1.1rem; line-height:1.6;
}
.cm-refund-box strong { color:#2C1810; }
.cm-actions { display:flex; gap:.7rem; }
.cm-btn { flex:1; padding:.75rem; border-radius:50px; font-weight:700; font-size:.9rem; cursor:pointer; border:none; font-family:'DM Sans',sans-serif; transition:background .2s,transform .1s; }
.cm-btn:hover { transform:translateY(-1px); }
.cm-btn-back   { background:#F0EBE7; color:#2C1810; }
.cm-btn-back:hover { background:#e5ddd7; }
.cm-btn-cancel { background:#C8460A; color:#fff; }
.cm-btn-cancel:hover { background:#E85D1E; }
.cm-btn-cancel:disabled { background:#aaa; cursor:not-allowed; transform:none; }

/* ── Admin toast ── */
.admin-toast {
  position:fixed; bottom:2rem; right:2rem;
  background:#1A0F0A; color:#fff;
  padding:.85rem 1.5rem; border-radius:12px;
  font-size:.9rem; box-shadow:0 8px 30px rgba(0,0,0,.3);
  z-index:99999; opacity:0; transform:translateY(20px);
  transition:all .3s; pointer-events:none; font-family:'DM Sans',sans-serif;
}
.admin-toast.show { opacity:1; transform:translateY(0); }

/* ── Cancel btn in table ── */
.btn-cancel-order {
  background:#C8460A; color:#fff;
  border:none; border-radius:20px;
  padding:5px 12px; font-size:.73rem;
  font-weight:700; cursor:pointer;
  transition:background .2s; white-space:nowrap;
  margin-top:4px; display:block;
}
.btn-cancel-order:hover    { background:#E85D1E; }
.btn-cancel-order:disabled { background:#ccc; cursor:not-allowed; }

/* ── Sidebar & responsive ── */
.sidebar-toggle { display:none; position:fixed; top:1rem; left:1rem; z-index:1100; background:#1A0F0A; color:#fff; border:none; border-radius:10px; width:42px; height:42px; font-size:1.2rem; cursor:pointer; align-items:center; justify-content:center; box-shadow:0 4px 16px rgba(0,0,0,.3); transition:background .2s; }
.sidebar-toggle:hover { background:#3D1A0A; }
.sidebar-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.55); z-index:999; }
.sidebar-overlay.open { display:block; }
@media (max-width:900px) {
  .admin-layout { grid-template-columns:1fr; }
  .sidebar { position:fixed; top:0; left:0; height:100vh; transform:translateX(-100%); transition:transform .3s cubic-bezier(.4,0,.2,1); z-index:1000; box-shadow:6px 0 30px rgba(0,0,0,.3); }
  .sidebar.open { transform:translateX(0); }
  .admin-main { padding:1rem .8rem; padding-top:4rem; }
  .admin-header { margin-bottom:1rem; gap:.5rem; }
  .admin-header h1 { font-size:1.3rem; }
  .sidebar-toggle { display:flex; }
  .stats-grid { grid-template-columns:repeat(2,1fr); gap:.7rem; }
  .stat-card { padding:.9rem 1rem; }
}
@media (max-width:600px) {
  .stats-grid { grid-template-columns:repeat(2,1fr); gap:.6rem; }
  .table-wrapper { overflow-x:auto; -webkit-overflow-scrolling:touch; border-radius:12px; }
  .admin-table { min-width:780px; font-size:.8rem; }
  .admin-table th,.admin-table td { padding:.6rem .7rem; }
  .badge-paid,.badge-unpaid { font-size:.7rem; padding:2px 8px; }
  .btn-mark-paid,.btn-cancel-order { font-size:.7rem; padding:4px 10px; }
  .refresh-btn { padding:.5rem .9rem; font-size:.8rem; }
  .admin-toast { bottom:1rem; right:1rem; left:1rem; font-size:.85rem; text-align:center; }
}

/* ── Logout modal ── */
.logout-modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,.55); display:flex; align-items:center; justify-content:center; z-index:999999; opacity:0; pointer-events:none; transition:opacity .25s; }
.logout-modal-overlay.open { opacity:1; pointer-events:all; }
.logout-modal { background:#fff; border-radius:20px; padding:2.5rem 2rem; max-width:380px; width:90%; text-align:center; box-shadow:0 30px 80px rgba(0,0,0,.35); transform:scale(.85) translateY(10px); transition:transform .3s cubic-bezier(.34,1.56,.64,1); font-family:'DM Sans',sans-serif; }
.logout-modal-overlay.open .logout-modal { transform:scale(1) translateY(0); }
.logout-modal .lm-icon { font-size:3rem; display:block; margin-bottom:.5rem; }
.logout-modal h3 { font-size:1.4rem; font-weight:700; color:#1A0F0A; margin-bottom:.4rem; }
.logout-modal p  { color:#8B6355; font-size:.9rem; margin-bottom:1.8rem; line-height:1.5; }
.logout-modal-actions { display:flex; gap:.8rem; justify-content:center; }
.lm-btn { flex:1; max-width:140px; padding:.75rem 1rem; border-radius:50px; font-size:.9rem; font-weight:700; cursor:pointer; transition:background .2s,transform .1s; border:none; font-family:'DM Sans',sans-serif; }
.lm-btn:hover { transform:translateY(-1px); }
.lm-btn-cancel  { background:#F0EBE7; color:#2C1810; }
.lm-btn-cancel:hover { background:#e5ddd7; }
.lm-btn-confirm { background:#C8460A; color:white; }
.lm-btn-confirm:hover { background:#E85D1E; }
</style>
</head>
<body>

<!-- Mobile sidebar toggle -->
<button class="sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()">☰</button>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- New Order Notification Banner -->
<div class="new-order-banner" id="newOrderBanner">
  <span class="nb-icon">🔔</span>
  <span id="bannerMsg">New order received!</span>
  <button class="nb-close" onclick="closeBanner()">✕</button>
</div>

<div class="admin-layout">
  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-brand">🍽 Admin Panel</div>
    <nav class="sidebar-nav">
      <a href="#" class="nav-item active" onclick="showTab('orders')">📋 Orders</a>
      <a href="#" class="nav-item" onclick="showTab('menu')">🍛 Menu Items</a>
      <a href="index.php" class="nav-item">🌐 View Site</a>
      <a href="#" class="nav-item logout-item" onclick="showLogoutConfirm()">🚪 Logout</a>
    </nav>
  </aside>

  <!-- MAIN -->
  <main class="admin-main">
    <div class="admin-header">
      <h1 id="pageTitle">Orders</h1>
      <button class="refresh-btn" onclick="loadData()">↻ Refresh</button>
    </div>

    <!-- STATS -->
    <div class="stats-grid" id="statsGrid">
      <div class="stat-card"><div class="stat-icon">📦</div><div class="stat-info"><span id="totalOrders">-</span><p>Total Orders</p></div></div>
      <div class="stat-card"><div class="stat-icon">💰</div><div class="stat-info"><span id="totalRevenue">-</span><p>Revenue</p></div></div>
      <div class="stat-card"><div class="stat-icon">⏳</div><div class="stat-info"><span id="pendingOrders">-</span><p>Pending</p></div></div>
      <div class="stat-card"><div class="stat-icon">📅</div><div class="stat-info"><span id="todayOrders">-</span><p>Today</p></div></div>
      <div class="stat-card"><div class="stat-icon">✅</div><div class="stat-info"><span id="paidRevenue">-</span><p>Paid Revenue</p></div></div>
    </div>

    <!-- ORDERS TAB -->
    <div id="ordersTab" class="tab-content active">
      <div class="table-wrapper">
        <table class="admin-table" id="ordersTable">
          <thead>
            <tr>
              <th>#ID</th>
              <th>Customer</th>
              <th>Phone</th>
              <th>Table</th>
              <th>Items</th>
              <th>Total</th>
              <th>Status</th>
              <th>💳 Payment</th>
              <th>Time</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="ordersBody">
            <tr><td colspan="10" class="loading-row">Loading orders…</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- MENU TAB -->
    <div id="menuTab" class="tab-content">
      <div class="menu-admin-grid" id="menuAdminGrid">
        <div class="loading-row">Loading menu items…</div>
      </div>
    </div>
  </main>
</div>

<!-- Admin toast -->
<div class="admin-toast" id="adminToast"></div>

<!-- ── Cancel Order Modal ── -->
<div class="cancel-modal-overlay" id="cancelModal">
  <div class="cancel-modal">
    <span class="cm-icon">⚠️</span>
    <h3>Cancel Order</h3>
    <p class="cm-sub">Order <strong id="cmOrderRef">#—</strong> · <strong id="cmOrderAmt"></strong></p>

    <label>Select reason for cancellation</label>
    <div class="cm-reason-grid" id="cmReasonGrid">
      <div class="cm-reason-chip" data-reason="Item unavailable">🚫 Item unavailable</div>
      <div class="cm-reason-chip" data-reason="Kitchen overloaded">🍳 Kitchen overloaded</div>
      <div class="cm-reason-chip" data-reason="Duplicate order">📋 Duplicate order</div>
      <div class="cm-reason-chip" data-reason="Customer request">👤 Customer request</div>
      <div class="cm-reason-chip" data-reason="Payment issue">💳 Payment issue</div>
      <div class="cm-reason-chip" data-reason="Other">✏️ Other</div>
    </div>

    <label>Additional note (optional)</label>
    <textarea class="cm-custom-input" id="cmCustomNote" rows="2" placeholder="Type any additional info for the customer…"></textarea>

    <div class="cm-refund-box" id="cmRefundBox" style="display:none">
      🔄 <strong>Refund Info:</strong> Since this order was already paid, the customer will be notified
      that their refund of <strong id="cmRefundAmt"></strong> will be processed within <strong>5–7 business days</strong>
      to the original payment method. Please arrange the refund manually.
    </div>

    <div class="cm-actions">
      <button class="cm-btn cm-btn-back" onclick="closeCancelModal()">← Go Back</button>
      <button class="cm-btn cm-btn-cancel" id="cmConfirmBtn" onclick="confirmCancel()">Cancel Order</button>
    </div>
  </div>
</div>

<!-- ── Logout Confirmation Modal ── -->
<div class="logout-modal-overlay" id="logoutModal">
  <div class="logout-modal">
    <span class="lm-icon">🚪</span>
    <h3>Confirm Logout</h3>
    <p>Are you sure you want to logout<br>from the admin panel?</p>
    <div class="logout-modal-actions">
      <button class="lm-btn lm-btn-cancel" onclick="closeLogoutConfirm()">Cancel</button>
      <button class="lm-btn lm-btn-confirm" onclick="confirmLogout()">Yes, Logout</button>
    </div>
  </div>
</div>

<script src="js/admin.js"></script>

<script>
// ═══════════════════════════════════════════════════════════════
//  SOUND — tiny beep using Web Audio API (no external files)
// ═══════════════════════════════════════════════════════════════
let _audioCtx = null;
function playNewOrderSound() {
  try {
    if (!_audioCtx) _audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    const ctx = _audioCtx;
    // Two-tone chime: 880 Hz then 1100 Hz
    [[880, 0, 0.18], [1100, 0.2, 0.18], [880, 0.42, 0.22]].forEach(([freq, start, dur]) => {
      const osc  = ctx.createOscillator();
      const gain = ctx.createGain();
      osc.connect(gain); gain.connect(ctx.destination);
      osc.frequency.value = freq;
      osc.type = 'sine';
      gain.gain.setValueAtTime(0, ctx.currentTime + start);
      gain.gain.linearRampToValueAtTime(0.38, ctx.currentTime + start + 0.04);
      gain.gain.linearRampToValueAtTime(0, ctx.currentTime + start + dur);
      osc.start(ctx.currentTime + start);
      osc.stop(ctx.currentTime + start + dur + 0.01);
    });
  } catch(e) { /* audio blocked */ }
}

// ═══════════════════════════════════════════════════════════════
//  REAL-TIME NEW ORDER POLLING
// ═══════════════════════════════════════════════════════════════
let _knownOrderIds    = new Set();
let _orderPollTimer   = null;
let _initialLoadDone  = false;

function startOrderPolling() {
  if (_orderPollTimer) clearInterval(_orderPollTimer);
  _orderPollTimer = setInterval(pollForNewOrders, 8000); // every 8s
}

async function pollForNewOrders() {
  try {
    const res  = await fetch('php/admin_api.php?action=orders&_t=' + Date.now());
    const data = await res.json();
    if (!data.orders) return;

    const newIds = data.orders.map(o => parseInt(o.id));

    if (!_initialLoadDone) {
      // First poll — just record IDs
      newIds.forEach(id => _knownOrderIds.add(id));
      _initialLoadDone = true;
      return;
    }

    // Find truly new orders
    const freshOrders = data.orders.filter(o => !_knownOrderIds.has(parseInt(o.id)));
    if (freshOrders.length === 0) return;

    freshOrders.forEach(o => _knownOrderIds.add(parseInt(o.id)));

    // Reload the orders table
    if (typeof loadData === 'function') loadData();

    // Notify admin for each new order
    freshOrders.forEach((o, i) => {
      setTimeout(() => {
        showNewOrderBanner(o);
        playNewOrderSound();
        requestBrowserNotification(o);
      }, i * 800);
    });

  } catch(e) {}
}

function showNewOrderBanner(order) {
  const banner = document.getElementById('newOrderBanner');
  document.getElementById('bannerMsg').textContent =
    '🛎 New Order #' + order.id + ' — ' + order.customer_name + ' (Table ' + (order.table_number || '?') + ')';
  banner.classList.add('show');
  clearTimeout(banner._tmr);
  banner._tmr = setTimeout(() => banner.classList.remove('show'), 8000);
}

function closeBanner() {
  document.getElementById('newOrderBanner').classList.remove('show');
}

// Browser push notification
function requestBrowserNotification(order) {
  if (!('Notification' in window)) return;
  const show = () => {
    if (Notification.permission === 'granted') {
      new Notification('🍽 New Order — Spice & Soul', {
        body: 'Order #' + order.id + ' from ' + order.customer_name +
              ' · Table ' + (order.table_number || '?') +
              ' · ₹' + parseFloat(order.total_amount).toFixed(2),
        icon: 'favicon.ico',
        tag : 'order-' + order.id
      });
    }
  };
  if (Notification.permission === 'default') {
    Notification.requestPermission().then(show);
  } else {
    show();
  }
}

// ═══════════════════════════════════════════════════════════════
//  MARK PAID
// ═══════════════════════════════════════════════════════════════
function showAdminToast(msg, ms) {
  ms = ms || 3500;
  const t = document.getElementById('adminToast');
  t.textContent = msg;
  t.classList.add('show');
  clearTimeout(t._tmr);
  t._tmr = setTimeout(() => t.classList.remove('show'), ms);
}

function markPaid(orderId, btn) {
  btn.disabled = true;
  btn.textContent = '⏳ Confirming…';
  fetch('php/payment.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'order_id=' + orderId
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      const cell = document.getElementById('payCell-' + orderId);
      if (cell) cell.innerHTML = '<span class="badge-paid">✅ Paid</span><small class="paid-at">Just now</small>';
      const row = document.getElementById('adminRow-' + orderId);
      if (row) { row.classList.remove('row-unpaid'); row.classList.add('row-paid'); }
      btn.remove();
      showAdminToast('💚 Order #' + orderId + ' marked PAID!', 4000);
    } else {
      btn.disabled = false; btn.textContent = '✅ Mark Paid';
      showAdminToast('❌ Failed: ' + (data.error || 'Try again.'));
    }
  })
  .catch(() => {
    btn.disabled = false; btn.textContent = '✅ Mark Paid';
    showAdminToast('❌ Network error. Try again.');
  });
}

// ═══════════════════════════════════════════════════════════════
//  CANCEL ORDER MODAL
// ═══════════════════════════════════════════════════════════════
let _cancelOrderId   = null;
let _cancelIsPaid    = false;
let _cancelAmt       = 0;
let _selectedReason  = '';

function openCancelModal(orderId, isPaid, amount) {
  _cancelOrderId  = orderId;
  _cancelIsPaid   = isPaid;
  _cancelAmt      = parseFloat(amount) || 0;
  _selectedReason = '';

  document.getElementById('cmOrderRef').textContent = '#' + orderId;
  document.getElementById('cmOrderAmt').textContent = '₹' + _cancelAmt.toFixed(2);
  document.getElementById('cmCustomNote').value = '';

  // Reset reason chips
  document.querySelectorAll('.cm-reason-chip').forEach(c => c.classList.remove('selected'));

  // Show refund info only if paid
  const refundBox = document.getElementById('cmRefundBox');
  if (isPaid) {
    document.getElementById('cmRefundAmt').textContent = '₹' + _cancelAmt.toFixed(2);
    refundBox.style.display = 'block';
  } else {
    refundBox.style.display = 'none';
  }

  document.getElementById('cmConfirmBtn').disabled = false;
  document.getElementById('cmConfirmBtn').textContent = 'Cancel Order';
  document.getElementById('cancelModal').classList.add('open');
}

function closeCancelModal() {
  document.getElementById('cancelModal').classList.remove('open');
}

// Reason chip selection
document.querySelectorAll('.cm-reason-chip').forEach(chip => {
  chip.addEventListener('click', function() {
    document.querySelectorAll('.cm-reason-chip').forEach(c => c.classList.remove('selected'));
    this.classList.add('selected');
    _selectedReason = this.dataset.reason;
  });
});

function confirmCancel() {
  const note   = document.getElementById('cmCustomNote').value.trim();
  const reason = _selectedReason || note || 'No reason specified';

  if (!_selectedReason && !note) {
    showAdminToast('⚠️ Please select or enter a cancellation reason.', 3000);
    return;
  }

  const btn = document.getElementById('cmConfirmBtn');
  btn.disabled    = true;
  btn.textContent = '⏳ Cancelling…';

  const fullReason = _selectedReason + (note ? ' — ' + note : '');

  fetch('php/cancel_order.php', {
    method : 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body   : 'order_id=' + _cancelOrderId +
             '&reason='  + encodeURIComponent(fullReason) +
             '&is_paid='  + (_cancelIsPaid ? '1' : '0') +
             '&amount='   + _cancelAmt
  })
  .then(r => r.json())
  .then(data => {
    closeCancelModal();
    if (data.success) {
      showAdminToast('🚫 Order #' + _cancelOrderId + ' cancelled. Customer notified.' +
        (_cancelIsPaid ? ' Refund required ₹' + _cancelAmt.toFixed(2) : ''), 6000);
      // Update row in table
      const row = document.getElementById('adminRow-' + _cancelOrderId);
      if (row) row.style.opacity = '.5';
      setTimeout(() => { if (typeof loadData === 'function') loadData(); }, 1500);
    } else {
      showAdminToast('❌ Failed: ' + (data.error || 'Try again.'), 4000);
    }
  })
  .catch(() => {
    closeCancelModal();
    showAdminToast('❌ Network error. Try again.');
  });
}

// ═══════════════════════════════════════════════════════════════
//  INJECT payment + cancel cells after admin.js renders rows
// ═══════════════════════════════════════════════════════════════
function injectPaymentCells() {
  document.querySelectorAll('#ordersBody tr').forEach(row => {
    const cells = row.querySelectorAll('td');
    if (cells.length < 9 || row.dataset.payInjected === '1') return;
    row.dataset.payInjected = '1';

    const orderId = parseInt(cells[0].textContent.trim().replace('#',''), 10);
    if (!orderId || isNaN(orderId)) return;

    row.id = 'adminRow-' + orderId;

    // Track known IDs (for new order detection)
    _knownOrderIds.add(orderId);

    // Payment cell
    const td = document.createElement('td');
    td.id = 'payCell-' + orderId;
    td.innerHTML =
      '<span class="badge-unpaid">⏳ Pending</span><br>' +
      '<button class="btn-mark-paid" onclick="markPaid(' + orderId + ',this)">✅ Mark Paid</button>';
    row.classList.add('row-unpaid');
    row.insertBefore(td, cells[7]);

    // Cancel button — inject into Actions cell (now index 9 after we added payment)
    const actionCell = row.querySelector('td:last-child');
    if (actionCell && !actionCell.querySelector('.btn-cancel-order')) {
      const cancelBtn = document.createElement('button');
      cancelBtn.className   = 'btn-cancel-order';
      cancelBtn.textContent = '🚫 Cancel';
      cancelBtn.onclick = function() {
        const isPaid = td.querySelector('.badge-paid') !== null;
        const amtTxt = cells[5] ? cells[5].textContent.replace(/[₹,]/g,'').trim() : '0';
        openCancelModal(orderId, isPaid, parseFloat(amtTxt));
      };
      actionCell.appendChild(cancelBtn);
    }

    // Check actual payment status
    fetch('php/get_payment_status.php?order_id=' + orderId)
    .then(r => r.json())
    .then(d => {
      if (d.payment === 'paid') {
        td.innerHTML = '<span class="badge-paid">✅ Paid</span>';
        row.classList.remove('row-unpaid'); row.classList.add('row-paid');
      }
    }).catch(() => {});
  });
}

// Highlight truly new rows (added after page load)
function highlightNewRows() {
  document.querySelectorAll('#ordersBody tr[data-new="1"]').forEach(row => {
    row.removeAttribute('data-new');
    row.classList.add('new-order-row');
    // Add blinking green dot to first cell
    const firstCell = row.querySelector('td');
    if (firstCell && !firstCell.querySelector('.new-dot')) {
      const dot = document.createElement('span');
      dot.className = 'new-dot';
      dot.title = 'New order';
      firstCell.prepend(dot);
      setTimeout(() => dot.remove(), 15000);
    }
  });
}

// Watch ordersBody for changes
(function() {
  const tbody = document.getElementById('ordersBody');
  if (!tbody) return;
  const observer = new MutationObserver(() => {
    injectPaymentCells();
    highlightNewRows();
  });
  observer.observe(tbody, { childList: true, subtree: true });
  window.addEventListener('load', () => {
    setTimeout(injectPaymentCells, 500);
    setTimeout(() => {
      _initialLoadDone = true;
      startOrderPolling();
    }, 2000);
  });
})();

// ── Logout ──
function showLogoutConfirm()  { document.getElementById('logoutModal').classList.add('open'); }
function closeLogoutConfirm() { document.getElementById('logoutModal').classList.remove('open'); }
function confirmLogout() {
  closeLogoutConfirm();
  setTimeout(() => {
    if (typeof logout === 'function') logout();
    else window.location.href = 'php/auth.php?action=logout';
  }, 250);
}
document.getElementById('logoutModal').addEventListener('click', e => { if (e.target === document.getElementById('logoutModal')) closeLogoutConfirm(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeLogoutConfirm(); closeCancelModal(); } });

// ── Mobile sidebar ──
function toggleSidebar() {
  document.querySelector('.sidebar').classList.toggle('open');
  document.getElementById('sidebarOverlay').classList.toggle('open');
}
function closeSidebar() {
  document.querySelector('.sidebar').classList.remove('open');
  document.getElementById('sidebarOverlay').classList.remove('open');
}
document.querySelectorAll('.sidebar-nav .nav-item').forEach(el => {
  el.addEventListener('click', () => { if (window.innerWidth <= 900) closeSidebar(); });
});
</script>

</body>
</html>