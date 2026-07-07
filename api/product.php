<?php

header('Content-Type: application/json; charset=utf-8');
require_once '../app/DB_Connection.php';
require_once '../app/Product.php';

$pdo = DB_Connection::getInstance()->getPDO();
$productModel = new Product($pdo);

$id = $_GET['id'] ?? 0;
$product = $productModel->getById($id);

if (!$product) {
    echo json_encode([
        'success' => false
    ]);
    exit;
}

$product['sizes'] = $productModel->getSizes($id);
$product['images'] = $productModel->getImages($id);

foreach ($productModel->getSizes($id) as $size) {
    $product['sizes'][] = $size['size'];
}

echo json_encode([
    'success' => true,
    'product' => $product
]);