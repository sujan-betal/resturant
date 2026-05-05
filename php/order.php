<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $name   = mysqli_real_escape_string($con, $data['name']);
    $phone  = mysqli_real_escape_string($con, $data['phone']);
    $table  = (int)($data['table'] ?? 0);
    $items  = $data['items']; // [{id, qty, price}]
    $total  = 0;

    foreach ($items as $item) {
        $total += $item['price'] * $item['qty'];
    }

    // Insert order
    $sql = "INSERT INTO orders (customer_name, customer_phone, table_number, total_amount) 
            VALUES ('$name', '$phone', $table, $total)";
    mysqli_query($con, $sql);
    $order_id = mysqli_insert_id($con);

    // Insert order items
    foreach ($items as $item) {
        $item_id = (int)$item['id'];
        $qty     = (int)$item['qty'];
        $price   = (float)$item['price'];
        mysqli_query($con, "INSERT INTO order_items (order_id, menu_item_id, quantity, price) 
                            VALUES ($order_id, $item_id, $qty, $price)");
    }

    echo json_encode([
        'success' => true,
        'order_id' => $order_id,
        'total' => $total,
        'message' => "Order #$order_id placed successfully!"
    ]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get all orders for admin
    $result = mysqli_query($con, "SELECT o.*, 
        GROUP_CONCAT(CONCAT(m.name, ' x', oi.quantity) SEPARATOR ', ') as items_list
        FROM orders o 
        LEFT JOIN order_items oi ON o.id = oi.order_id 
        LEFT JOIN menu_items m ON oi.menu_item_id = m.id
        GROUP BY o.id ORDER BY o.created_at DESC");
    $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($orders);
}
?>
