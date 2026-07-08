<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../app/Auth.php';

$auth = new Auth();
$auth->logout();

echo json_encode(['success' => true]);