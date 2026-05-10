<?php
/**
 * php/cancel_order.php
 * POST: order_id, reason, is_paid, amount
 * Returns: { success, refund_required, amount }
 */

header('Content-Type: application/json');
require_once 'connect.php';

$order_id = intval($_POST['order_id'] ?? 0);
$reason   = trim($_POST['reason']    ?? 'No reason provided');
$is_paid  = intval($_POST['is_paid'] ?? 0);
$amount   = floatval($_POST['amount'] ?? 0);

if (!$order_id) {
    echo json_encode(['success' => false, 'error' => 'Invalid order ID']);
    exit;
}

try {

    if (isset($pdo)) {

        // 1. Update order status
        $stmt = $pdo->prepare("UPDATE orders SET status='cancelled' WHERE id=?");
        $stmt->execute([$order_id]);

        // 2. Insert cancellation record
        $stmt2 = $pdo->prepare(
            "INSERT INTO order_cancellations (order_id, reason, refund_needed, refund_amount)
             VALUES (?, ?, ?, ?)"
        );
        $stmt2->execute([$order_id, $reason, $is_paid, $is_paid ? $amount : 0]);

    } else {

        // MySQLi fallback

        // 1. Update order status
        $conn->query("UPDATE orders SET status='cancelled' WHERE id=" . $order_id);

        // After cancelling order, credit the paid amount to customer
if ($is_paid && $total > 0) {
    $phone = $order['customer_phone'];

    if (isset($pdo)) {
        $pdo->prepare(
            "INSERT INTO customer_credits (phone, balance)
             VALUES (?, ?)
             ON DUPLICATE KEY UPDATE balance = balance + ?"
        )->execute([$phone, $total, $total]);
    } else {
        $p = $conn->real_escape_string($phone);
        $conn->query(
            "INSERT INTO customer_credits (phone, balance)
             VALUES ('$p', $total)
             ON DUPLICATE KEY UPDATE balance = balance + $total"
        );
    }
}

        // 2. Insert cancellation record
        $r  = $conn->real_escape_string($reason);
        $ra = $is_paid ? floatval($amount) : 0;
        $conn->query(
            "INSERT INTO order_cancellations (order_id, reason, refund_needed, refund_amount)
             VALUES ($order_id, '$r', $is_paid, $ra)"
        );

        if ($conn->error) {
            throw new Exception($conn->error);
        }
    }

    echo json_encode([
        'success'         => true,
        'refund_required' => (bool) $is_paid,
        'amount'          => $is_paid ? $amount : 0
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}