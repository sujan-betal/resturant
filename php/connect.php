<?php
// php/connect.php

// Suppress PHP warnings from appearing in JSON responses
error_reporting(0);
ini_set('display_errors', 0);

$host = '127.0.0.1';
$db   = 'restaurant_db';
$user = 'root';
$pass = 'root';        // ← your MySQL password (leave blank if none)
$port = 3306;

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
    echo json_encode([
        'success' => false,
        'error'   => 'Database connection failed: ' . $e->getMessage()
    ]);
    exit;
}