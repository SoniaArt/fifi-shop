<?php
require_once '../app/DB_Connection.php';
require_once '../app/Product.php';

$pdo = DB_Connection::getInstance()->getPDO();
$productModel = new Product($pdo);

$sort = $_GET['sort'] ?? 'newest';

$products = $productModel->getAll($sort);
$sortLabel = $productModel->getSortLabel($sort);
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

    <div class="forms" id="filterForm">
        <div class="filters-header d-flex justify-content-between align-items-center gap-4">
            <h3>Фильтры</h3>
            <button class="form-close" id="filterClose">
                <img src="/FIFI/images/cross_icon.png" alt="Крестик" class="close-icon">
            </button>
        </div>

        <div class="filter-body d-flex flex-column gap-3">
            <h4>Категория</h4>
            <div id="filterCategories" class="filter-group d-flex flex-column justify-content-beetwen gap-3">
            </div>
                
            <h4>Цвет</h4>
            <div id="filterColors" class="filter-group d-flex flex-column justify-content-beetwen gap-3">
            </div>

            <h4>Размер</h4>
            <div id="filterSizes" class="filter-group d-flex flex-column justify-content-beetwen gap-3">
            </div>
        </div>

        <div class="filter-footer d-flex flex-column justify-content-center gap-3">
                <button class="btn btn-outline-dark" id="resetFilters">Сбросить</button>
                <button class="btn btn-dark" id="applyFilters">Применить</button>
        </div>
    </div>


    <main class="page">
        <div class="catalog-header">
            <div class="d-flex justify-content-between">

                <div class="dropdown">
                    <button class="filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span id="sortLabel"><?= $sortLabel ?></span>
                        <svg width="28" height="9" viewBox="0 0 32 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <line y1="-0.5" x2="22.6274" y2="-0.5" transform="matrix(0.707107 0.707107 -0.748552 0.663077 0 0.662109)" stroke="black"/>
                            <line y1="-0.5" x2="22.6274" y2="-0.5" transform="matrix(-0.707107 0.707107 0.748552 0.663077 32 0.662109)" stroke="black"/>
                        </svg>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="sortDropdown">                    
                        <li><a class="dropdown-item sort-option <?= $sort === 'newest' ? 'active' : '' ?>" data-sort="newest" href="#">По новизне</a></li>
                        <li><a class="dropdown-item sort-option <?= $sort === 'price_asc' ? 'active' : '' ?>" data-sort="price_asc" href="#">По возрастанию цены</a></li>
                        <li><a class="dropdown-item sort-option <?= $sort === 'price_desc' ? 'active' : '' ?>" data-sort="price_desc" href="#">По убыванию цены</a></li>                      
                    </ul>
                </div>

                <div>
                    <button class="filter-btn" id="filterOpen">Фильтры</button>
                </div>
            </div>
        </div>
       
        <div class="container">
            <div class="row g-5" id="productGrid">
            </div>
        </div>

    </main>

    <?php include '../includes/footer.php';?>
    <script src="/FIFI/js/Filter.js"></script>
    <script src="/FIFI/js/catalog.js"></script>
</body>
</html>