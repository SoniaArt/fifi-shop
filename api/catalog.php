<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../app/DB_Connection.php';
require_once '../app/Product.php';

$pdo = DB_Connection::getInstance()->getPDO();
$productModel = new Product($pdo);

$sort = $_GET['sort'] ?? 'newest';
$search = trim($_GET['search'] ?? '');
$categories = $_GET['category'] ?? [];
$colors = $_GET['color'] ?? [];
$sizes = $_GET['size'] ?? [];

if (!is_array($categories)) {
    $categories = [$categories];
}

if (!is_array($colors)) {
    $colors = [$colors];
}

if (!is_array($sizes)) {
    $sizes = [$sizes];
}

$products = $productModel->getAll(
    $sort,
    $categories,
    $colors,
    $sizes, 
    $search
);

echo json_encode([
    'success' => true,
    'sortLabel' => $productModel->getSortLabel($sort),
    'products' => $products
]);