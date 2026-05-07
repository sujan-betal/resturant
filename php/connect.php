<?php
// php/connect.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = '127.0.0.1';
$db   = 'restaurant_db';
$user = 'root';
$pass = 'root'; // <-- Put your MySQL password here (leave blank if you don't have one)
$port = 3306;

// ==========================================
// 1. PDO CONNECTION (For files using $pdo)
// ==========================================
try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'PDO connection failed: ' . $e->getMessage()]);
    exit;
}

// ==========================================
// 2. MYSQLI CONNECTION (For menu.php, admin_api.php, etc.)
// ==========================================
$con = mysqli_connect($host, $user, $pass, $db, $port);

if (!$con) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'MySQLi connection failed: ' . mysqli_connect_error()]);
    exit;
}

// Create an alias just in case some files use $conn instead of $con
$conn = $con;

mysqli_set_charset($con, "utf8mb4");

?>