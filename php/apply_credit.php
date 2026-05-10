<?php
/**
 * Called after order is placed.
 * Deducts credit from customer_credits and records on order.
 * POST: order_id, phone, credit_used, credit_source
 */
header('Content-Type: application/json');
require_once 'connect.php';

$order_id      = intval($_POST['order_id']      ?? 0);
$phone         = trim($_POST['phone']           ?? '');
$credit_used   = floatval($_POST['credit_used'] ?? 0);
$credit_source = intval($_POST['credit_source'] ?? 0);

if (!$order_id || !$phone || $credit_used <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    exit;
}

try {
    if (isset($pdo)) {
        // Deduct from credit balance
        $pdo->prepare(
            "UPDATE customer_credits 
             SET balance = balance - ? 
             WHERE phone = ? AND balance >= ?"
        )->execute([$credit_used, $phone, $credit_used]);

        // Record on order
        $pdo->prepare(
            "UPDATE orders 
             SET credit_used = ?, credit_source = ? 
             WHERE id = ?"
        )->execute([$credit_used, $credit_source ?: null, $order_id]);

    } else {
        $p   = $conn->real_escape_string($phone);
        $oid = intval($order_id);
        $cs  = $credit_source ?: 'NULL';

        $conn->query(
            "UPDATE customer_credits 
             SET balance = balance - $credit_used 
             WHERE phone = '$p' AND balance >= $credit_used"
        );
        $conn->query(
            "UPDATE orders 
             SET credit_used = $credit_used, credit_source = $cs 
             WHERE id = $oid"
        );
    }

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}