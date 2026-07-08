<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../app/Auth.php';
require_once '../app/User.php';

$auth = new Auth();

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success'=>false,
        'error'=>'Необходимо войти в аккаунт'
    ]);
    exit;
}

$data=json_decode(file_get_contents('php://input'), true);

$currentPassword=$data['currentPassword'] ?? '';
$newPassword=$data['newPassword'] ?? '';
$confirmPassword=$data['confirmPassword'] ?? '';

if(empty($currentPassword) || empty($newPassword) || empty($confirmPassword)){
    echo json_encode([
        'success'=>false,
        'error'=>'Заполните все поля'
    ]);
    exit;
}

if($newPassword !== $confirmPassword){
    echo json_encode([
        'success'=>false,
        'error'=>'Пароли не совпадают'
    ]);
    exit;
}

if(strlen($newPassword)<6){
    echo json_encode([
        'success'=>false,
        'error'=>'Пароль минимум 6 символов'
    ]);
    exit;
}

$user=new User();

$result=$user->changePassword(
    $_SESSION['user_id'],
    $currentPassword,
    $newPassword
);

echo json_encode($result);