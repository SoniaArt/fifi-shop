<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../app/Auth.php';
require_once '../app/Favourite.php';

$data = json_decode(
    file_get_contents('php://input'),
    true
);

$action = $data['action'] ?? '';

$auth = new Auth();

if (!$auth->checkAuth()) {
    if($action === 'exists' || $action === 'allIds'){
        echo json_encode([
            'success'=>true,
            'exists'=>false,
            'ids'=>[]
        ]);
        exit;
    }

    echo json_encode([
        'success'=>false,
        'auth'=>false,
        'error'=>'Сначала войдите в аккаунт'
    ]);
    exit;
}

$userId = $_SESSION['user_id'];
$favourite = new Favourite();

if ($action === 'get') {
    echo json_encode([
        'success'=>true,
        'items'=>$favourite->getByUser($userId)
    ]);
    exit;
}

if ($action === 'add') {
    echo json_encode(
        $favourite->add(
            $userId,
            $data['product_id']
        )
    );
    exit;
}

if ($action === 'remove') {
    echo json_encode(
        $favourite->remove(
            $userId,
            $data['product_id']
        )
    );
    exit;
}

if($action === 'exists'){
    echo json_encode([
        'success'=>true,
        'exists'=>$favourite->exists(
            $userId,
            $data['product_id']
        )
    ]);
    exit;
}

if($action === 'allIds'){
    echo json_encode([
        'success'=>true,
        'ids'=>$favourite->getIds($userId)
    ]);
    exit;
}

echo json_encode([
    'success'=>false,
    'error'=>'Неизвестное действие'
]);