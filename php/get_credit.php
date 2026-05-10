<?php
header('Content-Type: application/json');
require_once 'connect.php';

$phone = trim($_GET['phone'] ?? '');
if (!$phone) { echo json_encode(['balance' => 0]); exit; }

if (isset($pdo)) {
    $s = $pdo->prepare("SELECT balance FROM customer_credits WHERE phone = ?");
    $s->execute([$phone]);
    $row = $s->fetch(PDO::FETCH_ASSOC);
} else {
    $p   = $conn->real_escape_string($phone);
    $res = $conn->query("SELECT balance FROM customer_credits WHERE phone = '$p'");
    $row = $res ? $res->fetch_assoc() : null;
}

echo json_encode([
    'balance' => $row ? floatval($row['balance']) : 0
]);