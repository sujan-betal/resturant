<?php
// php/admin_api.php

session_start();
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

if (!isset($_SESSION['admin_logged_in'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

require_once 'connect.php';

$action = isset($_GET['action']) ? $_GET['action']
        : (isset($_POST['action']) ? $_POST['action'] : '');

switch ($action) {

    // admin.js reads: d.total_orders, d.total_revenue, d.pending, d.today
    case 'stats':
    case 'get_stats':
        $row = $pdo->query("SELECT COUNT(*) as cnt FROM orders")->fetch();
        $total_orders = (int)$row['cnt'];

        $row = $pdo->query("SELECT COALESCE(SUM(total_amount),0) as rev FROM orders")->fetch();
        $total_revenue = number_format($row['rev'], 2);

        $row = $pdo->query("SELECT COUNT(*) as cnt FROM orders WHERE status='pending'")->fetch();
        $pending = (int)$row['cnt'];

        $row = $pdo->query("SELECT COUNT(*) as cnt FROM orders WHERE DATE(created_at)=CURDATE()")->fetch();
        $today = (int)$row['cnt'];

        $row = $pdo->query("SELECT COALESCE(SUM(total_amount),0) as rev FROM orders WHERE payment_status='paid'")->fetch();
        $paid_revenue = number_format($row['rev'], 2);

        // Return FLAT — admin.js uses d.total_orders, d.pending, d.today directly
        echo json_encode([
            'total_orders'  => $total_orders,
            'total_revenue' => $total_revenue,
            'pending'       => $pending,
            'today'         => $today,
            'paid_revenue'  => $paid_revenue
        ]);
        break;

    // admin.js POSTs action=update_status
    case 'update_status':
        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $status   = isset($_POST['status'])   ? trim($_POST['status'])     : '';
        $allowed  = ['pending','preparing','served','cancelled'];

        if (!$order_id || !in_array($status, $allowed)) {
            echo json_encode(['success' => false, 'error' => 'Invalid data']);
            break;
        }
        $pdo->prepare("UPDATE orders SET status=? WHERE id=?")->execute([$status, $order_id]);
        echo json_encode(['success' => true]);
        break;

    // admin.js POSTs action=toggle_item
    case 'toggle_item':
        $item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
        if (!$item_id) { echo json_encode(['success'=>false]); break; }

        $pdo->prepare("UPDATE menu_items SET is_available = NOT is_available WHERE id=?")->execute([$item_id]);
        $stmt = $pdo->prepare("SELECT is_available FROM menu_items WHERE id=?");
        $stmt->execute([$item_id]);
        $r = $stmt->fetch();
        echo json_encode(['success' => true, 'is_available' => (bool)$r['is_available']]);
        break;

    case 'delete_order':
        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        if (!$order_id) { echo json_encode(['success'=>false]); break; }
        $pdo->prepare("DELETE FROM order_items WHERE order_id=?")->execute([$order_id]);
        $pdo->prepare("DELETE FROM orders WHERE id=?")->execute([$order_id]);
        echo json_encode(['success' => true]);
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Unknown action: ' . $action]);
        break;
}