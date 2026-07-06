<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../app/DB_Connection.php';
require_once '../app/Product.php';

$pdo = DB_Connection::getInstance()->getPDO();
$productModel = new Product($pdo);

echo json_encode([
    'success' => true,
    'categories' => $productModel->getCategories(),
    'colors' => $productModel->getColors(),
    'sizes' => $productModel->getAllSizes()
]);