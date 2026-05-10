let currentTab = 'orders';

document.addEventListener('DOMContentLoaded', () => {
  loadStats();
  loadOrders();
});

function showTab(tab) {
  currentTab = tab;
  document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
  document.getElementById(tab + 'Tab').classList.add('active');
  document.getElementById('pageTitle').textContent = tab === 'orders' ? 'Orders' : 'Menu Items';
  event.target.classList.add('active');
  loadData();
}

function loadData() {
  loadStats();
  if (currentTab === 'orders') loadOrders();
  else loadMenuAdmin();
}

// ===== STATS =====
async function loadStats() {
  try {
    const res = await fetch('php/admin_api.php?action=stats');
    const d = await res.json();
    document.getElementById('totalOrders').textContent = d.total_orders || 0;
    document.getElementById('totalRevenue').textContent = '₹' + (parseFloat(d.total_revenue) || 0).toFixed(0);
    document.getElementById('pendingOrders').textContent = d.pending || 0;
    document.getElementById('todayOrders').textContent = d.today || 0;
  } catch (e) { console.error(e); }
}

// ===== ORDERS =====
async function loadOrders() {
  try {

    const res = await fetch('php/order.php');
    const orders = await res.json();

    const tbody = document.getElementById('ordersBody');

    if (!orders.length) {
      tbody.innerHTML = '<tr><td colspan="9" class="loading-row">No orders yet 🍽</td></tr>';
      return;
    }

    tbody.innerHTML = orders.map(o => `
      <tr>
        <td><strong>#${o.id}</strong></td>
        <td>${o.customer_name}</td>
        <td>${o.customer_phone || '-'}</td>
        <td>${o.table_number || '-'}</td>
        <td style="max-width:200px;font-size:0.8rem">
  ${o.items_list || '-'}
</td>
        <td><strong>₹${parseFloat(o.total_amount).toFixed(2)}</strong></td>
        <td>
          <span class="status-badge status-${o.status}">
            ${o.status}
          </span>
        </td>
        <td style="font-size:0.8rem">
          ${formatDate(o.created_at)}
        </td>
        <td>
          <select class="status-select"
            onchange="updateStatus(${o.id}, this.value)">
            
            <option value="pending"
              ${o.status==='pending'?'selected':''}>
              Pending
            </option>

            <option value="preparing"
              ${o.status==='preparing'?'selected':''}>
              Preparing
            </option>

            <option value="served"
              ${o.status==='served'?'selected':''}>
              Served
            </option>

            <option value="cancelled"
              ${o.status==='cancelled'?'selected':''}>
              Cancelled
            </option>

          </select>
        </td>
      </tr>
    `).join('');

  } catch (e) {

    console.error(e);

    document.getElementById('ordersBody').innerHTML =
      '<tr><td colspan="9" class="loading-row">⚠️ Error loading orders</td></tr>';
  }
}

async function updateStatus(orderId, status) {
  const form = new FormData();
  form.append('action', 'update_status');
  form.append('order_id', orderId);
  form.append('status', status);
  await fetch('php/admin_api.php', { method: 'POST', body: form });
  loadStats();
}

// ===== MENU ADMIN =====
async function loadMenuAdmin() {
  try {
    const res = await fetch('php/menu.php?action=menu');
    const items = await res.json();
    const grid = document.getElementById('menuAdminGrid');
    grid.innerHTML = items.map(item => `
      <div class="menu-admin-card">
        <img src="${item.image_url}" alt="${item.name}" onerror="this.src='https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400'">
        <div class="mac-body">
          <div class="mac-name">${item.name}</div>
          <div class="mac-price">₹${parseFloat(item.price).toFixed(2)}</div>
          <button class="toggle-btn ${item.is_available == 1 ? 'toggle-available' : 'toggle-unavailable'}"
            onclick="toggleItem(${item.id}, this)">
            ${item.is_available == 1 ? '✅ Available' : '❌ Unavailable'}
          </button>
        </div>
      </div>
    `).join('');
  } catch (e) {
    document.getElementById('menuAdminGrid').innerHTML = '<div class="loading-row">⚠️ Error loading menu</div>';
  }
}

async function toggleItem(itemId, btn) {
  const form = new FormData();
  form.append('action', 'toggle_item');
  form.append('item_id', itemId);
  await fetch('php/admin_api.php', { method: 'POST', body: form });
  const isAvailable = btn.classList.contains('toggle-available');
  btn.classList.toggle('toggle-available', !isAvailable);
  btn.classList.toggle('toggle-unavailable', isAvailable);
  btn.textContent = isAvailable ? '❌ Unavailable' : '✅ Available';
}

function formatDate(dateStr) {
  const d = new Date(dateStr);
  return d.toLocaleDateString('en-IN') + ' ' + d.toLocaleTimeString('en-IN', { hour: '2-digit', minute: '2-digit' });
}

async function logout() {
  await fetch('php/auth.php?action=logout');
  window.location.href = 'admin_login.php';
}


async function cancelOrder(orderId) {

    const reason = prompt("Enter cancellation reason:");

    if (!reason) {
        alert("Cancellation reason required");
        return;
    }

    const res = await fetch('php/cancel_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            order_id: orderId,
            reason: reason
        })
    });

    const data = await res.json();

    if (data.success) {
        alert("Order cancelled");
        location.reload();
    } else {
        alert(data.message);
    }
}
