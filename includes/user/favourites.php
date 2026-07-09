<div class="d-flex flex-column gap-4">
    <h3 class="text-center">
        Избранное
    </h3>
    <div id="favouritesList">
        <?php if (!$is_logged_in): ?>
        <p class="text-center text-secondary">
            Сначала войдите в аккаунт
        </p>

        <?php else: ?>
        <div class="d-flex flex-column gap-4">
            <h3 class="text-center">
                Избранное
            </h3>
            <div id="favouritesList">
                <p class="text-center text-secondary">
                    Избранное пусто
                </p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>