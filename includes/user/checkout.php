<div class="checkout-form d-flex flex-column h-100">
    <h4 class="text-center mb-4">Оплата заказа</h4>
    
    <form id="checkoutForm" class="d-flex flex-column gap-3 flex-grow-1">
        <div class="form-group">
            <label for="cardNumber" class="form-label small text-secondary">Номер карты</label>
            <input type="text" id="cardNumber" name="cardNumber" class="form-control" 
                   placeholder="0000 0000 0000 0000" maxlength="19" required>
            <div class="invalid-feedback" id="cardNumberError">Введите корректный номер карты (16 цифр)</div>
        </div>

        <div class="row g-3">
            <div class="col-6">
                <label for="cardExpiry" class="form-label small text-secondary">Срок действия</label>
                <input type="text" id="cardExpiry" name="cardExpiry" class="form-control" 
                       placeholder="ММ/ГГ" maxlength="5" required>
                <div class="invalid-feedback" id="cardExpiryError">Введите корректную дату (ММ/ГГ)</div>
            </div>
            <div class="col-6">
                <label for="cardCvv" class="form-label small text-secondary">CVV2/CVC2</label>
                <input type="password" id="cardCvv" name="cardCvv" class="form-control" placeholder="000" maxlength="3" required>
                <div class="invalid-feedback" id="cardCvvError">Введите 3-значный код</div>
            </div>
        </div>

        <div class="form-group">
            <label for="cardHolder" class="form-label small text-secondary">Имя владельца карты</label>
            <input type="text" id="cardHolder" name="cardHolder" class="form-control" 
                   placeholder="IVAN IVANOV" required>
            <div class="invalid-feedback" id="cardHolderError">Введите имя владельца латиницей</div>
        </div>

        <div class="mt-auto pt-3 border-top">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fw-bold">Сумма к оплате:</span>
                <span class="fw-bold fs-5" id="checkoutTotal">0 ₽</span>
            </div>
            <button type="submit" class="btn btn-dark w-100 py-2 fw-semibold" id="payNowBtn">
                ОПЛАТИТЬ
            </button>
            <button type="button" class="btn btn-outline-secondary w-100 mt-2" id="checkoutCancel">
                Отмена
            </button>
        </div>
    </form>
</div>