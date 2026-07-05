<?php
require_once '../app/DB_Connection.php';

$pdo = DB_Connection::getInstance()->getPDO();

$sort = $_GET['sort'] ?? 'newest';

switch ($sort) {
    case 'price_asc':
        $orderBy = 'price ASC';
        $sortLabel = 'По возрастанию цены';
        break;
    case 'price_desc':
        $orderBy = 'price DESC';
        $sortLabel = 'По убыванию цены';
        break;
    case 'newest':
                $orderBy = 'id DESC';
        $sortLabel = 'По новизне';
        break;
    default:
        $orderBy = 'id DESC';
        $sortLabel = 'По новизне';
        break;
}

$stmt = $pdo->query("SELECT * FROM products ORDER BY id");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FIFI — Каталог</title>

    <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/catalog.css" rel="stylesheet">

</head>
<body>
    <?php include '../includes/menu.php';?>
    <?php include '../includes/header.php';?>

    <main class="page">
        <div class="catalog-header">
            <div class="d-flex justify-content-between">

                <div class="dropdown">
                    <button class="filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        По новизне
                        <svg width="28" height="9" viewBox="0 0 32 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <line y1="-0.5" x2="22.6274" y2="-0.5" transform="matrix(0.707107 0.707107 -0.748552 0.663077 0 0.662109)" stroke="black"/>
                            <line y1="-0.5" x2="22.6274" y2="-0.5" transform="matrix(-0.707107 0.707107 0.748552 0.663077 32 0.662109)" stroke="black"/>
                        </svg>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                        <li><a class="dropdown-item active" href="#">По новизне</a></li>
                        <li><a class="dropdown-item" href="#">По возрастанию цены</a></li>
                        <li><a class="dropdown-item" href="#">По убыванию цены</a></li>
                    </ul>
                </div>

                <div>
                    <button class="filter-btn" id="filterOpen">Фильтры</button>
                </div>
            </div>
        </div>
       
        <div class="container">
            <div class="row g-5">
                <?php foreach ($products as $product): ?>
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="product-card">
                            <img src="../<?php echo $product['image'] ?>" class="product-image img-fluid">
                            <div class="text-center">
                                <p><?php echo htmlspecialchars($product['name'])?></p>
                                <p><?php echo number_format($product['price'], 0, ',', ' ')?> ₽</p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </main>

    <?php include '../includes/footer.php';?>
</body>
</html>