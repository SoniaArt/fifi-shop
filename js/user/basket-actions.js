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
            const sizeId = increaseBtn.dataset.sizeId; // Исправлено: sizeId вместо size
            
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
            const sizeId = decreaseBtn.dataset.sizeId; // Исправлено: sizeId вместо size
            
            await updateBasket(productId, sizeId, 'remove');

            const { initBasket } = await import('./basket.js');
            await initBasket();
            
            return;
        }
    });
}

async function updateBasket(productId, sizeId, action) {
    try {
        const response = await fetch('/FIFI/api/basket.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: action,
                product_id: productId,
                size_id: sizeId // Теперь передаётся число, а не строка
            })
        });

        const data = await response.json();

        if (!data.success) {
            if (data.auth === false) {
                alert('Сначала войдите в аккаунт');
            } else if (data.error) {
                alert(data.error);
            } else {
                alert('Ошибка при обновлении корзины');
            }
        }
        
        return data;
    } catch (error) {
        console.error('Error updating basket:', error);
        alert('Ошибка при обновлении корзины');
        return { success: false };
    }
}

export function updateBasketControls() {
    document.querySelectorAll('.basket-increase').forEach(btn => {
        const controls = btn.closest('.basket-controls');
        if (!controls) return;
        
        const quantitySpan = controls.querySelector('.basket-quantity');
        if (!quantitySpan) return;
        
        const quantity = parseInt(quantitySpan.textContent);
        const maxQuantity = parseInt(btn.dataset.maxQuantity || 0);
        
        if (maxQuantity > 0 && quantity >= maxQuantity) {
            btn.disabled = true;
        } else {
            btn.disabled = false;
        }
    });
}