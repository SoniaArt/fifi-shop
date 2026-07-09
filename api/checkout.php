<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../app/Auth.php';
require_once '../app/Basket.php';
require_once '../app/DB_Connection.php';

$auth = new Auth();
if (!$auth->checkAuth()) {
    echo json_encode(['success' => false, 'error' => 'Войдите в аккаунт']);
    exit;
}

$userId = $_SESSION['user_id'];
$basket = new Basket();
$pdo = DB_Connection::getInstance()->getPDO();

$data = json_decode(file_get_contents('php://input'), true);

if (($data['action'] ?? '') !== 'pay') {
    echo json_encode(['success' => false, 'error' => 'Неизвестное действие']);
    exit;
}

try {
    $items = $basket->getByUser($userId);
    if (empty($items)) {
        echo json_encode(['success' => false, 'error' => 'Корзина пуста']);
        exit;
    }

    $pdo->beginTransaction();

    foreach ($items as $item) {
        $stmt = $pdo->prepare("UPDATE product_sizes SET quantity = quantity - ? WHERE id = ?");
        $stmt->execute([$item['quantity'], $item['size_id']]);

        $stmt = $pdo->prepare("
            UPDATE products SET quantity = (
                SELECT COALESCE(SUM(quantity), 0) FROM product_sizes WHERE product_id = ?
            ) WHERE id = ?
        ");
        $stmt->execute([$item['product_id'], $item['product_id']]);
    }

    $pdo->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$userId]);
    $pdo->commit();

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}