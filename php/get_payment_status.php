<?php
// php/get_payment_status.php

header('Content-Type: application/json');

require 'connect.php';   // already in php/ folder ✅

$id  = intval($_GET['order_id'] ?? 0);
$row = $conn->query("SELECT payment FROM orders WHERE id = $id")->fetch_assoc();

echo json_encode([
    'payment' => $row['payment'] ?? 'pending'
]);

$conn->close();
?>