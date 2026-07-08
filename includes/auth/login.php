<div class="auth-panel active" id="loginPanel">
    <form class="d-flex flex-column justify-content-between gap-4" id="loginForm">
        <div class="form-group">
            <input type="text" name="login" class="form-control" placeholder="E-mail или телефон" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" class="form-control" placeholder="Пароль" required>
        </div>

         <div class="d-flex align-items-center gap-2">
            <input type="checkbox" id="remember" name="remember">
            <label for="remember">
                Запомнить меня
            </label>
        </div>

        <a href="#" class="form-link" id="showResetLink">Забыли пароль?</a>
        
        <button type="submit" class="btn btn-dark">
            ВОЙТИ
        </button>
    </form>
</div>