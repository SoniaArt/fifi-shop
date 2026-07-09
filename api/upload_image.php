<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../app/Auth.php';
$auth = new Auth();

if (!$auth->checkAuth() || !$auth->isAdmin()) {
    echo json_encode(['success' => false, 'error' => 'Доступ запрещен']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['image'])) {
    echo json_encode(['success' => false, 'error' => 'Нет файла']);
    exit;
}

$file = $_FILES['image'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    $errors = [
        UPLOAD_ERR_INI_SIZE => 'Файл превышает максимальный размер',
        UPLOAD_ERR_FORM_SIZE => 'Файл превышает максимальный размер формы',
        UPLOAD_ERR_PARTIAL => 'Файл загружен не полностью',
        UPLOAD_ERR_NO_FILE => 'Файл не выбран',
        UPLOAD_ERR_NO_TMP_DIR => 'Временная папка не найдена',
        UPLOAD_ERR_CANT_WRITE => 'Ошибка записи файла',
        UPLOAD_ERR_EXTENSION => 'Расширение PHP остановило загрузку'
    ];
    echo json_encode([
        'success' => false,
        'error' => $errors[$file['error']] ?? 'Неизвестная ошибка загрузки'
    ]);
    exit;
}

$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
if (!in_array($file['type'], $allowedTypes)) {
    echo json_encode(['success' => false, 'error' => 'Разрешены только изображения (JPEG, PNG, GIF, WEBP)']);
    exit;
}

$uploadDir = __DIR__ . '/../catalog/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$filename = uniqid() . '.' . $ext;
$destination = $uploadDir . $filename;

if (move_uploaded_file($file['tmp_name'], $destination)) {
    echo json_encode([
        'success' => true,
        'path' => 'catalog/' . $filename,
        'filename' => $filename
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Не удалось сохранить файл'
    ]);
}