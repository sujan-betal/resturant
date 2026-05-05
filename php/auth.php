<?php
session_start();
header('Content-Type: application/json');

// ===== ADMIN CREDENTIALS (change these!) =====
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'spice123');  // Change this password!

$action = $_GET['action'] ?? 'login';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === ADMIN_USER && $password === ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user'] = $username;
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
    }

} elseif ($action === 'logout') {
    session_destroy();
    echo json_encode(['success' => true]);

} elseif ($action === 'check') {
    echo json_encode(['logged_in' => isset($_SESSION['admin_logged_in'])]);
}
?>
