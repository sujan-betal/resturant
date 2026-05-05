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
  padding: 4px 12px; font-size: 0.75rem;
  font-weight: 700; cursor: pointer;
  transition: background 0.2s, transform 0.1s;
  white-space: nowrap;
}
.btn-mark-paid:hover { background: #3A9A3A; transform: translateY(-1px); }
.btn-mark-paid:disabled { background: #aaa; cursor: not-allowed; transform: none; }

/* paid_at small text under badge */
.paid-at { font-size: 0.68rem; color: #888; display: block; margin-top: 2px; }

/* highlight rows */
tr.row-paid   { background: #F6FFF6 !important; }
tr.row-unpaid { background: #FFFEF6 !important; }
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
      <!-- NEW: paid revenue stat -->
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

<script src="js/admin.js"></script>

</body>
</html>