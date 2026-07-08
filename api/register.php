<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../app/Auth.php';
require_once '../app/User.php';

new Auth();

$data = json_decode(file_get_contents('php://input'), true);

$email = trim($data['email'] ?? '');
$phone = trim($data['phone'] ?? '');
$password = $data['password'] ?? '';
$firstName = trim($data['firstName'] ?? '');
$lastName = trim($data['lastName'] ?? '');
$middleName = trim($data['middleName'] ?? '');

if (empty($email) || empty($phone) || empty($password) || empty($firstName) || empty($lastName)) {
    echo json_encode(['success' => false, 'error' => 'Все обязательные поля должны быть заполнены']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'error' => 'Некорректный email']);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'error' => 'Пароль должен быть не менее 6 символов']);
    exit;
}

$user = new User();
$result = $user->register($email, $phone, $password, $firstName, $lastName, $middleName);

if ($result['success']) {
    $user->login($email, $password);
}

echo json_encode($result);