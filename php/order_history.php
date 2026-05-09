<?php
/**
 * php/order_history.php
 * GET: phone (customer phone number)
 * Returns all orders for that phone + cancel reason + refund info
 */

header('Content-Type: application/json');
require_once 'connect.php';

$phone = trim($_GET['phone'] ?? '');
if (!$phone) {
    echo json_encode(['success'=>false,'error'=>'Phone required']);
    exit;
}

try {
    if (isset($pdo)) {

        // Fetch orders for this phone
        $stmt = $pdo->prepare(
            "SELECT o.id, o.customer_name, o.table_number, o.total_amount,
                    o.status, o.created_at,
                    COALESCE(p.payment_status,'unpaid') AS payment_status,
                    p.paid_at,
                    c.reason       AS cancel_reason,
                    c.refund_needed,
                    c.refund_amount
             FROM orders o
             LEFT JOIN payments            p ON p.order_id = o.id
             LEFT JOIN order_cancellations c ON c.order_id = o.id
             WHERE o.customer_phone = ?
             ORDER BY o.created_at DESC
             LIMIT 50"
        );
        $stmt->execute([$phone]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($orders as &$order) {
            // Fetch items for each order
            $iStmt = $pdo->prepare(
                "SELECT m.name, oi.quantity, oi.price
                 FROM order_items oi
                 JOIN menu_items m ON m.id = oi.menu_item_id
                 WHERE oi.order_id = ?"
            );
            $iStmt->execute([$order['id']]);
            $order['items'] = $iStmt->fetchAll(PDO::FETCH_ASSOC);
            $order['refund_amount'] = floatval($order['refund_amount'] ?? 0);
        }
        unset($order);

    } else {
        // mysqli fallback
        $ph = $conn->real_escape_string($phone);
        $res = $conn->query(
            "SELECT o.id, o.customer_name, o.table_number, o.total_amount,
                    o.status, o.created_at,
                    COALESCE(p.payment_status,'unpaid') AS payment_status,
                    p.paid_at,
                    c.reason AS cancel_reason,
                    c.refund_needed,
                    c.refund_amount
             FROM orders o
             LEFT JOIN payments            p ON p.order_id = o.id
             LEFT JOIN order_cancellations c ON c.order_id = o.id
             WHERE o.customer_phone = '$ph'
             ORDER BY o.created_at DESC
             LIMIT 50"
        );
        $orders = [];
        while ($row = $res->fetch_assoc()) {
            $oid   = intval($row['id']);
            $iRes  = $conn->query("SELECT m.name, oi.quantity, oi.price
                                   FROM order_items oi
                                   JOIN menu_items m ON m.id = oi.menu_item_id
                                   WHERE oi.order_id = $oid");
            $items = [];
            while ($ir = $iRes->fetch_assoc()) $items[] = $ir;
            $row['items']         = $items;
            $row['refund_amount'] = floatval($row['refund_amount'] ?? 0);
            $orders[]             = $row;
        }
    }

    echo json_encode(['success' => true, 'orders' => $orders]);

} catch (Exception $e) {
    echo json_encode(['success'=>false,'error'=>$e->getMessage()]);
}