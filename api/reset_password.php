<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../app/DB_Connection.php';
require_once '../app/User.php';

$user = new User();
$data = json_decode(file_get_contents('php://input'), true);

$action = $data['action'] ?? '';

if ($action === 'request') {
    $login = trim($data['login'] ?? '');
    if (empty($login)) {
        echo json_encode(['success' => false, 'error' => 'Введите email или телефон']);
        exit;
    }
    echo json_encode($user->requestPasswordReset($login));
    exit;
}

if ($action === 'reset') {
    $token = trim($data['token'] ?? '');
    $password = trim($data['password'] ?? '');
    $confirm = trim($data['confirm'] ?? '');
    
    if (empty($token)) {
        echo json_encode(['success' => false, 'error' => 'Токен не найден']);
        exit;
    }
    
    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'error' => 'Пароль должен быть не менее 6 символов']);
        exit;
    }
    
    if ($password !== $confirm) {
        echo json_encode(['success' => false, 'error' => 'Пароли не совпадают']);
        exit;
    }
    
    echo json_encode($user->resetPassword($token, $password));
    exit;
}

echo json_encode(['success' => false, 'error' => 'Неизвестное действие']);