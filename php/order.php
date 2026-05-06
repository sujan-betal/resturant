<?php
// php/order.php

session_start();
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

require_once 'connect.php';

// POST = customer placing a new order
// app.js sends JSON body (Content-Type: application/json)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Read JSON body — NOT $_POST
    $input = json_decode(file_get_contents('php://input'), true);

    $name  = isset($input['name'])  ? trim($input['name'])       : '';
    $phone = isset($input['phone']) ? trim($input['phone'])       : '';
    $table = isset($input['table']) ? intval($input['table'])     : 0;
    $items = isset($input['items']) ? $input['items']             : [];

    if (!$name || empty($items)) {
        echo json_encode(['success' => false, 'error' => 'Missing name or items']);
        exit;
    }

    // Calculate total from items
    $total = array_sum(array_map(fn($i) => floatval($i['price']) * intval($i['qty']), $items));

    $stmt = $pdo->prepare("
        INSERT INTO orders (customer_name, customer_phone, table_number, total_amount, status, payment_status)
        VALUES (?, ?, ?, ?, 'pending', 'unpaid')
    ");
    $stmt->execute([$name, $phone, $table, $total]);
    $order_id = $pdo->lastInsertId();

    // app.js sends items as [{id, qty, price}]  ← note: qty not quantity
    $itemStmt = $pdo->prepare("
        INSERT INTO order_items (order_id, menu_item_id, quantity, price)
        VALUES (?, ?, ?, ?)
    ");
    foreach ($items as $item) {
        $itemStmt->execute([
            $order_id,
            intval($item['id']),
            intval($item['qty']),    // ← app.js uses 'qty'
            floatval($item['price'])
        ]);
    }

    // app.js uses data.order_id and data.total
    echo json_encode([
        'success'  => true,
        'order_id' => $order_id,
        'total'    => $total
    ]);
    exit;
}

// GET = admin fetches all orders
// admin.js does: const orders = await res.json(); orders.map(...)
// Return DIRECT ARRAY
$orders = $pdo->query("
    SELECT
        o.id, o.customer_name, o.customer_phone,
        o.table_number, o.total_amount,
        o.status, o.payment_status, o.paid_at, o.created_at
    FROM orders o
    ORDER BY o.created_at DESC
")->fetchAll();

foreach ($orders as &$order) {
    $stmt = $pdo->prepare("
        SELECT m.name, oi.quantity
        FROM order_items oi
        JOIN menu_items m ON m.id = oi.menu_item_id
        WHERE oi.order_id = ?
    ");
    $stmt->execute([$order['id']]);
    $rows = $stmt->fetchAll();
    $order['items_list'] = implode(', ', array_map(
        fn($r) => $r['name'] . ' x' . $r['quantity'],
        $rows
    ));
}

echo json_encode($orders);