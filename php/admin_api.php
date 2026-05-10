<?php
// php/admin_api.php

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in'])) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'error'   => 'Unauthorized'
    ]);
    exit;
}

require_once 'connect.php';

$action = isset($_GET['action'])
    ? $_GET['action']
    : (isset($_POST['action']) ? $_POST['action'] : '');

switch ($action) {

    // =====================================================
    // STATS
    // =====================================================
    case 'stats':
    case 'get_stats':

        // Total Orders
        $row = $pdo->query("
            SELECT COUNT(*) as cnt
            FROM orders
        ")->fetch();

        $total_orders = (int)$row['cnt'];

        // Total Revenue
        $row = $pdo->query("
            SELECT COALESCE(SUM(total_amount),0) as rev
            FROM orders
        ")->fetch();

        $total_revenue = number_format($row['rev'], 2);

        // Pending Orders
        $row = $pdo->query("
            SELECT COUNT(*) as cnt
            FROM orders
            WHERE status='pending'
        ")->fetch();

        $pending = (int)$row['cnt'];

        // Today's Orders
        $row = $pdo->query("
            SELECT COUNT(*) as cnt
            FROM orders
            WHERE DATE(created_at)=CURDATE()
        ")->fetch();

        $today = (int)$row['cnt'];

        // Paid Revenue
        $paid_revenue = "0.00";

        echo json_encode([
            'success'       => true,
            'total_orders'  => $total_orders,
            'total_revenue' => $total_revenue,
            'pending'       => $pending,
            'today'         => $today,
            'paid_revenue'  => $paid_revenue
        ]);

        break;

    // =====================================================
    // GET ORDERS
    // =====================================================
   case 'orders':

    $stmt = $pdo->query("
        SELECT
            o.id,
            o.customer_name,
            o.customer_phone,
            o.table_number,
            o.total_amount,
            o.status,
            o.created_at,

            GROUP_CONCAT(
                CONCAT(mi.name, ' x', oi.quantity)
                SEPARATOR ', '
            ) AS items_list

        FROM orders o

        LEFT JOIN order_items oi
            ON o.id = oi.order_id

        LEFT JOIN menu_items mi
            ON oi.menu_item_id = mi.id

        GROUP BY o.id

        ORDER BY o.id DESC
    ");

    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'orders'  => $orders
    ]);

    break;

    // =====================================================
    // UPDATE ORDER STATUS
    // =====================================================
    case 'update_status':

        $order_id = isset($_POST['order_id'])
            ? intval($_POST['order_id'])
            : 0;

        $status = isset($_POST['status'])
            ? trim($_POST['status'])
            : '';

        $allowed = ['pending', 'preparing', 'served', 'cancelled'];

        if (!$order_id || !in_array($status, $allowed)) {
            echo json_encode([
                'success' => false,
                'error'   => 'Invalid data'
            ]);
            break;
        }

        $stmt = $pdo->prepare("
            UPDATE orders
            SET status = ?
            WHERE id = ?
        ");

        $stmt->execute([$status, $order_id]);

        echo json_encode([
            'success' => true
        ]);

        break;

    // =====================================================
    // TOGGLE MENU ITEM
    // =====================================================
    case 'toggle_item':

        $item_id = isset($_POST['item_id'])
            ? intval($_POST['item_id'])
            : 0;

        if (!$item_id) {
            echo json_encode([
                'success' => false
            ]);
            break;
        }

        $stmt = $pdo->prepare("
            UPDATE menu_items
            SET is_available = NOT is_available
            WHERE id = ?
        ");

        $stmt->execute([$item_id]);

        $stmt = $pdo->prepare("
            SELECT is_available
            FROM menu_items
            WHERE id = ?
        ");

        $stmt->execute([$item_id]);

        $r = $stmt->fetch();

        echo json_encode([
            'success'      => true,
            'is_available' => (bool)$r['is_available']
        ]);

        break;

    // =====================================================
    // DELETE ORDER
    // =====================================================
    case 'delete_order':

        $order_id = isset($_POST['order_id'])
            ? intval($_POST['order_id'])
            : 0;

        if (!$order_id) {
            echo json_encode([
                'success' => false
            ]);
            break;
        }

        // Delete child records first
        $stmt = $pdo->prepare("
            DELETE FROM order_items
            WHERE order_id = ?
        ");

        $stmt->execute([$order_id]);

        // Delete order
        $stmt = $pdo->prepare("
            DELETE FROM orders
            WHERE id = ?
        ");

        $stmt->execute([$order_id]);

        echo json_encode([
            'success' => true
        ]);

        break;

    // =====================================================
    // UNKNOWN ACTION
    // =====================================================
    default:

        echo json_encode([
            'success' => false,
            'error'   => 'Unknown action: ' . $action
        ]);

        break;
}
?>