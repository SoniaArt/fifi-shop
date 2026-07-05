<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../app/DB_Connection.php';
require_once '../app/Product.php';

$pdo = DB_Connection::getInstance()->getPDO();
$productModel = new Product($pdo);

$sort = $_GET['sort'] ?? 'newest';

$products = $productModel->getAll($sort);

echo json_encode([
    'success' => true,
    'sortLabel' => $productModel->getSortLabel($sort),
    'products' => $products
]);