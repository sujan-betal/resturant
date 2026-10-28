<?php
// php/auth.php — Handles Admin Login & Logout

session_start();
header('Content-Type: application/json');

// Portfolio demo access is a separate read-only session; never authenticate it as the restaurant admin.
if (isset($_GET['action']) && $_GET['action'] === 'demo') {
    session_regenerate_id(true);
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_demo'] = true;
    $_SESSION['admin_user'] = 'Demo viewer';
    echo json_encode(['success' => true, 'message' => 'Read-only demo session started']);
    exit;
}

// ═══════════════════════════════════════
//  LOGOUT  →  php/auth.php?action=logout
// ═══════════════════════════════════════
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();

    // Redirect back to login page
    header('Content-Type: text/html');
    header('Location: ../admin_login.php');
    exit;
}

// ═══════════════════════════════════════
//  LOGIN  →  POST from admin_login.php
// ═══════════════════════════════════════
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

// ── Admin credentials ──
// Change these to your preferred username & password
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin');   // ← change this!

if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
    unset($_SESSION['admin_demo']);
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_user']      = $username;
    echo json_encode(['success' => true, 'message' => 'Login successful']);
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid username or password']);
}
