<div class="d-flex flex-column gap-4 h-100">
    <h3 class="text-center">
        Корзина
    </h3>
    
    <div id="basketList" class="flex-grow-1">
        <?php if (!$is_logged_in): ?>
            <p class="text-center text-secondary">
                Сначала войдите в аккаунт
            </p>
        <?php else: ?>
            <p class="text-center text-secondary">
                Корзина пуста
            </p>
        <?php endif; ?>
    </div>

    <?php if ($is_logged_in): ?>
    <div class="basket-footer mt-auto">
        <div class="basket-total">
            <span>Итого:</span>
            <span id="basketTotal">0 ₽</span>
        </div>
        <button id="checkoutBtn" class="btn btn-dark w-100" disabled>
            ОПЛАТИТЬ
        </button>
        <small id="outOfStockWarning" class="text-danger" style="display: none;">
            В корзине есть товары, которых нет в наличии
        </small>
    </div>
    <?php endif; ?>
</div>