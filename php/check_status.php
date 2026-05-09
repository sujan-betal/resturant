<?php
/**
 * php/check_status.php
 * GET: order_id
 * Returns: { success, status, payment, cancel_reason, refund_needed, refund_amount }
 */

header('Content-Type: application/json');
require_once 'connect.php';

$order_id = intval($_GET['order_id'] ?? 0);
if (!$order_id) {
    echo json_encode(['success'=>false,'error'=>'No order_id']);
    exit;
}

try {
    if (isset($pdo)) {

        $stmt = $pdo->prepare(
            "SELECT o.status, o.total_amount,
                    COALESCE(p.payment_status,'unpaid') AS payment,
                    p.paid_at,
                    c.reason          AS cancel_reason,
                    c.refund_needed,
                    c.refund_amount
             FROM orders o
             LEFT JOIN payments            p ON p.order_id = o.id
             LEFT JOIN order_cancellations c ON c.order_id = o.id
             WHERE o.id = ?
             LIMIT 1"
        );
        $stmt->execute([$order_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

    } else {
        $res = $conn->query(
            "SELECT o.status, o.total_amount,
                    COALESCE(p.payment_status,'unpaid') AS payment,
                    p.paid_at,
                    c.reason          AS cancel_reason,
                    c.refund_needed,
                    c.refund_amount
             FROM orders o
             LEFT JOIN payments            p ON p.order_id = o.id
             LEFT JOIN order_cancellations c ON c.order_id = o.id
             WHERE o.id = $order_id
             LIMIT 1"
        );
        $row = $res ? $res->fetch_assoc() : null;
    }

    if (!$row) {
        echo json_encode(['success'=>false,'error'=>'Order not found']);
        exit;
    }

    echo json_encode([
        'success'       => true,
        'status'        => $row['status'],
        'payment'       => $row['payment'],
        'paid_at'       => $row['paid_at'] ?? null,
        'cancel_reason' => $row['cancel_reason'] ?? null,
        'refund_needed' => (bool)($row['refund_needed'] ?? false),
        'refund_amount' => floatval($row['refund_amount'] ?? 0),
        'total_amount'  => floatval($row['total_amount'] ?? 0),
    ]);

} catch (Exception $e) {
    echo json_encode(['success'=>false,'error'=>$e->getMessage()]);
}