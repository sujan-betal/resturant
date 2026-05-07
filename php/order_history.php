<?php

header('Content-Type: application/json');

include 'connect.php';

$phone = $_GET['phone'] ?? '';

if (!$phone) {

    echo json_encode([
        "success" => false,
        "orders" => [],
        "message" => "Phone number required"
    ]);

    exit;
}

$phone = mysqli_real_escape_string($conn, $phone);

$sql = "
SELECT 
    id,
    customer_name,
    customer_phone,
    table_number,
    total_amount,
    status,
    payment_status,
    payment_method,
    paid_at,
    created_at
FROM orders
WHERE customer_phone = '$phone'
ORDER BY id DESC
";

$result = mysqli_query($conn, $sql);

if (!$result) {

    echo json_encode([
        "success" => false,
        "orders" => [],
        "message" => mysqli_error($conn)
    ]);

    exit;
}

$orders = [];

while ($row = mysqli_fetch_assoc($result)) {

    // Get order items
    $orderId = $row['id'];

    $itemSql = "
SELECT 
    name,
    price,
    quantity
FROM order_items
WHERE order_id = '$orderId'
";

    $itemResult = mysqli_query($conn, $itemSql);

    $items = [];

    while ($item = mysqli_fetch_assoc($itemResult)) {
        $items[] = $item;
    }

    $row['items'] = $items;

    $orders[] = $row;
}

echo json_encode([
    "success" => true,
    "orders" => $orders
]);

?>