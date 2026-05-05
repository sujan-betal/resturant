<?php
// php/check_status.php
require_once 'connect.php'; // Apni DB file ka naam verify kar lein
header('Content-Type: application/json');

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($order_id <= 0) {
    echo json_encode(['status' => 'error']);
    exit;
}

// Check if order is paid in the database
$stmt = $pdo->prepare("SELECT payment_status FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if ($order) {
    echo json_encode(['status' => $order['payment_status']]); // 'unpaid' ya 'paid' aayega
} else {
    echo json_encode(['status' => 'not_found']);
}
?>