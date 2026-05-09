<?php
/**
 * php/cancel_order.php
 * POST: order_id, reason, is_paid, amount
 *
 * 1. Sets orders.status = 'cancelled'
 * 2. Saves cancellation reason + refund flag into order_cancellations table
 * 3. Returns JSON { success, refund_required, amount }
 *
 * The customer's history.php polls check_status.php which returns
 *   status = 'cancelled'  AND  cancel_reason  so the customer sees it.
 */

header('Content-Type: application/json');
require_once 'connect.php';   // provides $pdo or $conn

$order_id = intval($_POST['order_id'] ?? 0);
$reason   = trim($_POST['reason']   ?? 'No reason provided');
$is_paid  = intval($_POST['is_paid'] ?? 0);
$amount   = floatval($_POST['amount'] ?? 0);

if (!$order_id) {
    echo json_encode(['success'=>false,'error'=>'Invalid order ID']);
    exit;
}

try {
    // ── Support both PDO and mysqli ──
    if (isset($pdo)) {

        // 1. Update order status
        $stmt = $pdo->prepare("UPDATE orders SET status='cancelled' WHERE id=?");
        $stmt->execute([$order_id]);

        // 2. Ensure cancellation log table exists
        $pdo->exec("CREATE TABLE IF NOT EXISTS order_cancellations (
            id            INT AUTO_INCREMENT PRIMARY KEY,
            order_id      INT NOT NULL,
            reason        TEXT,
            refund_needed TINYINT(1) DEFAULT 0,
            refund_amount DECIMAL(10,2) DEFAULT 0.00,
            cancelled_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX (order_id)
        )");

        // 3. Insert cancellation record
        $stmt2 = $pdo->prepare(
            "INSERT INTO order_cancellations (order_id,reason,refund_needed,refund_amount)
             VALUES (?,?,?,?)"
        );
        $stmt2->execute([$order_id, $reason, $is_paid, $is_paid ? $amount : 0]);

    } else {
        // mysqli fallback
        $conn->query("UPDATE orders SET status='cancelled' WHERE id=" . $order_id);
        $conn->query("CREATE TABLE IF NOT EXISTS order_cancellations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            reason TEXT,
            refund_needed TINYINT(1) DEFAULT 0,
            refund_amount DECIMAL(10,2) DEFAULT 0.00,
            cancelled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX (order_id)
        )");
        $r  = $conn->real_escape_string($reason);
        $ra = $is_paid ? $amount : 0;
        $conn->query("INSERT INTO order_cancellations (order_id,reason,refund_needed,refund_amount)
                      VALUES ($order_id,'$r',$is_paid,$ra)");
    }

    echo json_encode([
        'success'         => true,
        'refund_required' => (bool)$is_paid,
        'amount'          => $is_paid ? $amount : 0
    ]);

} catch (Exception $e) {
    echo json_encode(['success'=>false,'error'=>$e->getMessage()]);
}