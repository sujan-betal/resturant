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
.badge-paid {
  display: inline-block;
  background: #D4EDDA; color: #155724;
  border: 1px solid #C3E6CB;
  padding: 3px 10px; border-radius: 20px;
  font-size: 0.75rem; font-weight: 700;
  white-space: nowrap;
}
.badge-unpaid {
  display: inline-block;
  background: #FFF3CD; color: #856404;
  border: 1px solid #FFE69C;
  padding: 3px 10px; border-radius: 20px;
  font-size: 0.75rem; font-weight: 700;
  white-space: nowrap;
}
.btn-mark-paid {
  background: #2C7A2C; color: white;
  border: none; border-radius: 20px;
  padding: 5px 12px; font-size: 0.75rem;
  font-weight: 700; cursor: pointer;
  transition: background 0.2s, transform 0.1s;
  white-space: nowrap;
  margin-top: 5px;
  display: block;
}
.btn-mark-paid:hover  { background: #3A9A3A; transform: translateY(-1px); }
.btn-mark-paid:disabled { background: #aaa; cursor: not-allowed; transform: none; }

/* paid_at small text under badge */
.paid-at { font-size: 0.68rem; color: #888; display: block; margin-top: 2px; }

/* row highlights */
tr.row-paid   { background: #F0FFF0 !important; }
tr.row-unpaid { background: #FFFEF6 !important; }

/* ── Admin toast ── */
.admin-toast {
  position: fixed; bottom: 2rem; right: 2rem;
  background: #1A0F0A; color: #fff;
  padding: 0.85rem 1.5rem;
  border-radius: 12px;
  font-size: 0.9rem;
  box-shadow: 0 8px 30px rgba(0,0,0,0.3);
  z-index: 99999;
  opacity: 0; transform: translateY(20px);
  transition: all 0.3s;
  pointer-events: none;
  font-family: 'DM Sans', sans-serif;
}
.admin-toast.show { opacity: 1; transform: translateY(0); }
</style>
</head>
<body>

<div class="admin-layout">
  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-brand">🍽 Admin Panel</div>
    <nav class="sidebar-nav">
      <a href="#" class="nav-item active" onclick="showTab('orders')">📋 Orders</a>
      <a href="#" class="nav-item" onclick="showTab('menu')">🍛 Menu Items</a>
      <a href="index.php" class="nav-item">🌐 View Site</a>
      <a href="#" class="nav-item logout-item" onclick="logout()">🚪 Logout</a>
    </nav>
  </aside>

  <!-- MAIN CONTENT -->
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
            <tr><td colspan="10" class="loading-row">Loading orders...</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- MENU TAB -->
    <div id="menuTab" class="tab-content">
      <div class="menu-admin-grid" id="menuAdminGrid">
        <div class="loading-row">Loading menu items...</div>
      </div>
    </div>
  </main>
</div>

<!-- Admin toast -->
<div class="admin-toast" id="adminToast"></div>

<!-- Your existing admin.js (unchanged) -->
<script src="js/admin.js"></script>

<script>
// ═══════════════════════════════════════════════════════════════
//  MARK PAID — Smart injection (NO changes to admin.js needed)
//
//  How it works:
//  • MutationObserver watches #ordersBody for new rows
//  • Each new <tr> gets scanned: order ID read from col 0 (#8 → 8)
//  • Payment status fetched from php/get_payment_status.php
//  • Payment <td> injected at col 7 (before Time col)
//  • Admin clicks "✅ Mark Paid" → php/mark_paid.php → DB updated
//  • Customer's 3s poll detects 'paid' → QR modal closes → popups
// ═══════════════════════════════════════════════════════════════

/* ── Toast helper ── */
function showAdminToast(msg, ms) {
  ms = ms || 3500;
  var t = document.getElementById('adminToast');
  t.textContent = msg;
  t.classList.add('show');
  clearTimeout(t._tmr);
  t._tmr = setTimeout(function(){ t.classList.remove('show'); }, ms);
}

/* ── Mark order as Paid ── */
function markPaid(orderId, btn) {
  btn.disabled    = true;
  btn.textContent = '⏳ Confirming…';

  fetch('php/mark_paid.php', {
    method : 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body   : 'order_id=' + orderId
  })
  .then(function(r){ return r.json(); })
  .then(function(data) {
    if (data.success) {
      // Swap badge to Paid
      var cell = document.getElementById('payCell-' + orderId);
      if (cell) {
        cell.innerHTML =
          '<span class="badge-paid">✅ Paid</span>' +
          '<small class="paid-at">Just now</small>';
      }
      // Green row
      var row = document.getElementById('adminRow-' + orderId);
      if (row) { row.classList.remove('row-unpaid'); row.classList.add('row-paid'); }

      btn.remove();
      showAdminToast('💚 Order #' + orderId + ' marked PAID — customer notified!', 4000);
    } else {
      btn.disabled    = false;
      btn.textContent = '✅ Mark Paid';
      showAdminToast('❌ Failed. Try again.');
    }
  })
  .catch(function(err) {
    btn.disabled    = false;
    btn.textContent = '✅ Mark Paid';
    showAdminToast('❌ Network error. Try again.');
    console.error(err);
  });
}

/* ════════════════════════════════════════════════════════
   CORE: injectPaymentCells()
   ────────────────────────────────────────────────────────
   Scans every <tr> in #ordersBody.
   Reads Order ID from the FIRST cell text (e.g. "#8" → 8).
   Skips rows that already have a payment cell injected.
   Inserts payment <td> at position 7 (before Time column).
   Fetches current payment status from DB for each new row.
════════════════════════════════════════════════════════ */
function injectPaymentCells() {
  var rows = document.querySelectorAll('#ordersBody tr');

  rows.forEach(function(row) {
    // Skip colspan rows (loading/empty states)
    var cells = row.querySelectorAll('td');
    if (cells.length < 9) return;

    // Skip if we already processed this row
    if (row.dataset.payInjected === '1') return;
    row.dataset.payInjected = '1';

    // Read Order ID from first cell: "#8" → 8
    var idText  = cells[0].textContent.trim().replace('#', '');
    var orderId = parseInt(idText, 10);
    if (!orderId || isNaN(orderId)) return;

    // Tag row for later targeting
    row.id = 'adminRow-' + orderId;

    // Create placeholder payment cell (pending by default)
    var td  = document.createElement('td');
    td.id   = 'payCell-' + orderId;
    td.innerHTML =
      '<span class="badge-unpaid">⏳ Pending</span><br>' +
      '<button class="btn-mark-paid" onclick="markPaid(' + orderId + ', this)">✅ Mark Paid</button>';
    row.classList.add('row-unpaid');

    // Insert at index 7 (before Time column, after Status)
    var refCell = cells[7]; // 0-based: 7 = 8th cell = Time col
    row.insertBefore(td, refCell);

    // Check real payment status from DB asynchronously
    fetch('php/get_payment_status.php?order_id=' + orderId)
    .then(function(r){ return r.json(); })
    .then(function(d) {
      if (d.payment === 'paid') {
        td.innerHTML =
          '<span class="badge-paid">✅ Paid</span>';
        row.classList.remove('row-unpaid');
        row.classList.add('row-paid');
      }
    })
    .catch(function(){});
  });
}

/* ── Watch #ordersBody for new rows from admin.js ── */
(function() {
  var tbody = document.getElementById('ordersBody');
  if (!tbody) return;

  var observer = new MutationObserver(function() {
    injectPaymentCells();
  });
  observer.observe(tbody, { childList: true, subtree: true });

  // Also run after load (in case rows already rendered)
  window.addEventListener('load', function() {
    setTimeout(injectPaymentCells, 500);
    setTimeout(injectPaymentCells, 1500); // retry for slow renders
  });
})();
</script>

</body>
</html>