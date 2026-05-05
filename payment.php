<?php
// php/payment.php  — Mark an order as paid
// Called by admin panel: POST with order_id

session_start();
header('Content-Type: application/json');

// Only logged-in admins can mark orders paid
if (!isset($_SESSION['admin_logged_in'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

require_once 'connect.php';

$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;

if ($order_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid order ID']);
    exit;
}

$stmt = $pdo->prepare("
    UPDATE orders
    SET payment_status = 'paid',
        payment_method = 'UPI',
        paid_at = NOW()
    WHERE id = ?
");
$stmt->execute([$order_id]);

if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => true, 'message' => 'Order marked as paid']);
} else {
    echo json_encode(['success' => false, 'error' => 'Order not found or already paid']);
}