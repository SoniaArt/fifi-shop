<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../app/Auth.php';
require_once '../app/Basket.php';

try {
    $data = json_decode(
        file_get_contents('php://input'),
        true
    );

    $action = $data['action'] ?? '';

    $auth = new Auth();

    if (!$auth->checkAuth()) {
        echo json_encode([
            'success' => false,
            'auth' => false,
            'error' => 'Сначала войдите в аккаунт'
        ]);
        exit;
    }

    $userId = $_SESSION['user_id'];
    $basket = new Basket();

    if ($action === 'get') {
        $items = $basket->getByUser($userId);
        echo json_encode([
            'success' => true,
            'items' => $items
        ]);
        exit;
    }

    if ($action === 'add') {
        $result = $basket->add(
            $userId,
            $data['product_id'],
            $data['size_id']
        );
        echo json_encode($result);
        exit;
    }

    if ($action === 'remove') {
        $result = $basket->remove(
            $userId,
            $data['product_id'],
            $data['size_id']
        );
        echo json_encode($result);
        exit;
    }

    echo json_encode([
        'success' => false,
        'error' => 'Неизвестное действие'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}