<?php
require_once '../app/User.php';

$user = new User();
$token = $_GET['token'] ?? '';
$userData = $user->getUserByResetToken($token);

if (!$userData) {
    die("Ссылка недействительна или истекла.");
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Смена пароля</title>

    <link href="/FIFI/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/FIFI/css/style.css" rel="stylesheet">
</head>

<body class="d-flex justify-content-center align-items-center vh-100" style="background: #FDFDFE;">

<div class="forms active" style="max-width:450px; position: relative; display: flex; padding: 30px;">
    <input type="hidden" id="tokenData" data-token="<?= htmlspecialchars($token) ?>">
    <h3 class="text-center mb-4" style="font-size: 1.3rem; font-weight: 700;">
        Новый пароль
    </h3>

    <form id="newPasswordForm" class="d-flex flex-column gap-3" style="flex: 1;">
        <div class="form-group">
            <input type="password" name="password" class="form-control" placeholder="Новый пароль" required>
        </div>
        <div class="form-group">
            <input type="password" name="confirm" class="form-control" placeholder="Повторите пароль" required>
        </div>
        <button class="btn btn-dark" style="margin-top: auto;">
            СМЕНИТЬ
        </button>
    </form>
    
    <div id="result" class="mt-3"></div>
</div>

<script src="/FIFI/js/reset_password.js"></script>
</body>
</html>