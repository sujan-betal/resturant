<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Spice & Soul Restaurant</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
<style>
/* ── Payment QR Modal ── */
.payment-modal-overlay {
  position: fixed; inset: 0;
  background: rgba(0,0,0,0.6);
  display: flex; align-items: center; justify-content: center;
  z-index: 9999;
  opacity: 0; pointer-events: none;
  transition: opacity 0.3s;
}
.payment-modal-overlay.open {
  opacity: 1; pointer-events: all;
}
.payment-modal {
  background: #fff;
  border-radius: 20px;
  padding: 2.5rem 2rem;
  max-width: 420px; width: 90%;
  text-align: center;
  box-shadow: 0 30px 80px rgba(0,0,0,0.35);
  transform: translateY(20px);
  transition: transform 0.3s;
}
.payment-modal-overlay.open .payment-modal {
  transform: translateY(0);
}
.payment-modal .pm-icon { font-size: 2.5rem; margin-bottom: 0.4rem; }
.payment-modal h2 {
  font-family: 'Playfair Display', serif;
  font-size: 1.6rem; color: #1A0F0A; margin-bottom: 0.3rem;
}
.payment-modal .pm-sub { color: #8B6355; font-size: 0.9rem; margin-bottom: 1.5rem; }
.payment-modal .pm-amount {
  font-size: 2rem; font-weight: 700; color: #C8460A;
  margin-bottom: 1rem;
}
#qrContainer {
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 1.2rem;
  width: 200px; height: 200px;
  background: #FFFAF6;
  border: 2px dashed #EDD8C8;
  border-radius: 12px;
  overflow: hidden;
}
#qrContainer canvas, #qrContainer img { border-radius: 8px; }

.pm-note {
  font-size: 0.8rem; color: #999;
  margin-bottom: 1.2rem;
  line-height: 1.5;
}
.pm-upi-id {
  background: #FFF3ED;
  border: 1px solid #EDD8C8;
  border-radius: 8px;
  padding: 0.5rem 1rem;
  font-size: 0.85rem;
  color: #2C1810;
  margin-bottom: 1.5rem;
  word-break: break-all;
}
.pm-upi-id strong { display: block; font-size: 0.75rem; color: #8B6355; margin-bottom: 2px; }

.pm-btn-done {
  width: 100%;
  padding: 0.85rem;
  background: #2C7A2C; color: white;
  border: none; border-radius: 50px;
  font-weight: 700; font-size: 1rem;
  cursor: pointer; transition: background 0.2s;
}
.pm-btn-done:hover { background: #3A9A3A; }
.pm-order-ref { font-size: 0.78rem; color: #bbb; margin-top: 1rem; }
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
  <div class="nav-brand">🍽 Spice &amp; Soul</div>
  <div class="nav-links">
    <a href="#menu">Menu</a>
    <a href="#about">About</a>
    <button class="cart-btn" onclick="toggleCart()">
      🛒 Cart <span class="cart-count" id="cartCount">0</span>
    </button>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-content">
    <p class="hero-tag">EST. 2024 · KOLKATA</p>
    <h1>Where Every Bite<br>Tells a <em>Story</em></h1>
    <p class="hero-sub">Authentic flavours crafted with passion, served with love</p>
    <a href="#menu" class="btn-primary">Explore Menu</a>
  </div>
  <div class="hero-image">
    <div class="hero-img-circle">
      <img src="https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=600" alt="Food">
    </div>
    <div class="floating-badge badge1">⭐ 4.9 Rating</div>
    <div class="floating-badge badge2">🕐 30 min delivery</div>
  </div>
</section>

<!-- CATEGORY FILTER -->
<section id="menu" class="menu-section">
  <div class="section-header">
    <p class="section-tag">OUR MENU</p>
    <h2>What Would You Like?</h2>
  </div>
  <div class="category-tabs" id="categoryTabs">
    <button class="tab active" data-id="all" onclick="filterMenu('all', this)">All Items</button>
  </div>
  <div class="menu-grid" id="menuGrid">
    <div class="loading">Loading delicious items... 🍽</div>
  </div>
</section>

<!-- ABOUT -->
<section id="about" class="about-section">
  <div class="about-content">
    <p class="section-tag">OUR STORY</p>
    <h2>Passion on Every Plate</h2>
    <p>Since 2024, Spice & Soul has been serving authentic recipes passed down through generations. Our chefs blend traditional techniques with modern presentation to create unforgettable dining experiences.</p>
    <div class="about-stats">
      <div class="stat"><span>500+</span><p>Happy Customers</p></div>
      <div class="stat"><span>50+</span><p>Menu Items</p></div>
      <div class="stat"><span>10+</span><p>Expert Chefs</p></div>
    </div>
  </div>
  <div class="about-img">
    <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600" alt="Restaurant">
  </div>
</section>

<!-- CART SIDEBAR -->
<div class="cart-overlay" id="cartOverlay" onclick="toggleCart()"></div>
<div class="cart-sidebar" id="cartSidebar">
  <div class="cart-header">
    <h3>Your Order 🛒</h3>
    <button onclick="toggleCart()">✕</button>
  </div>
  <div class="cart-items" id="cartItems">
    <div class="empty-cart">Your cart is empty<br>🍽 Add something tasty!</div>
  </div>
  <div class="cart-footer" id="cartFooter" style="display:none">
    <div class="cart-total">Total: <strong id="cartTotal">₹0</strong></div>
    <button class="btn-order" onclick="showOrderForm()">Place Order</button>
  </div>
</div>

<!-- ORDER DETAILS MODAL -->
<div class="modal-overlay" id="orderModal">
  <div class="modal">
    <div class="modal-header">
      <h3>Complete Your Order</h3>
      <button onclick="closeModal()">✕</button>
    </div>
    <form id="orderForm">
      <div class="form-group">
        <label>Your Name *</label>
        <input type="text" id="custName" placeholder="Enter your name" required>
      </div>
      <div class="form-group">
        <label>Phone Number</label>
        <input type="tel" id="custPhone" placeholder="Enter phone number">
      </div>
      <div class="form-group">
        <label>Table Number</label>
        <input type="number" id="tableNo" placeholder="e.g. 5" min="1" max="50">
      </div>
      <div class="order-summary" id="orderSummary"></div>
      <button type="submit" class="btn-primary" style="width:100%">Confirm Order 🍽</button>
    </form>
  </div>
</div>

<!-- PAYMENT QR MODAL -->
<div class="payment-modal-overlay" id="paymentModal">
  <div class="payment-modal">
    <div class="pm-icon">📱</div>
    <h2>Pay to Confirm</h2>
    <p class="pm-sub">Scan the QR code below to complete payment</p>

    <div class="pm-amount" id="pmAmount">₹0</div>

    <!-- QR code renders here -->
    <div id="qrContainer"></div>

    <div class="pm-upi-id">
      <strong>UPI ID</strong>
      <span id="pmUpiId">spiceandsoul@upi</span>
    </div>

    <p class="pm-note">
      Open any UPI app (GPay, PhonePe, Paytm, etc.),<br>
      scan this QR &amp; pay <strong id="pmAmountSmall">₹0</strong>.
      Show the screenshot to your server.<br>
      Admin will mark your order as <strong>Paid ✅</strong>.
    </p>

    <button class="pm-btn-done" onclick="closePaymentModal()" style="background: #dc3545;">
      ✖ Close
    </button>

    <p class="pm-order-ref" id="pmOrderRef">Order #—</p>
  </div>
</div>

<!-- SUCCESS TOAST -->
<div class="toast" id="toast"></div>

<footer class="footer">
  <p>🍽 Spice &amp; Soul Restaurant · Kolkata · Made with ❤️</p>
</footer>

<!-- QR code library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<!-- Link to your app.js which handles cart and form submission -->
<script src="js/app.js"></script>

<script>
// ═══════════════════════════════════════════════════
//  AUTOMATIC PAYMENT VERIFICATION LOGIC (Polling)
// ═══════════════════════════════════════════════════

let paymentCheckTimer; // Timer ko store karne ke liye

function showPaymentQR(orderId, totalAmount) {
  document.getElementById('pmAmount').textContent      = '₹' + parseFloat(totalAmount).toFixed(2);
  document.getElementById('pmAmountSmall').textContent = '₹' + parseFloat(totalAmount).toFixed(2);
  document.getElementById('pmOrderRef').textContent    = 'Order #' + orderId;
  document.getElementById('pmUpiId').textContent       = 'sujanbetal18-1@okaxis'; 

  const qrBox = document.getElementById('qrContainer');
  qrBox.innerHTML = `<img src="qr.jpeg" alt="Scan to Pay" style="width: 100%; height: 100%; object-fit: contain; border-radius: 8px;">`;

  document.getElementById('paymentModal').classList.add('open');

  // 🔴 MAGIC START: Har 3 second mein check karega ki Admin ne "Mark Paid" kiya ya nahi
  paymentCheckTimer = setInterval(async () => {
    try {
      const response = await fetch(`php/check_status.php?order_id=${orderId}`);
      const data = await response.json();

      if (data.status === 'paid') {
        // Agar Admin ne verify kar diya hai!
        clearInterval(paymentCheckTimer); // Timer band karo
        showPaymentSuccess(); // Success popup dikhao
      }
    } catch (err) {
      console.log("Checking status...", err);
    }
  }, 3000); // 3000 ms = 3 Seconds
}

// Jab background mein payment verify ho jaye
function showPaymentSuccess() {
  // Modal band karo
  document.getElementById('paymentModal').classList.remove('open');
  
  // Success message dikhao
  if (typeof showToast === 'function') {
    showToast('🎉 Payment Done Successfully! Your food is being prepared.');
  } else {
    alert('🎉 Payment Done Successfully! Your food is being prepared.');
  }
}

// Agar customer ✕ dabakar khud modal band karta hai
function closePaymentModal() {
  document.getElementById('paymentModal').classList.remove('open');
  
  // Timer rok do warna background mein check karta rahega
  if (paymentCheckTimer) {
    clearInterval(paymentCheckTimer);
  }
}
</script>

</body>
</html>