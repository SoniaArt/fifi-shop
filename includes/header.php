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
            
            <a href="/FIFI/pages/favourites.php">
                <img src="/FIFI/images/favourites_icon.png" alt="Избранное" class="header-icon">
            </a>
            <a href="/FIFI/pages/basket.php">
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

    <div class="d-flex flex-row justify-content-center gap-4">
        <p class="auth-tab active" data-tab="login">Авторизация</p>
        <p class="auth-tab" data-tab="register">Регистрация</p>
    </div>
    
    <div class="auth-panel active" id="loginPanel">
        <form class="d-flex flex-column justify-content-between gap-4">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="E-mail или телефон" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" placeholder="Пароль" required>
            </div>

            <a href="#" class="form-link" id="showResetLink">Забыли пароль?</a>
            
            <button type="submit" class="btn btn-dark ">
                ВОЙТИ
            </button>
        </form>
    </div>

    <div class="auth-panel" id="registerPanel">
        <form class="d-flex flex-column justify-content-between gap-4">
            <div class="form-group">
                <input type="email" class="form-control" placeholder="E-mail" required>
            </div>
            <div class="form-group">
                <input type="tel" class="form-control" placeholder="Номер телефона" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" placeholder="Пароль" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" placeholder="Подтвердите пароль" required>
            </div>
            
            <button type="submit" class="btn btn-dark ">
                ЗАРЕГИСТРИРОВАТЬСЯ
            </button>
        </form>
    </div>

    <div class="auth-panel" id="resetPanel">
        <form class="d-flex flex-column justify-content-between gap-4">
            <h2 class="text-center">Восстановление пароля</h2>

            <div class="form-group">
                <input type="text" class="form-control" placeholder="E-mail или телефон" required>
            </div>
            
            <button type="submit" class="btn btn-dark ">
                ВОССТАНОВИТЬ
            </button>
        </form>
    </div>

</div>  