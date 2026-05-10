<?php
header('Content-Type: application/json');
require_once 'connect.php';

$order_id = intval($_POST['order_id'] ?? 0);
if (!$order_id) {
    echo json_encode(['success' => false, 'error' => 'Invalid order ID']);
    exit;
}

try {
    if (isset($pdo)) {
        // Check if row already exists
        $check = $pdo->prepare("SELECT id FROM payments WHERE order_id = ?");
        $check->execute([$order_id]);

        if ($check->fetch()) {
            // Update existing
            $stmt = $pdo->prepare(
                "UPDATE payments SET payment_status='paid', paid_at=NOW() WHERE order_id=?"
            );
            $stmt->execute([$order_id]);
        } else {
            // Insert new
            $stmt = $pdo->prepare(
                "INSERT INTO payments (order_id, payment_status, paid_at) VALUES (?, 'paid', NOW())"
            );
            $stmt->execute([$order_id]);
        }

    } else {
        // MySQLi fallback
        $oid = intval($order_id);
        $check = $conn->query("SELECT id FROM payments WHERE order_id = $oid");

        if ($check && $check->num_rows > 0) {
            $conn->query("UPDATE payments SET payment_status='paid', paid_at=NOW() WHERE order_id=$oid");
        } else {
            $conn->query("INSERT INTO payments (order_id, payment_status, paid_at) VALUES ($oid, 'paid', NOW())");
        }

        if ($conn->error) throw new Exception($conn->error);
    }

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}