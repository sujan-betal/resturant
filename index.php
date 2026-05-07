<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Spice & Soul Restaurant</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
<style>

/* ══════════════════════════════════════════════
   PAYMENT MODAL — Full redesign with tabs
   ══════════════════════════════════════════════ */

.payment-modal-overlay {
  position: fixed; inset: 0;
  background: rgba(0,0,0,0.65);
  display: flex; align-items: center; justify-content: center;
  z-index: 9999;
  opacity: 0; pointer-events: none;
  transition: opacity 0.3s;
  padding: 1rem;
}
.payment-modal-overlay.open {
  opacity: 1; pointer-events: all;
}
.payment-modal {
  background: #fff;
  border-radius: 24px;
  padding: 0;
  max-width: 440px; width: 100%;
  box-shadow: 0 30px 80px rgba(0,0,0,0.4);
  transform: translateY(24px) scale(0.97);
  transition: transform 0.35s cubic-bezier(0.34,1.4,0.64,1);
  overflow: hidden;
  max-height: 92vh;
  overflow-y: auto;
}
.payment-modal-overlay.open .payment-modal {
  transform: translateY(0) scale(1);
}

/* ── Modal header ── */
.pm-header {
  background: linear-gradient(135deg, #1A0F0A 0%, #3D1A0A 100%);
  padding: 1.6rem 1.8rem 1.2rem;
  text-align: center;
  color: #fff;
}
.pm-header .pm-icon { font-size: 2rem; display: block; margin-bottom: 0.3rem; }
.pm-header h2 {
  font-family: 'Playfair Display', serif;
  font-size: 1.5rem; font-weight: 900;
  margin: 0 0 0.15rem;
}
.pm-header .pm-sub { font-size: 0.82rem; color: #c9a899; margin: 0; }
.pm-amount-badge {
  display: inline-block;
  background: rgba(255,255,255,0.15);
  border: 1px solid rgba(255,255,255,0.25);
  border-radius: 50px;
  padding: 0.35rem 1.2rem;
  font-size: 1.5rem; font-weight: 700;
  color: #fff;
  margin-top: 0.8rem;
  letter-spacing: 0.5px;
}

/* ── Tabs ── */
.pm-tabs {
  display: flex;
  border-bottom: 2px solid #f0e8e0;
  background: #fffaf6;
}
.pm-tab {
  flex: 1;
  padding: 0.85rem 0.4rem;
  border: none;
  background: transparent;
  font-family: 'DM Sans', sans-serif;
  font-size: 0.78rem;
  font-weight: 600;
  color: #8B6355;
  cursor: pointer;
  border-bottom: 3px solid transparent;
  margin-bottom: -2px;
  transition: color 0.2s, border-color 0.2s;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 3px;
}
.pm-tab .tab-icon { font-size: 1.2rem; }
.pm-tab:hover { color: #C8460A; }
.pm-tab.active { color: #C8460A; border-bottom-color: #C8460A; }

/* ── Tab panels ── */
.pm-panel { display: none; padding: 1.5rem 1.6rem; }
.pm-panel.active { display: block; }

/* ── QR panel ── */
#qrContainer {
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 1rem;
  width: 190px; height: 190px;
  background: #FFFAF6;
  border: 2px dashed #EDD8C8;
  border-radius: 12px;
  overflow: hidden;
}
#qrContainer img { width:100%; height:100%; object-fit:contain; border-radius:8px; }

.pm-upi-id {
  background: #FFF3ED;
  border: 1px solid #EDD8C8;
  border-radius: 10px;
  padding: 0.6rem 1rem;
  font-size: 0.85rem;
  color: #2C1810;
  margin-bottom: 1rem;
  cursor: pointer;
  transition: background 0.2s;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.5rem;
}
.pm-upi-id:hover { background: #ffe8d6; }
.pm-upi-id .upi-label { font-size: 0.72rem; color: #8B6355; display: block; }
.pm-upi-id .copy-hint { font-size: 0.75rem; color: #C8460A; white-space: nowrap; }

.pm-note {
  font-size: 0.78rem; color: #999;
  line-height: 1.6; text-align: center;
  margin-bottom: 0.5rem;
}

/* ── UPI apps panel ── */
.upi-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 0.7rem;
  margin-bottom: 1rem;
}
.upi-app-btn {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.4rem;
  padding: 0.85rem 0.5rem;
  border: 1.5px solid #EDD8C8;
  border-radius: 14px;
  background: #FFFAF6;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
  font-family: 'DM Sans', sans-serif;
}
.upi-app-btn:hover {
  border-color: #C8460A;
  background: #FFF3ED;
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(200,70,10,0.12);
}
.upi-app-btn .app-logo {
  width: 44px; height: 44px;
  border-radius: 10px;
  object-fit: contain;
  background: #fff;
  padding: 4px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.upi-app-btn .app-logo-emoji {
  width: 44px; height: 44px;
  border-radius: 10px;
  background: #fff;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.6rem;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.upi-app-btn .app-name {
  font-size: 0.7rem;
  font-weight: 600;
  color: #2C1810;
  text-align: center;
  line-height: 1.2;
}

.upi-or-divider {
  text-align: center;
  font-size: 0.75rem;
  color: #bbb;
  margin: 0.8rem 0;
  position: relative;
}
.upi-or-divider::before, .upi-or-divider::after {
  content: '';
  position: absolute;
  top: 50%; width: 42%;
  height: 1px; background: #EDD8C8;
}
.upi-or-divider::before { left: 0; }
.upi-or-divider::after  { right: 0; }

.upi-manual-row {
  display: flex; gap: 0.5rem;
}
.upi-manual-input {
  flex: 1;
  padding: 0.75rem 1rem;
  border: 1.5px solid #EDD8C8;
  border-radius: 10px;
  font-family: 'DM Sans', sans-serif;
  font-size: 0.9rem;
  outline: none;
  background: #FFFAF6;
  transition: border-color 0.2s;
}
.upi-manual-input:focus { border-color: #C8460A; background: #fff; }
.upi-pay-btn {
  padding: 0.75rem 1rem;
  background: #C8460A; color: #fff;
  border: none; border-radius: 10px;
  font-family: 'DM Sans', sans-serif;
  font-weight: 700; font-size: 0.85rem;
  cursor: pointer;
  transition: background 0.2s;
  white-space: nowrap;
}
.upi-pay-btn:hover { background: #E85D1E; }
.upi-err { font-size: 0.75rem; color: #C8460A; margin-top: 0.4rem; display: none; }

/* ── Card panel ── */
.card-preview {
  background: linear-gradient(135deg, #1A0F0A 0%, #5a2d0c 100%);
  border-radius: 16px;
  padding: 1.4rem 1.5rem;
  color: #fff;
  margin-bottom: 1.2rem;
  position: relative;
  overflow: hidden;
  min-height: 130px;
}
.card-preview::before {
  content: '';
  position: absolute;
  top: -30px; right: -30px;
  width: 120px; height: 120px;
  border-radius: 50%;
  background: rgba(255,255,255,0.06);
}
.card-preview::after {
  content: '';
  position: absolute;
  bottom: -40px; right: 20px;
  width: 100px; height: 100px;
  border-radius: 50%;
  background: rgba(255,255,255,0.04);
}
.card-chip { font-size: 1.6rem; margin-bottom: 0.8rem; display: block; }
.card-number-display {
  font-size: 1.05rem; letter-spacing: 3px;
  font-weight: 500; margin-bottom: 0.8rem;
  color: rgba(255,255,255,0.9);
  font-family: 'Courier New', monospace;
}
.card-bottom-row {
  display: flex; justify-content: space-between; align-items: flex-end;
}
.card-holder-display .cl { font-size: 0.62rem; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 1px; }
.card-holder-display .cv { font-size: 0.85rem; font-weight: 600; color: #fff; }
.card-expiry-display .cl { font-size: 0.62rem; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 1px; text-align: right; }
.card-expiry-display .cv { font-size: 0.85rem; font-weight: 600; color: #fff; text-align: right; }
.card-brand-logo { font-size: 1.4rem; }

.card-type-badge {
  position: absolute; top: 1rem; right: 1.2rem;
  font-size: 0.7rem; font-weight: 700;
  background: rgba(255,255,255,0.15);
  border: 1px solid rgba(255,255,255,0.2);
  padding: 2px 10px; border-radius: 20px;
  color: #fff; letter-spacing: 1px;
}

/* Card form */
.card-form { display: flex; flex-direction: column; gap: 0.75rem; }
.cf-group { display: flex; flex-direction: column; gap: 0.3rem; }
.cf-group label {
  font-size: 0.75rem; font-weight: 600;
  color: #8B6355; text-transform: uppercase; letter-spacing: 0.5px;
}
.cf-group input {
  padding: 0.8rem 1rem;
  border: 1.5px solid #EDD8C8;
  border-radius: 10px;
  font-family: 'DM Sans', sans-serif;
  font-size: 0.95rem;
  outline: none;
  background: #FFFAF6;
  transition: border-color 0.2s, background 0.2s;
  color: #1A0F0A;
}
.cf-group input:focus { border-color: #C8460A; background: #fff; }
.cf-group input.cf-error { border-color: #dc3545; background: #fff8f8; }
.cf-group input.cf-valid { border-color: #28a745; }
.cf-group .cf-err-msg { font-size: 0.72rem; color: #dc3545; display: none; }
.cf-group .cf-err-msg.show { display: block; }

.cf-row { display: flex; gap: 0.7rem; }
.cf-row .cf-group { flex: 1; }

.card-type-icons {
  display: flex; gap: 0.4rem; align-items: center;
  margin-bottom: 0.3rem;
}
.cti {
  padding: 3px 8px;
  border: 1.5px solid #EDD8C8;
  border-radius: 6px;
  font-size: 0.68rem; font-weight: 700;
  color: #bbb; background: #f9f9f9;
  transition: all 0.2s;
}
.cti.active { border-color: #C8460A; color: #C8460A; background: #FFF3ED; }

.card-submit-btn {
  width: 100%;
  padding: 1rem;
  background: linear-gradient(135deg, #C8460A, #E85D1E);
  color: #fff;
  border: none; border-radius: 12px;
  font-family: 'DM Sans', sans-serif;
  font-size: 1rem; font-weight: 700;
  cursor: pointer;
  margin-top: 0.3rem;
  transition: opacity 0.2s, transform 0.1s;
  display: flex; align-items: center; justify-content: center; gap: 0.5rem;
}
.card-submit-btn:hover { opacity: 0.92; transform: translateY(-1px); }
.card-submit-btn:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

.secure-note {
  text-align: center;
  font-size: 0.72rem; color: #bbb;
  margin-top: 0.5rem;
}

/* ── Waiting state (shared across all methods) ── */
.pm-waiting-panel {
  display: none;
  flex-direction: column;
  align-items: center;
  padding: 1.5rem 1.6rem 1.8rem;
  text-align: center;
}
.pm-waiting-panel.active { display: flex; }
.pm-waiting-spinner {
  width: 48px; height: 48px;
  border: 4px solid #EDD8C8;
  border-top-color: #C8460A;
  border-radius: 50%;
  animation: spin 0.9s linear infinite;
  margin-bottom: 1rem;
}
@keyframes spin { to { transform: rotate(360deg); } }
.pm-waiting-title {
  font-family: 'Playfair Display', serif;
  font-size: 1.2rem; color: #1A0F0A;
  margin-bottom: 0.3rem;
}
.pm-waiting-sub { font-size: 0.85rem; color: #8B6355; line-height: 1.5; }
.pm-order-ref { font-size: 0.75rem; color: #ccc; margin-top: 1rem; }

/* ── Copied toast ── */
.copy-flash {
  position: fixed; bottom: 1.5rem; left: 50%; transform: translateX(-50%);
  background: #1A0F0A; color: #fff;
  padding: 0.6rem 1.4rem; border-radius: 50px;
  font-size: 0.85rem; font-weight: 600;
  z-index: 99999;
  opacity: 0; transition: opacity 0.3s;
  pointer-events: none;
  white-space: nowrap;
}
.copy-flash.show { opacity: 1; }

/* ══════════════════════════════════════════════
   SUCCESS OVERLAY POPUPS (unchanged)
   ══════════════════════════════════════════════ */
.success-overlay {
  position: fixed; inset: 0;
  background: rgba(0,0,0,0.55);
  display: flex; align-items: center; justify-content: center;
  z-index: 99999;
  animation: soFadeIn 0.3s ease;
}
.success-box {
  background: #fff;
  border-radius: 24px;
  padding: 3rem 2.5rem;
  text-align: center;
  max-width: 380px; width: 90%;
  box-shadow: 0 30px 80px rgba(0,0,0,0.3);
  animation: soPopIn 0.4s cubic-bezier(0.34,1.56,0.64,1);
}
.success-box .sb-icon { font-size: 4rem; margin-bottom: 0.5rem; display: block; }
.success-box h2 {
  font-family: 'Playfair Display', serif;
  font-size: 1.7rem; color: #1A0F0A; margin-bottom: 0.5rem;
}
.success-box p { color: #8B6355; font-size: 0.95rem; line-height: 1.6; }
@keyframes soFadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes soPopIn {
  from { transform: scale(0.7); opacity: 0; }
  to   { transform: scale(1);   opacity: 1; }
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
  <div class="nav-brand">🍽 Spice &amp; Soul</div>
  <div class="nav-links">
    <a href="#menu">Menu</a>
    <a href="history.php">📋 My Orders</a>
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
    <p>Since 2024, Spice &amp; Soul has been serving authentic recipes passed down through generations. Our chefs blend traditional techniques with modern presentation to create unforgettable dining experiences.</p>
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

<!-- ══════════════════════════════════════════════════════════
     PAYMENT MODAL — Tabs: QR Code | UPI Apps | Debit/Credit
     No close button — closes only when admin confirms payment
     ══════════════════════════════════════════════════════════ -->
<div class="payment-modal-overlay" id="paymentModal">
  <div class="payment-modal">

    <!-- Header -->
    <div class="pm-header">
      <span class="pm-icon">🔒</span>
      <h2>Secure Payment</h2>
      <p class="pm-sub">Choose your preferred payment method</p>
      <div class="pm-amount-badge" id="pmAmount">₹0</div>
    </div>

    <!-- ── Tab bar ── -->
    <div class="pm-tabs" id="pmTabBar">
      <button class="pm-tab active" onclick="switchPmTab('qr', this)">
        <span class="tab-icon">📷</span>QR Code
      </button>
      <button class="pm-tab" onclick="switchPmTab('upi', this)">
        <span class="tab-icon">📲</span>UPI Apps
      </button>
      <button class="pm-tab" onclick="switchPmTab('card', this)">
        <span class="tab-icon">💳</span>Debit / Credit
      </button>
    </div>

    <!-- ══ PANEL 1: QR Code ══ -->
    <div class="pm-panel active" id="panelQr">
      <div id="qrContainer">
        <img src="qr.jpeg" alt="Scan to Pay" style="width:100%;height:100%;object-fit:contain;border-radius:8px;">
      </div>

      <div class="pm-upi-id" onclick="copyUpiId()" title="Tap to copy UPI ID">
        <div>
          <span class="upi-label">UPI ID</span>
          <strong id="pmUpiId">sujanbetal18-1@okaxis</strong>
        </div>
        <span class="copy-hint">📋 Tap to copy</span>
      </div>

      <p class="pm-note">
        Open any UPI app · Scan QR &amp; pay <strong id="pmAmountSmall">₹0</strong><br>
        Show payment screenshot to your server · Admin confirms ✅
      </p>
    </div>

    <!-- ══ PANEL 2: UPI Apps ══ -->
    <div class="pm-panel" id="panelUpi">

      <!-- App grid — deep-links open the specific app on Android -->
      <div class="upi-grid" id="upiAppGrid">

        <!-- GPay -->
        <a class="upi-app-btn" id="upiGpay" href="#" onclick="openUpiApp('gpay'); return false;">
          <div class="app-logo-emoji" style="background:linear-gradient(135deg,#4285F4,#34A853);">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f2/Google_Pay_Logo.svg/120px-Google_Pay_Logo.svg.png"
                 style="width:32px;height:32px;object-fit:contain;" alt="GPay"
                 onerror="this.parentNode.textContent='G'">
          </div>
          <span class="app-name">Google Pay</span>
        </a>

        <!-- PhonePe -->
        <a class="upi-app-btn" id="upiPhonepe" href="#" onclick="openUpiApp('phonepe'); return false;">
          <div class="app-logo-emoji" style="background:#6739B7;">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5f/PhonePe_Logo.svg/120px-PhonePe_Logo.svg.png"
                 style="width:32px;height:32px;object-fit:contain;" alt="PhonePe"
                 onerror="this.parentNode.textContent='₱'">
          </div>
          <span class="app-name">PhonePe</span>
        </a>

        <!-- Paytm -->
        <a class="upi-app-btn" id="upiPaytm" href="#" onclick="openUpiApp('paytm'); return false;">
          <div class="app-logo-emoji" style="background:#00B9F1;">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/24/Paytm_Logo_%28standalone%29.svg/120px-Paytm_Logo_%28standalone%29.svg.png"
                 style="width:32px;height:32px;object-fit:contain;" alt="Paytm"
                 onerror="this.parentNode.textContent='P'">
          </div>
          <span class="app-name">Paytm</span>
        </a>

        <!-- Amazon Pay -->
        <a class="upi-app-btn" id="upiAmazon" href="#" onclick="openUpiApp('amazon'); return false;">
          <div class="app-logo-emoji" style="background:#FF9900;">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a9/Amazon_logo.svg/120px-Amazon_logo.svg.png"
                 style="width:32px;height:32px;object-fit:contain;" alt="Amazon Pay"
                 onerror="this.parentNode.textContent='a'">
          </div>
          <span class="app-name">Amazon Pay</span>
        </a>

        <!-- BHIM -->
        <a class="upi-app-btn" id="upiBhim" href="#" onclick="openUpiApp('bhim'); return false;">
          <div class="app-logo-emoji" style="background:#00529B;">
            <img src="https://upload.wikimedia.org/wikipedia/en/thumb/a/a2/BHIM_logo.png/120px-BHIM_logo.png"
                 style="width:32px;height:32px;object-fit:contain;" alt="BHIM"
                 onerror="this.parentNode.innerHTML='🇮🇳'">
          </div>
          <span class="app-name">BHIM UPI</span>
        </a>

        <!-- WhatsApp Pay -->
        <a class="upi-app-btn" id="upiWhatsapp" href="#" onclick="openUpiApp('whatsapp'); return false;">
          <div class="app-logo-emoji" style="background:#25D366;">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6b/WhatsApp.svg/120px-WhatsApp.svg.png"
                 style="width:32px;height:32px;object-fit:contain;" alt="WhatsApp"
                 onerror="this.parentNode.innerHTML='💬'">
          </div>
          <span class="app-name">WhatsApp Pay</span>
        </a>

        <!-- Jio Pay -->
        <a class="upi-app-btn" id="upiJio" href="#" onclick="openUpiApp('jio'); return false;">
          <div class="app-logo-emoji" style="background:#003087;">
            <span style="font-size:1.2rem;font-weight:900;color:#fff;">JIO</span>
          </div>
          <span class="app-name">Jio Pay</span>
        </a>

        <!-- MobiKwik -->
        <a class="upi-app-btn" id="upiMobikwik" href="#" onclick="openUpiApp('mobikwik'); return false;">
          <div class="app-logo-emoji" style="background:#0066FF;">
            <span style="font-size:1.1rem;font-weight:900;color:#fff;">MK</span>
          </div>
          <span class="app-name">MobiKwik</span>
        </a>

        <!-- Freecharge -->
        <a class="upi-app-btn" id="upiFreech" href="#" onclick="openUpiApp('freecharge'); return false;">
          <div class="app-logo-emoji" style="background:#E4202A;">
            <span style="font-size:1rem;font-weight:900;color:#fff;">FC</span>
          </div>
          <span class="app-name">Freecharge</span>
        </a>

      </div><!-- /upi-grid -->

      <div class="upi-or-divider">or enter UPI ID manually</div>

      <div class="upi-manual-row">
        <input type="text" class="upi-manual-input" id="manualUpiInput"
               placeholder="e.g. name@okaxis"
               autocomplete="off" autocorrect="off" spellcheck="false">
        <button class="upi-pay-btn" onclick="payManualUpi()">Pay Now</button>
      </div>
      <div class="upi-err" id="upiManualErr">⚠ Enter a valid UPI ID (e.g. name@bank)</div>

    </div><!-- /panelUpi -->

    <!-- ══ PANEL 3: Debit / Credit Card ══ -->
    <div class="pm-panel" id="panelCard">

      <!-- Visual card preview -->
      <div class="card-preview" id="cardPreview">
        <span class="card-chip">💳</span>
        <div class="card-number-display" id="cardNumDisplay">•••• •••• •••• ••••</div>
        <div class="card-bottom-row">
          <div class="card-holder-display">
            <span class="cl">Card Holder</span>
            <span class="cv" id="cardHolderDisplay">FULL NAME</span>
          </div>
          <div class="card-expiry-display">
            <span class="cl">Expires</span>
            <span class="cv" id="cardExpiryDisplay">MM/YY</span>
          </div>
        </div>
        <div class="card-type-badge" id="cardTypeBadge">CARD</div>
      </div>

      <!-- Card type indicators -->
      <div class="card-type-icons" style="margin-bottom:0.8rem;">
        <span class="cti" id="cti-visa">VISA</span>
        <span class="cti" id="cti-mc">MASTER</span>
        <span class="cti" id="cti-rupay">RuPay</span>
        <span class="cti" id="cti-amex">AMEX</span>
        <span class="cti" id="cti-maestro">MAESTRO</span>
        <span class="cti" id="cti-discover">DISCOVER</span>
      </div>

      <!-- Card form -->
      <div class="card-form" id="cardForm">

        <div class="cf-group">
          <label>Card Number</label>
          <input type="text" id="cfCardNum"
                 placeholder="1234 5678 9012 3456"
                 maxlength="19"
                 inputmode="numeric"
                 autocomplete="cc-number"
                 oninput="onCardNumInput(this)"
                 onblur="validateCardNum()">
          <span class="cf-err-msg" id="errCardNum">Enter a valid card number</span>
        </div>

        <div class="cf-group">
          <label>Cardholder Name</label>
          <input type="text" id="cfName"
                 placeholder="Name as on card"
                 autocomplete="cc-name"
                 oninput="onNameInput(this)"
                 onblur="validateName()">
          <span class="cf-err-msg" id="errName">Enter the name printed on your card</span>
        </div>

        <div class="cf-row">
          <div class="cf-group">
            <label>Expiry Date</label>
            <input type="text" id="cfExpiry"
                   placeholder="MM/YY"
                   maxlength="5"
                   inputmode="numeric"
                   autocomplete="cc-exp"
                   oninput="onExpiryInput(this)"
                   onblur="validateExpiry()">
            <span class="cf-err-msg" id="errExpiry">Enter a valid expiry date</span>
          </div>
          <div class="cf-group">
            <label>CVV</label>
            <input type="password" id="cfCvv"
                   placeholder="•••"
                   maxlength="4"
                   inputmode="numeric"
                   autocomplete="cc-csc"
                   oninput="onCvvInput(this)"
                   onblur="validateCvv()">
            <span class="cf-err-msg" id="errCvv">Enter valid CVV</span>
          </div>
        </div>

        <button class="card-submit-btn" id="cardPayBtn" onclick="submitCardPayment()">
          🔒 Pay <span id="cardBtnAmt">₹0</span> Securely
        </button>

        <p class="secure-note">🔐 256-bit SSL encrypted · Your card details are safe</p>

      </div><!-- /card-form -->

    </div><!-- /panelCard -->

    <!-- ══ Waiting panel — shown after any payment initiated ══ -->
    <div class="pm-waiting-panel" id="pmWaiting">
      <div class="pm-waiting-spinner"></div>
      <div class="pm-waiting-title">Waiting for Confirmation…</div>
      <p class="pm-waiting-sub">
        Payment received · Admin is verifying your transaction<br>
        This page will update automatically ✅
      </p>
      <p class="pm-order-ref" id="pmOrderRef">Order #—</p>
    </div>

  </div><!-- /.payment-modal -->
</div><!-- /.payment-modal-overlay -->

<!-- Copy flash toast -->
<div class="copy-flash" id="copyFlash">📋 Copied!</div>

<!-- SUCCESS TOAST -->
<div class="toast" id="toast"></div>

<!-- ══════════════════════════════════════════════════════
     LIVE ORDER STATUS TRACKER WIDGET
     Appears after order placement — updates automatically
     when admin changes status (no page refresh needed)
     ══════════════════════════════════════════════════════ -->
<style>
/* ── Tracker widget ── */
.order-tracker {
  position: fixed;
  bottom: 1.5rem; right: 1.5rem;
  width: 310px;
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.25);
  font-family: 'DM Sans', sans-serif;
  z-index: 88888;
  overflow: hidden;
  transform: translateY(120%) scale(0.9);
  opacity: 0;
  transition: transform 0.45s cubic-bezier(0.34,1.4,0.64,1), opacity 0.35s;
  pointer-events: none;
}
.order-tracker.visible {
  transform: translateY(0) scale(1);
  opacity: 1;
  pointer-events: all;
}

/* header */
.ot-header {
  background: linear-gradient(135deg, #1A0F0A, #3D1A0A);
  padding: 0.9rem 1.1rem;
  display: flex; align-items: center; justify-content: space-between;
}
.ot-title {
  color: #fff; font-size: 0.85rem; font-weight: 700;
  display: flex; align-items: center; gap: 0.4rem;
}
.ot-order-num { color: #EDD8C8; font-size: 0.75rem; margin-top: 1px; }
.ot-close {
  background: rgba(255,255,255,0.12); border: none;
  color: #fff; width: 26px; height: 26px;
  border-radius: 50%; font-size: 0.75rem; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: background 0.2s;
}
.ot-close:hover { background: rgba(255,255,255,0.25); }

/* steps */
.ot-body { padding: 1.1rem 1.2rem 0.9rem; }

.ot-steps {
  display: flex; align-items: center;
  justify-content: space-between;
  margin-bottom: 0.9rem;
}
.ot-step {
  display: flex; flex-direction: column;
  align-items: center; gap: 4px;
  flex: 1;
  position: relative;
}
.ot-step-icon {
  width: 40px; height: 40px;
  border-radius: 50%;
  background: #f0ebe7;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.1rem;
  border: 2px solid #EDD8C8;
  transition: background 0.4s, border-color 0.4s, transform 0.3s;
  position: relative; z-index: 2;
}
.ot-step.done .ot-step-icon {
  background: #D4EDDA; border-color: #2C7A2C;
  transform: scale(1.1);
}
.ot-step.active .ot-step-icon {
  background: #FFF3CD; border-color: #C8460A;
  animation: stepPulse 1.8s ease-in-out infinite;
  transform: scale(1.15);
}
@keyframes stepPulse {
  0%,100% { box-shadow: 0 0 0 0 rgba(200,70,10,0.3); }
  50%      { box-shadow: 0 0 0 8px rgba(200,70,10,0); }
}
.ot-step-label {
  font-size: 0.65rem; font-weight: 600;
  color: #bbb; text-transform: uppercase; letter-spacing: 0.3px;
  text-align: center; line-height: 1.2;
  transition: color 0.3s;
}
.ot-step.done .ot-step-label  { color: #2C7A2C; }
.ot-step.active .ot-step-label { color: #C8460A; }

/* connector lines */
.ot-line {
  flex: 1; height: 2px;
  background: #EDD8C8;
  margin: 0 4px;
  margin-bottom: 18px; /* align with icon centres */
  position: relative;
  overflow: hidden;
}
.ot-line-fill {
  position: absolute; inset: 0;
  background: #2C7A2C;
  transform: scaleX(0);
  transform-origin: left;
  transition: transform 0.6s ease;
}
.ot-line.filled .ot-line-fill { transform: scaleX(1); }

/* status text */
.ot-status-text {
  font-size: 0.82rem; color: #8B6355;
  text-align: center;
  padding-top: 0.2rem;
  min-height: 1.2em;
  transition: color 0.3s;
}
.ot-status-text.served { color: #2C7A2C; font-weight: 700; }
.ot-status-text.cancelled { color: #C8460A; }

/* live pulse dot */
.ot-live {
  display: flex; align-items: center; gap: 5px;
  font-size: 0.68rem; color: #aaa;
  justify-content: center;
  margin-top: 0.5rem;
  padding-bottom: 0.1rem;
}
.ot-live-dot {
  width: 6px; height: 6px; border-radius: 50%;
  background: #28a745;
  animation: livePulse 2s ease-in-out infinite;
}
@keyframes livePulse {
  0%,100% { opacity:1; transform:scale(1); }
  50%     { opacity:0.3; transform:scale(0.7); }
}
.ot-live-dot.stopped { background:#aaa; animation:none; }
</style>

<div class="order-tracker" id="orderTracker">
  <div class="ot-header">
    <div>
      <div class="ot-title">🍽 Order Status</div>
      <div class="ot-order-num" id="otOrderNum">Order #—</div>
    </div>
    <button class="ot-close" onclick="closeOrderTracker()" title="Dismiss">✕</button>
  </div>
  <div class="ot-body">
    <div class="ot-steps">

      <!-- Step 1: Received / Pending -->
      <div class="ot-step" id="otStep-pending">
        <div class="ot-step-icon">📋</div>
        <div class="ot-step-label">Received</div>
      </div>

      <div class="ot-line" id="otLine-1"><div class="ot-line-fill"></div></div>

      <!-- Step 2: Preparing -->
      <div class="ot-step" id="otStep-preparing">
        <div class="ot-step-icon">👨‍🍳</div>
        <div class="ot-step-label">Preparing</div>
      </div>

      <div class="ot-line" id="otLine-2"><div class="ot-line-fill"></div></div>

      <!-- Step 3: Served -->
      <div class="ot-step" id="otStep-served">
        <div class="ot-step-icon">✅</div>
        <div class="ot-step-label">Served</div>
      </div>

    </div>
    <div class="ot-status-text" id="otStatusText">Waiting for kitchen…</div>
    <div class="ot-live">
      <span class="ot-live-dot" id="otLiveDot"></span>
      <span id="otLiveLabel">Updating live</span>
    </div>
  </div>
</div>

<footer class="footer">
  <p>🍽 Spice &amp; Soul Restaurant · Kolkata · Made with ❤️</p>
</footer>

<!-- QR code library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<!-- Main app logic (unchanged) -->
<script src="js/app.js"></script>

<script>
// ═══════════════════════════════════════════════════════════════════
//  PAYMENT MODAL — Full handler
//  Supports: QR Code | UPI Apps | Debit/Credit Card
//  Admin confirmation flow is identical for all methods
// ═══════════════════════════════════════════════════════════════════

const UPI_ID  = 'sujanbetal18-1@okaxis';
const UPI_NAME = 'Spice%20%26%20Soul';

let paymentCheckTimer = null;
let _currentOrderId   = null;
let _currentAmount    = 0;

/* ── Device detection ────────────────────────────────── */
const _isIOS     = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
const _isAndroid = /Android/.test(navigator.userAgent);

/* ── App Store / Play Store links ────────────────────── */
const APP_STORE = {
  gpay      : { ios: 'https://apps.apple.com/app/google-pay/id1481099965',      android: 'https://play.google.com/store/apps/details?id=com.google.android.apps.nbu.paisa.user' },
  phonepe   : { ios: 'https://apps.apple.com/app/phonepe/id1146674610',          android: 'https://play.google.com/store/apps/details?id=com.phonepe.app' },
  paytm     : { ios: 'https://apps.apple.com/app/paytm/id473941634',             android: 'https://play.google.com/store/apps/details?id=net.one97.paytm' },
  amazon    : { ios: 'https://apps.apple.com/app/amazon-shopping/id297606951',   android: 'https://play.google.com/store/apps/details?id=in.amazon.mShop.android.shopping' },
  bhim      : { ios: 'https://apps.apple.com/app/bhim/id1200315258',             android: 'https://play.google.com/store/apps/details?id=in.org.npci.upiapp' },
  whatsapp  : { ios: 'https://apps.apple.com/app/whatsapp/id310633997',          android: 'https://play.google.com/store/apps/details?id=com.whatsapp' },
  jio       : { ios: 'https://apps.apple.com/app/jio/id1442327078',              android: 'https://play.google.com/store/apps/details?id=com.jio.myjio' },
  mobikwik  : { ios: 'https://apps.apple.com/app/mobikwik/id527308852',          android: 'https://play.google.com/store/apps/details?id=com.mobikwik_new' },
  freecharge: { ios: 'https://apps.apple.com/app/freecharge/id500180255',        android: 'https://play.google.com/store/apps/details?id=com.freecharge.android' },
};

const APP_NAMES = {
  gpay:'Google Pay', phonepe:'PhonePe', paytm:'Paytm',
  amazon:'Amazon Pay', bhim:'BHIM UPI'
};

/* ── Build deep links (Android & iOS schemes) ─────────── */
function buildUpiLink(app, amount, orderId) {
  const base    = `pa=${UPI_ID}&pn=${UPI_NAME}&am=${amount}&cu=INR&tn=Order%23${orderId}`;
  // iOS uses the same app-specific schemes; generic upi:// does NOT work on iOS
  const schemes = {
    gpay      : `tez://upi/pay?${base}`,          // GPay: same scheme on iOS & Android
    phonepe   : `phonepe://pay?${base}`,           // PhonePe: same on both
    paytm     : `paytmmp://upi/pay?${base}`,       // Paytm
    amazon    : `amazonpay://extras?${base}`,      // Amazon Pay
    bhim      : `bhim://pay?${base}`,              // BHIM — iOS uses bhim:// not upi://
  };
  return schemes[app] || `upi://pay?${base}`;
}

/* ── Smart deep-link launcher (Android + iOS) ─────────── */
function tryDeepLink(link, onSuccess, onFail) {
  let left = true;

  // visibilitychange fires when the browser goes to background (app opened)
  function onHide() {
    if (document.hidden) { left = true; cleanup(); onSuccess(); }
  }
  // pagehide fires on some iOS versions
  function onPageHide() { left = true; cleanup(); onSuccess(); }

  function cleanup() {
    document.removeEventListener('visibilitychange', onHide);
    window.removeEventListener('pagehide', onPageHide);
  }

  document.addEventListener('visibilitychange', onHide);
  window.addEventListener('pagehide', onPageHide);

  // Attempt to open the deep link
  const iframe = document.createElement('iframe');
  iframe.style.display = 'none';
  iframe.src = link;
  document.body.appendChild(iframe);
  setTimeout(() => document.body.removeChild(iframe), 500);

  // Also try direct navigation (needed for some iOS apps)
  try { window.location.href = link; } catch(e) {}

  // If page is still visible after 2.2 s → app not installed
  setTimeout(() => {
    cleanup();
    if (!document.hidden) onFail();
    else onSuccess();
  }, 2200);
}

/* ── Open UPI app button ─────────────────────────────── */
function openUpiApp(app) {
  if (!_currentOrderId) return;

  // WhatsApp Pay is not available on iPhone
  if (app === 'whatsapp' && _isIOS) {
    showNotFoundPanel(app, true); return;
  }

  const link = buildUpiLink(app, _currentAmount.toFixed(2), _currentOrderId);

  tryDeepLink(
    link,
    () => showWaitingPanel(),          // ✅ app opened
    () => showNotFoundPanel(app, false) // ❌ app not installed
  );
}

/* ── App-not-found fallback panel ─────────────────────── */
function showNotFoundPanel(app, iosUnavailable) {
  const name    = APP_NAMES[app] || app;
  const storeUrl = _isIOS
    ? (APP_STORE[app] ? APP_STORE[app].ios     : null)
    : (APP_STORE[app] ? APP_STORE[app].android : null);
  const storeLabel = _isIOS ? '🍎 Download on App Store' : '▶ Get on Play Store';
  const msg = iosUnavailable
    ? `${name} is not available on iPhone. Please use another payment method.`
    : `${name} app not found on your device.`;

  // Build small fallback box inside the UPI panel
  let existing = document.getElementById('upiNotFound');
  if (existing) existing.remove();

  const box = document.createElement('div');
  box.id = 'upiNotFound';
  box.style.cssText = `
    background:#FFF3ED; border:1.5px solid #EDD8C8; border-radius:14px;
    padding:1rem 1.2rem; margin-top:0.8rem; text-align:center;
    font-family:'DM Sans',sans-serif; animation: cardIn 0.3s ease;
  `;
  box.innerHTML = `
    <div style="font-size:1.6rem;margin-bottom:0.3rem;">⚠️</div>
    <div style="font-weight:700;color:#1A0F0A;font-size:0.9rem;margin-bottom:0.3rem;">${msg}</div>
    <div style="font-size:0.78rem;color:#8B6355;margin-bottom:0.8rem;">
      ${iosUnavailable ? 'Try Google Pay, PhonePe or Paytm instead.' : 'Install the app or use another method below.'}
    </div>
    <div style="display:flex;gap:0.5rem;justify-content:center;flex-wrap:wrap;">
      ${storeUrl && !iosUnavailable ? `<a href="${storeUrl}" target="_blank" style="
        background:#C8460A;color:#fff;border-radius:20px;padding:0.5rem 1rem;
        font-size:0.78rem;font-weight:700;text-decoration:none;">${storeLabel}</a>` : ''}
      <button onclick="switchPmTab('qr', document.querySelectorAll('.pm-tab')[0]); this.closest('#upiNotFound').remove();" style="
        background:#1A0F0A;color:#fff;border:none;border-radius:20px;
        padding:0.5rem 1rem;font-size:0.78rem;font-weight:700;cursor:pointer;">
        📷 Use QR Code
      </button>
      <button onclick="this.closest('#upiNotFound').remove();" style="
        background:#f0ebe7;color:#2C1810;border:none;border-radius:20px;
        padding:0.5rem 1rem;font-size:0.78rem;font-weight:700;cursor:pointer;">
        ← Try Another
      </button>
    </div>`;

  document.getElementById('panelUpi').appendChild(box);
}

/* ── Pay via manually entered UPI ID ─────────────────── */
function payManualUpi() {
  const val = document.getElementById('manualUpiInput').value.trim();
  const err = document.getElementById('upiManualErr');
  if (!val || !/^[\w.\-]+@[\w]+$/.test(val)) {
    err.style.display = 'block'; return;
  }
  err.style.display = 'none';

  // Generic upi:// works on Android; on iOS we try it but show fallback if needed
  const link = `upi://pay?pa=${encodeURIComponent(val)}&pn=${UPI_NAME}&am=${_currentAmount.toFixed(2)}&cu=INR&tn=Order%23${_currentOrderId}`;

  if (_isIOS) {
    // On iOS, show copy + instruction if upi:// doesn't work
    tryDeepLink(link, showWaitingPanel, () => {
      // Show copy UPI ID suggestion as fallback
      const f = document.getElementById('copyFlash');
      f.textContent = '⚠️ Open your UPI app manually & pay to: ' + UPI_ID;
      f.style.maxWidth = '90vw';
      f.style.borderRadius = '12px';
      f.classList.add('show');
      setTimeout(() => { f.classList.remove('show'); f.style.maxWidth=''; }, 4000);
      showWaitingPanel();
    });
  } else {
    tryDeepLink(link, showWaitingPanel, showWaitingPanel);
  }
}

/* ── Copy UPI ID ─────────────────────────────────────── */
function copyUpiId() {
  if (navigator.clipboard) {
    navigator.clipboard.writeText(UPI_ID).then(showCopyFlash).catch(showCopyFlash);
  } else {
    // fallback
    const el = document.createElement('textarea');
    el.value = UPI_ID; document.body.appendChild(el);
    el.select(); document.execCommand('copy');
    document.body.removeChild(el);
    showCopyFlash();
  }
}
function showCopyFlash() {
  const f = document.getElementById('copyFlash');
  f.textContent = '📋 UPI ID Copied!';
  f.classList.add('show');
  setTimeout(() => f.classList.remove('show'), 2200);
}

/* ── Tab switching ───────────────────────────────────── */
function switchPmTab(tab, btn) {
  // Hide waiting, show tab bar + panels
  document.getElementById('pmWaiting').classList.remove('active');
  document.getElementById('pmTabBar').style.display = '';

  document.querySelectorAll('.pm-tab').forEach(b => b.classList.remove('active'));
  document.querySelectorAll('.pm-panel').forEach(p => p.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('panel' + tab.charAt(0).toUpperCase() + tab.slice(1)).classList.add('active');
}

/* ── Show waiting panel (after any payment triggered) ── */
function showWaitingPanel() {
  document.querySelectorAll('.pm-panel').forEach(p => p.classList.remove('active'));
  document.getElementById('pmTabBar').style.display = 'none';
  document.getElementById('pmWaiting').classList.add('active');
}

// ═══════════════════════════════════════════════════════════════════
//  CARD VALIDATION
// ═══════════════════════════════════════════════════════════════════

/* Luhn algorithm */
function luhn(num) {
  let n = num.replace(/\s/g, '');
  let sum = 0, alt = false;
  for (let i = n.length - 1; i >= 0; i--) {
    let d = parseInt(n[i], 10);
    if (alt) { d *= 2; if (d > 9) d -= 9; }
    sum += d; alt = !alt;
  }
  return sum % 10 === 0;
}

/* Detect card type from number prefix */
function detectCardType(num) {
  const n = num.replace(/\s/g, '');
  if (/^4/.test(n)) return 'visa';
  if (/^5[1-5]/.test(n) || /^2[2-7]/.test(n)) return 'mc';
  if (/^(508[5-9]|6069[8-9]|607[0-9]|608[0-5]|6521|6522|81[0-9]{2}|82[0-9]{2}|508[1-9])/.test(n)) return 'rupay';
  if (/^3[47]/.test(n)) return 'amex';
  if (/^6(?:011|5)/.test(n)) return 'discover';
  if (/^6304|^6759|^6761|^6762|^6763/.test(n)) return 'maestro';
  return null;
}

/* Format card number with spaces */
function onCardNumInput(el) {
  let v = el.value.replace(/\D/g, '');
  let type = detectCardType(v);
  // Amex is 4-6-5, others 4-4-4-4
  if (type === 'amex') {
    v = v.slice(0,15);
    v = v.replace(/(\d{4})(\d{1,6})?(\d{1,5})?/, (_, a, b, c) => [a, b, c].filter(Boolean).join(' '));
  } else {
    v = v.slice(0,16);
    v = v.replace(/(.{4})/g, '$1 ').trim();
  }
  el.value = v;

  // Live card preview
  const raw = el.value.replace(/\s/g, '');
  let display = '•••• •••• •••• ••••';
  if (raw.length > 0) {
    display = '';
    for (let i = 0; i < 16; i++) {
      if (i > 0 && i % 4 === 0) display += ' ';
      display += raw[i] ? raw[i] : '•';
    }
  }
  document.getElementById('cardNumDisplay').textContent = display;
  highlightCardType(type);
}

function highlightCardType(type) {
  const names = { visa:'VISA', mc:'MASTERCARD', rupay:'RUPAY', amex:'AMEX', maestro:'MAESTRO', discover:'DISCOVER' };
  ['visa','mc','rupay','amex','maestro','discover'].forEach(t => {
    document.getElementById('cti-' + t).classList.toggle('active', t === type);
  });
  document.getElementById('cardTypeBadge').textContent = type ? names[type] || 'CARD' : 'CARD';
}

function onNameInput(el) {
  el.value = el.value.toUpperCase();
  document.getElementById('cardHolderDisplay').textContent = el.value || 'FULL NAME';
}

function onExpiryInput(el) {
  let v = el.value.replace(/\D/g, '').slice(0, 4);
  if (v.length >= 3) v = v.slice(0,2) + '/' + v.slice(2);
  el.value = v;
  document.getElementById('cardExpiryDisplay').textContent = v || 'MM/YY';
}

function onCvvInput(el) {
  el.value = el.value.replace(/\D/g, '').slice(0, 4);
}

/* Individual field validators */
function validateCardNum() {
  const el  = document.getElementById('cfCardNum');
  const raw = el.value.replace(/\s/g, '');
  const ok  = raw.length >= 13 && raw.length <= 19 && luhn(raw);
  el.classList.toggle('cf-error', !ok);
  el.classList.toggle('cf-valid', ok);
  document.getElementById('errCardNum').classList.toggle('show', !ok && raw.length > 0);
  return ok;
}

function validateName() {
  const el = document.getElementById('cfName');
  const ok = el.value.trim().length >= 3;
  el.classList.toggle('cf-error', !ok);
  el.classList.toggle('cf-valid', ok);
  document.getElementById('errName').classList.toggle('show', !ok && el.value.length > 0);
  return ok;
}

function validateExpiry() {
  const el  = document.getElementById('cfExpiry');
  const val = el.value;
  let ok = false;
  if (/^\d{2}\/\d{2}$/.test(val)) {
    const [mm, yy] = val.split('/').map(Number);
    const now = new Date();
    const curYY = now.getFullYear() % 100;
    const curMM = now.getMonth() + 1;
    ok = mm >= 1 && mm <= 12 && (yy > curYY || (yy === curYY && mm >= curMM));
  }
  el.classList.toggle('cf-error', !ok);
  el.classList.toggle('cf-valid', ok);
  document.getElementById('errExpiry').classList.toggle('show', !ok && val.length > 0);
  return ok;
}

function validateCvv() {
  const el  = document.getElementById('cfCvv');
  const raw = document.getElementById('cfCardNum').value.replace(/\s/g, '');
  const isAmex = detectCardType(raw) === 'amex';
  const ok  = isAmex ? el.value.length === 4 : (el.value.length === 3 || el.value.length === 4);
  el.classList.toggle('cf-error', !ok);
  el.classList.toggle('cf-valid', ok);
  document.getElementById('errCvv').classList.toggle('show', !ok && el.value.length > 0);
  return ok;
}

/* Submit card payment */
function submitCardPayment() {
  const v1 = validateCardNum();
  const v2 = validateName();
  const v3 = validateExpiry();
  const v4 = validateCvv();
  if (!v1 || !v2 || !v3 || !v4) return;

  const btn = document.getElementById('cardPayBtn');
  btn.disabled = true;
  btn.innerHTML = '<span class="pm-waiting-spinner" style="width:18px;height:18px;border-width:2px;margin:0;"></span> Processing…';

  // Simulate processing delay → then show waiting panel
  // (Admin confirms payment from their panel as usual)
  setTimeout(showWaitingPanel, 2200);
}

// ═══════════════════════════════════════════════════════════════════
//  OPEN PAYMENT MODAL  — called from app.js after order is placed
// ═══════════════════════════════════════════════════════════════════
function showPaymentQR(orderId, totalAmount) {
  _currentOrderId = orderId;
  _currentAmount  = parseFloat(totalAmount);
  const amtStr    = '₹' + _currentAmount.toFixed(2);

  // Set amounts everywhere
  document.getElementById('pmAmount').textContent      = amtStr;
  document.getElementById('pmAmountSmall').textContent = amtStr;
  document.getElementById('pmOrderRef').textContent    = 'Order #' + orderId;
  document.getElementById('pmUpiId').textContent       = UPI_ID;
  document.getElementById('cardBtnAmt').textContent    = amtStr;

  // Reset to QR tab
  switchPmTab('qr', document.querySelector('.pm-tab'));
  document.querySelectorAll('.pm-tab').forEach(b => b.classList.remove('active'));
  document.querySelector('.pm-tab').classList.add('active');
  document.querySelectorAll('.pm-panel').forEach(p => p.classList.remove('active'));
  document.getElementById('panelQr').classList.add('active');
  document.getElementById('pmTabBar').style.display = '';
  document.getElementById('pmWaiting').classList.remove('active');

  // Reset card form
  ['cfCardNum','cfName','cfExpiry','cfCvv'].forEach(id => {
    const el = document.getElementById(id);
    el.value = ''; el.classList.remove('cf-error','cf-valid');
  });
  document.querySelectorAll('.cf-err-msg').forEach(e => e.classList.remove('show'));
  document.getElementById('cardNumDisplay').textContent = '•••• •••• •••• ••••';
  document.getElementById('cardHolderDisplay').textContent = 'FULL NAME';
  document.getElementById('cardExpiryDisplay').textContent = 'MM/YY';
  document.getElementById('cardTypeBadge').textContent = 'CARD';
  highlightCardType(null);
  const cardBtn = document.getElementById('cardPayBtn');
  cardBtn.disabled = false;
  cardBtn.innerHTML = '🔒 Pay ' + amtStr + ' Securely';

  // Reset manual UPI
  document.getElementById('manualUpiInput').value = '';
  document.getElementById('upiManualErr').style.display = 'none';

  // Open modal
  document.getElementById('paymentModal').classList.add('open');

  // Start polling for admin confirmation (same as before)
  if (paymentCheckTimer) clearInterval(paymentCheckTimer);
  paymentCheckTimer = setInterval(async () => {
    try {
      const res  = await fetch(`php/check_status.php?order_id=${orderId}`);
      const data = await res.json();
      if (data.status === 'paid') {
        clearInterval(paymentCheckTimer);
        paymentCheckTimer = null;
        handlePaymentConfirmed();
      }
    } catch (err) {
      console.log('Polling…', err);
    }
  }, 3000);
}

// ═══════════════════════════════════════════════════════════════════
//  SUCCESS FLOW — unchanged, admin marks paid → triggers this
// ═══════════════════════════════════════════════════════════════════
function handlePaymentConfirmed() {
  document.getElementById('paymentModal').classList.remove('open');

  showSuccessPopup(
    '✅', 'Payment Confirmed!',
    'Your payment has been received successfully.',
    () => {
      showSuccessPopup(
        '🎉', 'Order Placed!',
        'Your order is confirmed and our chefs are already cooking. Sit tight!',
        () => { if (typeof clearCart === 'function') clearCart(); },
        2500
      );
    },
    1800
  );
}

function showSuccessPopup(icon, title, message, onClose, autoCloseMs) {
  const existing = document.getElementById('dynamicSuccessOverlay');
  if (existing) existing.remove();

  const overlay = document.createElement('div');
  overlay.id        = 'dynamicSuccessOverlay';
  overlay.className = 'success-overlay';
  overlay.innerHTML = `
    <div class="success-box">
      <span class="sb-icon">${icon}</span>
      <h2>${title}</h2>
      <p>${message}</p>
    </div>`;
  document.body.appendChild(overlay);

  setTimeout(() => {
    overlay.style.opacity    = '0';
    overlay.style.transition = 'opacity 0.4s';
    setTimeout(() => {
      overlay.remove();
      if (typeof onClose === 'function') onClose();
    }, 400);
  }, autoCloseMs || 2000);
}
</script>

<script>
// ═══════════════════════════════════════════════════════════════════
//  LIVE ORDER STATUS TRACKER
//  Shows a floating widget after order placement.
//  Polls php/check_status.php every 4 seconds for status updates.
//  Updates the step indicators without any page refresh.
// ═══════════════════════════════════════════════════════════════════

let _trackerOrderId   = null;
let _trackerTimer     = null;
let _trackerLastStatus = null;

const STATUS_STEPS = {
  pending   : { step: 1, text: 'Order received — kitchen will start soon ⏳'   },
  preparing : { step: 2, text: 'Chefs are cooking your food! 👨‍🍳'               },
  served    : { step: 3, text: 'Your food is on the way — enjoy your meal! 🎉'  },
  cancelled : { step: 0, text: 'Order was cancelled. Please contact staff.'      },
};

/* ── Show tracker widget ── */
function showOrderTracker(orderId) {
  _trackerOrderId    = orderId;
  _trackerLastStatus = null;

  document.getElementById('otOrderNum').textContent = 'Order #' + orderId;

  // Reset all steps to neutral
  ['pending','preparing','served'].forEach(s => {
    document.getElementById('otStep-' + s).className = 'ot-step';
  });
  document.getElementById('otLine-1').classList.remove('filled');
  document.getElementById('otLine-2').classList.remove('filled');

  const statusEl = document.getElementById('otStatusText');
  statusEl.className   = 'ot-status-text';
  statusEl.textContent = 'Waiting for kitchen…';

  document.getElementById('otLiveDot').className   = 'ot-live-dot';
  document.getElementById('otLiveLabel').textContent = 'Updating live';

  // Show widget
  document.getElementById('orderTracker').classList.add('visible');

  // Apply pending state immediately
  applyTrackerStatus('pending');

  // Start polling
  if (_trackerTimer) clearInterval(_trackerTimer);
  _trackerTimer = setInterval(pollOrderStatus, 4000);
}

/* ── Hide / dismiss tracker ── */
function closeOrderTracker() {
  document.getElementById('orderTracker').classList.remove('visible');
  stopTrackerPolling();
}

function stopTrackerPolling() {
  if (_trackerTimer) { clearInterval(_trackerTimer); _trackerTimer = null; }
  const dot   = document.getElementById('otLiveDot');
  const label = document.getElementById('otLiveLabel');
  if (dot)   dot.className   = 'ot-live-dot stopped';
  if (label) label.textContent = 'Updates stopped';
}

/* ── Poll check_status.php ── */
async function pollOrderStatus() {
  if (!_trackerOrderId) return;
  try {
    const res  = await fetch('php/check_status.php?order_id=' + _trackerOrderId + '&_t=' + Date.now());
    const data = await res.json();
    if (!data.success) return;

    const status = data.status;  // pending|preparing|served|cancelled

    // ── Payment confirmed? (legacy flow still works) ──
    if (data.payment === 'paid' && typeof handlePaymentConfirmed === 'function') {
      const payModal = document.getElementById('paymentModal');
      if (payModal && payModal.classList.contains('open')) {
        if (paymentCheckTimer) { clearInterval(paymentCheckTimer); paymentCheckTimer = null; }
        handlePaymentConfirmed();
      }
    }

    // ── Order status changed? ──
    if (status && status !== _trackerLastStatus) {
      _trackerLastStatus = status;
      applyTrackerStatus(status);

      // Stop polling when order is done
      if (status === 'served' || status === 'cancelled') {
        setTimeout(stopTrackerPolling, 5000); // keep widget visible 5s
      }
    }
  } catch (e) {
    // Network hiccup — keep polling silently
  }
}

/* ── Update step UI ── */
function applyTrackerStatus(status) {
  const info = STATUS_STEPS[status] || STATUS_STEPS.pending;
  const step = info.step;

  // Step states: done = completed, active = current
  const steps = [
    { key: 'pending',   num: 1 },
    { key: 'preparing', num: 2 },
    { key: 'served',    num: 3 },
  ];

  steps.forEach(({ key, num }) => {
    const el = document.getElementById('otStep-' + key);
    if (!el) return;
    if (status === 'cancelled') {
      el.className = 'ot-step';
    } else if (num < step) {
      el.className = 'ot-step done';
    } else if (num === step) {
      el.className = 'ot-step active';
    } else {
      el.className = 'ot-step';
    }
  });

  // Connector lines
  if (status !== 'cancelled') {
    document.getElementById('otLine-1').classList.toggle('filled', step >= 2);
    document.getElementById('otLine-2').classList.toggle('filled', step >= 3);
  }

  // Status text
  const textEl = document.getElementById('otStatusText');
  textEl.textContent = info.text;
  textEl.className   = 'ot-status-text' + (status === 'served' ? ' served' : status === 'cancelled' ? ' cancelled' : '');
}

// ═══════════════════════════════════════════════════════════════════
//  HOOK INTO handlePaymentConfirmed — launch tracker after payment
// ═══════════════════════════════════════════════════════════════════
const _origHandlePaymentConfirmed = typeof handlePaymentConfirmed === 'function'
  ? handlePaymentConfirmed : null;

// Override to also start the tracker
window.handlePaymentConfirmed = function () {
  if (_origHandlePaymentConfirmed) _origHandlePaymentConfirmed();

  // Show tracker after the success popups (delay 4s)
  if (_currentOrderId) {
    setTimeout(() => showOrderTracker(_currentOrderId), 4200);
  }
};

// Also expose for external calls from app.js if it uses showPaymentQR
const _origShowPaymentQR = typeof showPaymentQR === 'function' ? showPaymentQR : null;
window._trackerOrderIdFromQR = null;
if (_origShowPaymentQR) {
  window.showPaymentQR = function(orderId, totalAmount) {
    window._trackerOrderIdFromQR = orderId;
    _origShowPaymentQR(orderId, totalAmount);
  };
}
</script>

</body>
</html>