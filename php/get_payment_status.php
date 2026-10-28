<?php
session_start();
header('Content-Type: application/json');
if (!empty($_SESSION['admin_demo'])) {
    $order_id = intval($_GET['order_id'] ?? 0);
    echo json_encode(['payment' => $order_id === 2401 || $order_id === 2403 ? 'paid' : 'unpaid']);
    exit;
}
require 'connect.php';

$order_id = intval($_GET['order_id'] ?? 0);
if (!$order_id) { echo json_encode(['payment' => 'unpaid']); exit; }

$stmt = $conn->prepare("SELECT is_paid FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if ($row && $row['is_paid'] == 1) {
    echo json_encode(['payment' => 'paid']);
} else {
    echo json_encode(['payment' => 'unpaid']);
}
