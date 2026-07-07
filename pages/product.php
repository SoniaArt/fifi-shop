<?php
$id = $_GET['id'] ?? 0;
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIFI — Товар</title>

    <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/product.css" rel="stylesheet">

</head>
<body>
    <?php include '../includes/menu.php';?>
    <?php include '../includes/header.php';?>

    <main class="container-fluid p-0">
        <div class="container">
            <div id="productPage" data-id="<?= $id ?>"></div>
        </div>
    </main>

    <?php include '../includes/footer.php';?>
    <script src="../js/product.js"></script>
</body>
</html>