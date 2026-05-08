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

/* ── Mobile sidebar toggle button ── */
.sidebar-toggle {
  display: none;
  position: fixed;
  top: 1rem; left: 1rem;
  z-index: 1100;
  background: #1A0F0A;
  color: #fff;
  border: none;
  border-radius: 10px;
  width: 42px; height: 42px;
  font-size: 1.2rem;
  cursor: pointer;
  align-items: center; justify-content: center;
  box-shadow: 0 4px 16px rgba(0,0,0,0.3);
  transition: background 0.2s;
}
.sidebar-toggle:hover { background: #3D1A0A; }
.sidebar-overlay {
  display: none;
  position: fixed; inset: 0;
  background: rgba(0,0,0,0.55);
  z-index: 999;
}
.sidebar-overlay.open { display: block; }

/* ── Responsive ── */
@media (max-width: 900px) {
  .admin-layout { grid-template-columns: 1fr; }
  .sidebar {
    position: fixed;
    top: 0; left: 0;
    height: 100vh;
    transform: translateX(-100%);
    transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
    z-index: 1000;
    box-shadow: 6px 0 30px rgba(0,0,0,0.3);
  }
  .sidebar.open { transform: translateX(0); }
  .admin-main { padding: 1rem 0.8rem; padding-top: 4rem; }
  .admin-header { margin-bottom: 1rem; gap: 0.5rem; }
  .admin-header h1 { font-size: 1.3rem; }
  .sidebar-toggle { display: flex; }
  .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 0.7rem; }
  .stat-card { padding: 0.9rem 1rem; }
  .stat-card .stat-icon { font-size: 1.4rem; }
  .stat-info span { font-size: 1.4rem; }
}

@media (max-width: 600px) {
  .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 0.6rem; }
  .stat-card { padding: 0.75rem; }
  .table-wrapper { overflow-x: auto; -webkit-overflow-scrolling: touch; border-radius: 12px; }
  .admin-table { min-width: 720px; font-size: 0.8rem; }
  .admin-table th, .admin-table td { padding: 0.6rem 0.7rem; }
  .badge-paid, .badge-unpaid { font-size: 0.7rem; padding: 2px 8px; }
  .btn-mark-paid { font-size: 0.7rem; padding: 4px 10px; }
  .refresh-btn { padding: 0.5rem 0.9rem; font-size: 0.8rem; }
  .admin-header { padding: 0.75rem 1rem; }
  .logout-modal { padding: 2rem 1.4rem; }
  .logout-modal h3 { font-size: 1.2rem; }
  .lm-btn { font-size: 0.85rem; padding: 0.65rem 0.8rem; }
  .admin-toast { bottom: 1rem; right: 1rem; left: 1rem; font-size: 0.85rem; text-align: center; }
}

/* ── Logout Confirmation Modal ── */
.logout-modal-overlay {
  position: fixed; inset: 0;
  background: rgba(0,0,0,0.55);
  display: flex; align-items: center; justify-content: center;
  z-index: 999999;
  opacity: 0; pointer-events: none;
  transition: opacity 0.25s;
}
.logout-modal-overlay.open {
  opacity: 1; pointer-events: all;
}
.logout-modal {
  background: #fff;
  border-radius: 20px;
  padding: 2.5rem 2rem;
  max-width: 380px; width: 90%;
  text-align: center;
  box-shadow: 0 30px 80px rgba(0,0,0,0.35);
  transform: scale(0.85) translateY(10px);
  transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1);
  font-family: 'DM Sans', sans-serif;
}
.logout-modal-overlay.open .logout-modal {
  transform: scale(1) translateY(0);
}
.logout-modal .lm-icon { font-size: 3rem; display: block; margin-bottom: 0.5rem; }
.logout-modal h3 {
  font-size: 1.4rem; font-weight: 700;
  color: #1A0F0A; margin-bottom: 0.4rem;
}
.logout-modal p {
  color: #8B6355; font-size: 0.9rem;
  margin-bottom: 1.8rem; line-height: 1.5;
}
.logout-modal-actions {
  display: flex; gap: 0.8rem; justify-content: center;
}
.lm-btn {
  flex: 1; max-width: 140px;
  padding: 0.75rem 1rem;
  border-radius: 50px;
  font-size: 0.9rem; font-weight: 700;
  cursor: pointer;
  transition: background 0.2s, transform 0.1s;
  border: none;
  font-family: 'DM Sans', sans-serif;
}
.lm-btn:hover { transform: translateY(-1px); }
.lm-btn-cancel {
  background: #F0EBE7; color: #2C1810;
}
.lm-btn-cancel:hover { background: #E5DDD7; }
.lm-btn-confirm {
  background: #C8460A; color: white;
}
.lm-btn-confirm:hover { background: #E85D1E; }
</style>
</head>
<body>

<!-- Mobile sidebar toggle -->
<button class="sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()">☰</button>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<div class="admin-layout">
  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-brand">🍽 Admin Panel</div>
    <nav class="sidebar-nav">
      <a href="#" class="nav-item active" onclick="showTab('orders')">📋 Orders</a>
      <a href="#" class="nav-item" onclick="showTab('menu')">🍛 Menu Items</a>
      <a href="index.php" class="nav-item">🌐 View Site</a>
      <!-- FIX: calls showLogoutConfirm() instead of logout() directly -->
      <a href="#" class="nav-item logout-item" onclick="showLogoutConfirm()">🚪 Logout</a>
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

<!-- Your existing admin.js (unchanged) -->
<script src="js/admin.js"></script>

<script>
// ═══════════════════════════════════════════════════════════════
//  MARK PAID — Smart injection (NO changes to admin.js needed)
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

/* ── Mark order as Paid ──
   FIX: Changed URL from 'php/mark_paid.php' → 'php/payment.php'
   (payment.php lives in the php/ folder per its own comment header)
*/
function markPaid(orderId, btn) {
  btn.disabled    = true;
  btn.textContent = '⏳ Confirming…';

  fetch('php/payment.php', {                          // ← FIXED path
    method : 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body   : 'order_id=' + orderId
  })
  .then(function(r){ return r.json(); })
  .then(function(data) {
    if (data.success) {
      var cell = document.getElementById('payCell-' + orderId);
      if (cell) {
        cell.innerHTML =
          '<span class="badge-paid">✅ Paid</span>' +
          '<small class="paid-at">Just now</small>';
      }
      var row = document.getElementById('adminRow-' + orderId);
      if (row) { row.classList.remove('row-unpaid'); row.classList.add('row-paid'); }

      btn.remove();
      showAdminToast('💚 Order #' + orderId + ' marked PAID — customer notified!', 4000);
    } else {
      btn.disabled    = false;
      btn.textContent = '✅ Mark Paid';
      showAdminToast('❌ Failed: ' + (data.error || 'Try again.'));
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
════════════════════════════════════════════════════════ */
function injectPaymentCells() {
  var rows = document.querySelectorAll('#ordersBody tr');

  rows.forEach(function(row) {
    var cells = row.querySelectorAll('td');
    if (cells.length < 9) return;
    if (row.dataset.payInjected === '1') return;
    row.dataset.payInjected = '1';

    var idText  = cells[0].textContent.trim().replace('#', '');
    var orderId = parseInt(idText, 10);
    if (!orderId || isNaN(orderId)) return;

    row.id = 'adminRow-' + orderId;

    var td  = document.createElement('td');
    td.id   = 'payCell-' + orderId;
    td.innerHTML =
      '<span class="badge-unpaid">⏳ Pending</span><br>' +
      '<button class="btn-mark-paid" onclick="markPaid(' + orderId + ', this)">✅ Mark Paid</button>';
    row.classList.add('row-unpaid');

    var refCell = cells[7];
    row.insertBefore(td, refCell);

    // FIX: Also using correct path for get_payment_status
    fetch('php/get_payment_status.php?order_id=' + orderId)
    .then(function(r){ return r.json(); })
    .then(function(d) {
      if (d.payment === 'paid') {
        td.innerHTML = '<span class="badge-paid">✅ Paid</span>';
        row.classList.remove('row-unpaid');
        row.classList.add('row-paid');
      }
    })
    .catch(function(){});
  });
}

/* ── Watch #ordersBody for new rows ── */
(function() {
  var tbody = document.getElementById('ordersBody');
  if (!tbody) return;

  var observer = new MutationObserver(function() {
    injectPaymentCells();
  });
  observer.observe(tbody, { childList: true, subtree: true });

  window.addEventListener('load', function() {
    setTimeout(injectPaymentCells, 500);
    setTimeout(injectPaymentCells, 1500);
  });
})();

/* ════════════════════════════════════════════════════════
   LOGOUT CONFIRMATION
════════════════════════════════════════════════════════ */
function showLogoutConfirm() {
  document.getElementById('logoutModal').classList.add('open');
}

function closeLogoutConfirm() {
  document.getElementById('logoutModal').classList.remove('open');
}

function confirmLogout() {
  // Close modal first, then redirect to logout endpoint
  closeLogoutConfirm();
  // Small delay so the modal close animation plays
  setTimeout(function() {
    if (typeof logout === 'function') {
      logout(); // call admin.js logout if defined
    } else {
      window.location.href = 'php/auth.php?action=logout';
    }
  }, 250);
}

// Close modal on overlay click (outside the box)
document.getElementById('logoutModal').addEventListener('click', function(e) {
  if (e.target === this) closeLogoutConfirm();
});
// Close on Escape key
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeLogoutConfirm();
});

/* ════════════════════════════════════════════════════════
   MOBILE SIDEBAR TOGGLE
════════════════════════════════════════════════════════ */
function toggleSidebar() {
  const sidebar = document.querySelector('.sidebar');
  const overlay = document.getElementById('sidebarOverlay');
  sidebar.classList.toggle('open');
  overlay.classList.toggle('open');
}
function closeSidebar() {
  document.querySelector('.sidebar').classList.remove('open');
  document.getElementById('sidebarOverlay').classList.remove('open');
}
// Close sidebar when a nav item is clicked on mobile
document.querySelectorAll('.sidebar-nav .nav-item').forEach(function(el) {
  el.addEventListener('click', function() {
    if (window.innerWidth <= 900) closeSidebar();
  });
});
</script>

</body>
</html>