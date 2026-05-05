<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
include('connect.php');

$action = $_GET['action'] ?? 'menu';

if ($action === 'menu') {
    $category_id = $_GET['category'] ?? null;

    if ($category_id) {
        $query = "SELECT m.*, c.name as category_name, c.icon 
                  FROM menu_items m 
                  JOIN categories c ON m.category_id = c.id 
                  WHERE m.category_id = ? AND m.is_available = 1";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'i', $category_id);
    } else {
        $query = "SELECT m.*, c.name as category_name, c.icon 
                  FROM menu_items m 
                  JOIN categories c ON m.category_id = c.id 
                  WHERE m.is_available = 1 ORDER BY m.category_id";
        $stmt = mysqli_prepare($con, $query);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($items);

} elseif ($action === 'categories') {
    $result = mysqli_query($con, "SELECT * FROM categories");
    $cats = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($cats);
}
?>
