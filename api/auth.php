<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../app/Auth.php';

$auth = new Auth();
$action = $_GET['action'] ?? '';

if ($action === 'checkAdmin') {
    echo json_encode([
        'isAdmin' => $auth->isAdmin()
    ]);
    exit;
}

echo json_encode(['error' => 'Неизвестное действие']);