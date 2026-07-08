<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../app/Auth.php';
require_once '../app/User.php';

new Auth();

$data = json_decode(file_get_contents('php://input'), true);

$login = trim($data['login'] ?? '');
$password = $data['password'] ?? '';
$remember = $data['remember'] ?? false;

if (empty($login) || empty($password)) {
    echo json_encode(['success' => false, 'error' => 'Заполните все поля']);
    exit;
}

$user = new User();
$result = $user->login($login, $password, $remember);

echo json_encode($result);