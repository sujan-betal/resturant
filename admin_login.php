<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login - Spice & Soul</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
  font-family: 'DM Sans', sans-serif;
  min-height: 100vh;
  background: linear-gradient(135deg, #1A0F0A 0%, #3D1A0A 100%);
  display: flex; align-items: center; justify-content: center;
}
.login-box {
  background: white; border-radius: 20px;
  padding: 3rem 2.5rem; width: 100%; max-width: 400px;
  box-shadow: 0 30px 80px rgba(0,0,0,0.4);
  text-align: center;
}
.login-logo { font-size: 2.5rem; margin-bottom: 0.5rem; }
.login-title {
  font-family: 'Playfair Display', serif;
  font-size: 1.8rem; font-weight: 900;
  color: #1A0F0A; margin-bottom: 0.3rem;
}
.login-sub { color: #8B6355; font-size: 0.9rem; margin-bottom: 2rem; }

.form-group { margin-bottom: 1.2rem; text-align: left; }
.form-group label {
  display: block; font-weight: 600;
  font-size: 0.85rem; margin-bottom: 0.4rem; color: #2C1810;
}
.form-group input {
  width: 100%; padding: 0.8rem 1rem;
  border: 2px solid #EDD8C8; border-radius: 10px;
  font-family: 'DM Sans', sans-serif; font-size: 0.95rem;
  outline: none; transition: border-color 0.2s;
  background: #FFFAF6;
}
.form-group input:focus { border-color: #C8460A; background: white; }

.login-btn {
  width: 100%; padding: 0.9rem;
  background: #C8460A; color: white;
  border: none; border-radius: 50px;
  font-weight: 700; font-size: 1rem;
  cursor: pointer; transition: background 0.2s, transform 0.1s;
  margin-top: 0.5rem;
}
.login-btn:hover { background: #E85D1E; transform: translateY(-1px); }

.error-msg {
  background: #F8D7DA; color: #721C24;
  padding: 0.7rem 1rem; border-radius: 8px;
  font-size: 0.85rem; margin-bottom: 1rem;
  display: none;
}
.error-msg.show { display: block; }

.back-link {
  display: block; margin-top: 1.5rem;
  color: #8B6355; font-size: 0.85rem;
  text-decoration: none;
}
.back-link:hover { color: #C8460A; }
</style>
</head>
<body>

<div class="login-box">
  <div class="login-logo">🍽</div>
  <h1 class="login-title">Admin Login</h1>
  <p class="login-sub">Spice & Soul Restaurant</p>

  <div class="error-msg" id="errorMsg">❌ Wrong username or password!</div>

  <form id="loginForm">
    <div class="form-group">
      <label>Username</label>
      <input type="text" id="username" placeholder="Enter username" required>
    </div>
    <div class="form-group">
      <label>Password</label>
      <input type="password" id="password" placeholder="Enter password" required>
    </div>
    <button type="submit" class="login-btn">Login to Admin Panel</button>
  </form>

  <a href="index.php" class="back-link">← Back to Website</a>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const username = document.getElementById('username').value;
  const password = document.getElementById('password').value;

  const form = new FormData();
  form.append('username', username);
  form.append('password', password);

  const res = await fetch('php/auth.php', { method: 'POST', body: form });
  const data = await res.json();

  if (data.success) {
    window.location.href = 'admin.php';
  } else {
    document.getElementById('errorMsg').classList.add('show');
  }
});
</script>

</body>
</html>
