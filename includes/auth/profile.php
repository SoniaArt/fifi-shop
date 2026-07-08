<div class="auth-panel active" id="profileView">
    <div class="d-flex flex-column justify-content-between w-100 gap-4">
        <div class="d-flex flex-column gap-2">
            <h3 class="text-center">Профиль</h3>
            <p class="text-center"><?= htmlspecialchars($_SESSION['user_name']) ?></p>
            <div>
                <small class="text-secondary">Email</small>
                <p class="mt-2"><?= htmlspecialchars($_SESSION['user_email']) ?></p>
            </div>
            <div>
                <small class="text-secondary">Номер телефона</small>
                <p class="mt-2"><?= htmlspecialchars($_SESSION['user_phone']) ?></p>
            </div>
        </div>
        <div class="d-flex flex-column gap-3">
            <button class="btn btn-dark" id="editProfile">
                РЕДАКТИРОВАТЬ
            </button>

            <button class="btn btn-outline-dark" id="logoutBtn">
                ВЫЙТИ
            </button>
        </div>
    </div>
</div>

<div class="auth-panel" id="profileEditPanel">
    <form id="profileEditForm" class="d-flex flex-column justify-content-between gap-3">
        <h3 class="text-center">Редактирование</h3>
        <div class="form-group">
            <input type="text" name="lastName" class="form-control" placeholder="Фамилия" value="<?= htmlspecialchars($user['last_name']) ?>">
        </div>
        <div class="form-group">
            <input type="text" name="firstName" class="form-control" placeholder="Имя" value="<?= htmlspecialchars($user['first_name']) ?>">
        </div>
        <div class="form-group">
            <input type="text" name="middleName" class="form-control" placeholder="Отчество" value="<?= htmlspecialchars($user['middle_name']) ?>">
        </div>
        <div class="form-group">
            <input type="email" name="email" class="form-control" placeholder="E-mail" value="<?= htmlspecialchars($user['email']) ?>">
        </div>
        <div class="form-group">
            <input type="tel" name="phone" class="form-control" placeholder="Номер телефона" value="<?= htmlspecialchars($user['phone']) ?>">
        </div>
        <div class="mt-auto d-flex flex-column gap-3">
            <button type="button" class="btn btn-outline-dark" id="changePassword">
                СМЕНИТЬ ПАРОЛЬ
            </button>
            <button type="submit" class="btn btn-dark">
                СОХРАНИТЬ
            </button>
            <button type="button" class="btn btn-outline-dark" id="cancelEdit">
                ОТМЕНИТЬ
            </button>
        </div>
    </form>
</div>

<div class="auth-panel" id="changePasswordPanel">
    <form id="changePasswordForm" class="d-flex flex-column gap-3">
        <h3 class="text-center">
            Смена пароля
        </h3>
        <div class="form-group">
            <input  type="password" name="currentPassword" class="form-control" placeholder="Текущий пароль" required>
        </div>

        <div class="form-group">
            <input type="password" name="newPassword" class="form-control" placeholder="Новый пароль" required>
        </div>

        <div class="form-group">
            <input type="password" name="confirmPassword" class="form-control" placeholder="Повторите новый пароль" required>
        </div>

        <div class="mt-auto d-flex flex-column gap-3">
        <button class="btn btn-dark">
            СМЕНИТЬ
        </button>

        <button type="button" class="btn btn-outline-dark" id="cancelChangePassword">
            ОТМЕНИТЬ
        </button>
        </div>
    </form>
</div>