<?php
// php/order.php

session_start();

header('Content-Type: application/json');

require_once 'connect.php';

// ======================================================
// PLACE ORDER
// ======================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input = json_decode(file_get_contents('php://input'), true);

    $name  = isset($input['name'])  ? trim($input['name']) : '';
    $phone = isset($input['phone']) ? trim($input['phone']) : '';
    $table = isset($input['table']) ? intval($input['table']) : 0;
    $items = isset($input['items']) ? $input['items'] : [];

    if (!$name || empty($items)) {

        echo json_encode([
            'success' => false,
            'error'   => 'Missing name or items'
        ]);

        exit;
    }

    // ======================================================
    // CALCULATE TOTAL
    // ======================================================
    $total = 0;

    foreach ($items as $item) {

        $qty   = isset($item['qty']) ? intval($item['qty']) : 1;
        $price = isset($item['price']) ? floatval($item['price']) : 0;

        $total += ($qty * $price);
    }

    // ======================================================
    // INSERT ORDER
    // ======================================================
    $stmt = $pdo->prepare("
        INSERT INTO orders
        (
            customer_name,
            customer_phone,
            table_number,
            total_amount,
            status
        )
        VALUES (?, ?, ?, ?, 'pending')
    ");

    $stmt->execute([
        $name,
        $phone,
        $table,
        $total
    ]);

    $order_id = $pdo->lastInsertId();


    // At the top of order.php, receive credit fields
$credit_used   = floatval($_POST['credit_used']   ?? 0);
$credit_source = intval($_POST['credit_source']   ?? 0);

// In your INSERT query, add these two columns:
// credit_used = $credit_used, credit_source = $credit_source
// Example (add to your existing INSERT):
$sql = "INSERT INTO orders 
        (customer_name, customer_phone, table_number, total_amount, 
         credit_used, credit_source, status)
        VALUES (?, ?, ?, ?, ?, ?, 'pending')";

// total_amount should be the ORIGINAL total, not reduced
// credit_used tracks how much credit was applied

    // ======================================================
    // INSERT ORDER ITEMS
    // ======================================================
    $itemStmt = $pdo->prepare("
        INSERT INTO order_items
        (
            order_id,
            menu_item_id,
            quantity,
            price
        )
        VALUES (?, ?, ?, ?)
    ");

    foreach ($items as $item) {

        $menu_item_id = isset($item['id']) ? intval($item['id']) : 0;
        $qty          = isset($item['qty']) ? intval($item['qty']) : 1;
        $price        = isset($item['price']) ? floatval($item['price']) : 0;

        $itemStmt->execute([
            $order_id,
            $menu_item_id,
            $qty,
            $price
        ]);
    }

    echo json_encode([
        'success'  => true,
        'order_id' => $order_id,
        'total'    => $total
    ]);

    exit;
}

// ======================================================
// GET ORDERS
// ======================================================
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
            CONCAT(m.name, ' x', oi.quantity)
            SEPARATOR ', '
        ) AS items_list

    FROM orders o

    LEFT JOIN order_items oi
        ON o.id = oi.order_id

    LEFT JOIN menu_items m
        ON oi.menu_item_id = m.id

    GROUP BY o.id

    ORDER BY o.created_at DESC
");

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($orders);
?>