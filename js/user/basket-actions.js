let initialized = false;

export function initBasketButtons() {
    console.log('basket buttons initialized');

    if (initialized) return;
    initialized = true;

    document.addEventListener('click', async function(e) {
        const increaseBtn = e.target.closest('.basket-increase');
        if (increaseBtn) {
            e.preventDefault();
            e.stopPropagation();
            
            const productId = increaseBtn.dataset.id;
            const sizeId = increaseBtn.dataset.size;

            await updateBasket(productId, sizeId, 'add');

            const { initBasket } = await import('./basket.js');
            await initBasket();
            
            return;
        }

        const decreaseBtn = e.target.closest('.basket-decrease');
        if (decreaseBtn) {
            e.preventDefault();
            e.stopPropagation();
            
            const productId = decreaseBtn.dataset.id;
            const sizeId = decreaseBtn.dataset.size;

            await updateBasket(productId, sizeId, 'remove');

            const { initBasket } = await import('./basket.js');
            await initBasket();
            
            return;
        }
    });
}

async function updateBasket(productId, sizeId, action) {
    const response = await fetch('/FIFI/api/basket.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: action,
            product_id: productId,
            size_id: sizeId
        })
    });

    const data = await response.json();

    if (!data.success) {
        if (data.auth === false) {
            alert('Сначала войдите в аккаунт');
        } else {
            alert('Ошибка при обновлении корзины');
        }
    }
    
    return data;
}

export function updateBasketControls() {
    document.querySelectorAll('.basket-increase').forEach(btn => {
        const quantity = parseInt(btn.closest('.basket-controls').querySelector('.basket-quantity').textContent);
        const maxQuantity = parseInt(btn.dataset.maxQuantity || 0);
        
        if (maxQuantity > 0 && quantity >= maxQuantity) {
            btn.disabled = true;
        } else {
            btn.disabled = false;
        }
    });
}