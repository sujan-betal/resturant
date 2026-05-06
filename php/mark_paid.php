<?php
// php/mark_paid.php
header('Content-Type: application/json');

// ── Direct DB connection (no dependency on connect.php variable name) ──
$host   = "localhost";
$user   = "root";
$pass   = "";             // XAMPP default = empty password
$dbname = "restaurant_db"; // ← your database name (check phpMyAdmin if unsure)

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo json_encode([
        'success' => false,
        'error'   => 'DB failed: ' . $conn->connect_error
    ]);
    exit;
}

// ── Validate POST ──
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'POST required']);
    exit;
}

$order_id = intval($_POST['order_id'] ?? 0);
if ($order_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid order_id: ' . $order_id]);
    exit;
}

// ── Auto-add payment column if missing ──
$conn->query("ALTER TABLE orders ADD COLUMN IF NOT EXISTS payment VARCHAR(20) DEFAULT 'pending'");

// ── Mark as paid ──
$stmt = $conn->prepare("UPDATE orders SET payment = 'paid' WHERE id = ?");
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("i", $order_id);
$stmt->execute();

echo json_encode(['success' => true, 'order_id' => $order_id]);

$stmt->close();
$conn->close();
?>