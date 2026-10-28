<?php
// php/order.php

session_start();

header('Content-Type: application/json');

if (!empty($_SESSION['admin_demo'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Read-only demo']);
        exit;
    }
    echo json_encode([
        [ 'id' => 2401, 'customer_name' => 'Aarav Mehta', 'customer_phone' => '000-000-0101', 'table_number' => 4, 'total_amount' => '860.00', 'status' => 'preparing', 'created_at' => date('Y-m-d H:i:s', time() - 900), 'items_list' => 'Paneer Tikka x2, Masala Chai x2' ],
        [ 'id' => 2402, 'customer_name' => 'Mira Shah', 'customer_phone' => '000-000-0102', 'table_number' => 7, 'total_amount' => '1240.00', 'status' => 'pending', 'created_at' => date('Y-m-d H:i:s', time() - 2100), 'items_list' => 'Thali Special x2, Gulab Jamun x2' ],
        [ 'id' => 2403, 'customer_name' => 'Kabir Rao', 'customer_phone' => '000-000-0103', 'table_number' => 2, 'total_amount' => '540.00', 'status' => 'served', 'created_at' => date('Y-m-d H:i:s', time() - 4200), 'items_list' => 'Butter Naan x2, Dal Makhani x1' ],
        [ 'id' => 2404, 'customer_name' => 'Anaya Das', 'customer_phone' => '000-000-0104', 'table_number' => 5, 'total_amount' => '720.00', 'status' => 'pending', 'created_at' => date('Y-m-d H:i:s', time() - 6000), 'items_list' => 'Veg Biryani x2, Lassi x2' ],
    ]);
    exit;
}

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
