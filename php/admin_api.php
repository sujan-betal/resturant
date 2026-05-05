<?php
header('Content-Type: application/json');
include('../php/connect.php');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'update_status') {
    $order_id = (int)$_POST['order_id'];
    $status   = mysqli_real_escape_string($con, $_POST['status']);
    mysqli_query($con, "UPDATE orders SET status='$status' WHERE id=$order_id");
    echo json_encode(['success' => true]);

} elseif ($action === 'toggle_item') {
    $item_id = (int)$_POST['item_id'];
    mysqli_query($con, "UPDATE menu_items SET is_available = !is_available WHERE id=$item_id");
    echo json_encode(['success' => true]);

} elseif ($action === 'stats') {
    $total_orders = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM orders"))[0];
    $total_revenue = mysqli_fetch_row(mysqli_query($con, "SELECT SUM(total_amount) FROM orders WHERE status != 'cancelled'"))[0];
    $pending = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM orders WHERE status='pending'"))[0];
    $today = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM orders WHERE DATE(created_at)=CURDATE()"))[0];
    echo json_encode(compact('total_orders','total_revenue','pending','today'));
}
?>
