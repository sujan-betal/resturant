# 🍽 Spice & Soul Restaurant

A full-stack restaurant web app built with HTML, CSS, JavaScript, PHP & MySQL.

## Features
- 🏠 Beautiful homepage with hero section
- 🍛 Menu with category filters
- 🛒 Cart with add/remove/quantity controls
- 📋 Order placement with customer details
- 👨‍💼 Admin panel with order management
- 📊 Dashboard stats (total orders, revenue, pending)
- 🔄 Toggle menu item availability

## Setup Instructions

### 1. Place in XAMPP
Copy the `restaurant` folder to:
```
C:\xampp\htdocs\restaurant
```

### 2. Import Database
- Open MySQL Workbench or phpMyAdmin
- Run the file: `restaurant.sql`
- This creates `restaurant_db` with all tables and sample data

### 3. Update Connection
Edit `php/connect.php`:
```php
$db_host = "127.0.0.1";
$db_user = "root";
$db_pass = "";       // your password
$db_name = "restaurant_db";
$db_port = 3306;
```

### 4. Run
Open browser:
```
http://localhost/restaurant/index.php       ← Customer view
http://localhost/restaurant/admin.php       ← Admin panel
```

## File Structure
```
restaurant/
├── index.php           ← Homepage
├── admin.php           ← Admin panel
├── restaurant.sql      ← Database
├── css/
│   ├── style.css       ← Main styles
│   └── admin.css       ← Admin styles
├── js/
│   ├── app.js          ← Frontend logic
│   └── admin.js        ← Admin logic
└── php/
    ├── connect.php     ← DB connection
    ├── menu.php        ← Menu API
    ├── order.php       ← Orders API
    └── admin_api.php   ← Admin API
```
