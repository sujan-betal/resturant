<?php
// php/menu.php

header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

require_once 'connect.php';

$action     = isset($_GET['action'])   ? $_GET['action']          : 'menu';
$categoryId = isset($_GET['category']) ? intval($_GET['category']) : null;

switch ($action) {

    // app.js calls: php/menu.php?action=categories
    case 'categories':
        $cats = $pdo->query("SELECT id, name, icon FROM categories ORDER BY id")->fetchAll();
        echo json_encode($cats);
        break;

    // app.js calls: php/menu.php?action=menu  OR  php/menu.php?action=menu&category=2
    // admin.js calls: php/menu.php?action=menu
    // Both expect a DIRECT FLAT ARRAY of items
    case 'menu':
    default:
        if ($categoryId) {
            $stmt = $pdo->prepare("
                SELECT
                    m.id, m.name, m.description,
                    m.price, m.image_url, m.is_available,
                    c.name AS category_name,
                    c.icon AS icon          -- app.js uses item.icon
                FROM menu_items m
                JOIN categories c ON c.id = m.category_id
                WHERE m.category_id = ? AND m.is_available = 1
                ORDER BY m.name
            ");
            $stmt->execute([$categoryId]);
        } else {
            $stmt = $pdo->query("
                SELECT
                    m.id, m.name, m.description,
                    m.price, m.image_url, m.is_available,
                    c.name AS category_name,
                    c.icon AS icon          -- app.js uses item.icon
                FROM menu_items m
                JOIN categories c ON c.id = m.category_id
                WHERE m.is_available = 1
                ORDER BY c.id, m.name
            ");
        }
        $items = $stmt->fetchAll();
        echo json_encode($items);
        break;

    // admin toggle
    case 'toggle':
        session_start();
        if (!isset($_SESSION['admin_logged_in'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            exit;
        }
        $item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
        if (!$item_id) { echo json_encode(['success'=>false]); exit; }

        $pdo->prepare("UPDATE menu_items SET is_available = NOT is_available WHERE id=?")->execute([$item_id]);
        $stmt = $pdo->prepare("SELECT is_available FROM menu_items WHERE id=?");
        $stmt->execute([$item_id]);
        $r = $stmt->fetch();
        echo json_encode(['success' => true, 'is_available' => (bool)$r['is_available']]);
        break;
}