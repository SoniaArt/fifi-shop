<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../app/Auth.php';
require_once '../app/User.php';

$auth = new Auth();

if (!$auth->checkAuth()) {
    echo json_encode([
        'success' => false,
        'error' => 'Не авторизован'
    ]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$firstName = trim($data['firstName'] ?? '');
$lastName = trim($data['lastName'] ?? '');
$middleName = trim($data['middleName'] ?? '');
$email = trim($data['email'] ?? '');
$phone = trim($data['phone'] ?? '');

if (empty($firstName) || empty($lastName) || empty($email) || empty($phone)) {
    echo json_encode([
        'success' => false,
        'error' => 'Все обязательные поля должны быть заполнены'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'error' => 'Некорректный email'
    ]);
    exit;
}

$user = new User();

$result = $user->updateProfile(
    $_SESSION['user_id'],
    $firstName,
    $lastName,
    $middleName,
    $email,
    $phone
);

echo json_encode($result);