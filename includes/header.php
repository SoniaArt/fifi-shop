<?php
require_once dirname(__DIR__) . '/app/Auth.php';
$auth = new Auth();
$is_logged_in = $auth->checkAuth();
$user = $is_logged_in ? $auth->getCurrentUser() : null;
?>

<header class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-4">
            <button class="navbar-toggler" type="button" id="menuOpen">
                <img src="/FIFI/images/menu_icon.png" alt="Меню" class="menu-icon">
            </button>
            <a href="/FIFI/index.php" class="logo">FIFI</a>
        </div>
        <div class="d-flex align-items-center gap-4">
            <div class="search-box d-flex align-items-center">
                <input type="text" id="searchInput" class="search-input" placeholder="Поиск...">
                <button id="searchBtn" class="search-btn">
                    <img src="/FIFI/images/search_icon.png" alt="Поиск" class="header-icon">
                </button>
            </div>
            
            <a href="#" id="favouritesOpen">
                <img src="/FIFI/images/favourites_icon.png" alt="Избранное" class="header-icon">
            </a>
            <a href="#" id="basketOpen">
                <img src="/FIFI/images/basket_icon.png" alt="Корзина" class="header-icon">
            </a>
            <a href="#" id="authFormOpen">
                <img src="/FIFI/images/profile_icon.png" alt="Профиль" class="header-icon">
            </a>
        </div>
    </div>
</header>

<div class="forms" id="authForm">
    <div class="d-flex justify-content-end">
        <button class="form-close" id="authFormClose">
            <img src="/FIFI/images/cross_icon.png" alt="Крестик" class="close-icon">
        </button>
    </div>

    <?php if (!$is_logged_in): ?>
        <div class="d-flex flex-row justify-content-center gap-4">
            <p class="auth-tab active" data-tab="login">Авторизация</p>
            <p class="auth-tab" data-tab="register">Регистрация</p>
        </div>
        
        <?php include __DIR__ . '/auth/login.php'; ?>
        <?php include __DIR__ . '/auth/register.php'; ?>
        <?php include __DIR__ . '/auth/reset.php'; ?>
        
    <?php else: ?>
        <?php include __DIR__ . '/auth/profile.php'; ?>
    <?php endif; ?>
</div>

<div class="forms" id="favouritesForm">
    <div class="d-flex justify-content-end">
        <button class="form-close" id="favouritesClose">
            <img src="/FIFI/images/cross_icon.png" alt="Закрыть" class="close-icon">
        </button>
    </div>
    <?php include __DIR__ . '/user/favourites.php'; ?>
</div>


<div class="forms" id="basketForm">
    <div class="d-flex justify-content-end">
        <button class="form-close" id="basketClose">
            <img src="/FIFI/images/cross_icon.png" alt="Закрыть" class="close-icon">
        </button>
    </div>
    <?php include __DIR__ . '/user/basket.php'; ?>
</div>

<div class="forms" id="checkoutForm">
    <div class="d-flex justify-content-end">
        <button class="form-close" id="checkoutClose">
            <img src="/FIFI/images/cross_icon.png" alt="Закрыть" class="close-icon">
        </button>
    </div>
    <?php include __DIR__ . '/user/checkout.php'; ?>
</div>