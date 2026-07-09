import { createProductCard } from '../components/product-card.js';

let basketItems = [];

export async function loadBasketItems() {
    try {
        const response = await fetch('/FIFI/api/basket.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'get'
            })
        });

        const data = await response.json();

        if (!data.success) {
            basketItems = [];
            return [];
        }

        basketItems = data.items;
        return basketItems;
    } catch (error) {
        console.error('Error loading basket:', error);
        basketItems = [];
        return [];
    }
}

export function getBasketTotal() {
    let total = 0;
    basketItems.forEach(item => {
        total += item.price * item.quantity;
    });
    return total;
}

export function hasOutOfStock() {
    return basketItems.some(item => item.product_quantity === 0 || (item.available_quantity !== undefined && item.available_quantity < 0));
}

export async function initBasket() {
    const basketList = document.getElementById('basketList');
    const basketTotal = document.getElementById('basketTotal');
    const checkoutBtn = document.getElementById('checkoutBtn');
    const outOfStockWarning = document.getElementById('outOfStockWarning');

    if (!basketList) return;

    await loadBasketItems();

    if (basketList.querySelector('.text-secondary')?.textContent.includes('войдите')) {
        return;
    }

    if (basketItems.length === 0) {
        basketList.innerHTML = `
            <p class="text-center text-secondary py-4">
                Корзина пуста
            </p>
        `;
        if (basketTotal) basketTotal.textContent = '0 ₽';
        if (checkoutBtn) checkoutBtn.disabled = true;
        if (outOfStockWarning) outOfStockWarning.style.display = 'none';
        return;
    }

    basketList.innerHTML = '';

    basketItems.forEach(item => {
        const maxQuantity = item.max_quantity || 0;
        const sizeName = item.size_name || 'One Size';
        
        basketList.innerHTML += createProductCard(
            {
                ...item,
                product_id: item.product_id,
                quantity: item.product_quantity
            },
            {
                basket: true,
                quantity: item.quantity,
                maxQuantity: maxQuantity,
                size: sizeName,
                sizeId: item.size_id 
            }
        );
    });

    if (basketTotal) {
        const total = getBasketTotal();
        basketTotal.textContent = `${total.toLocaleString('ru-RU')} ₽`;
    }

    const hasOutOfStockItems = hasOutOfStock();
    if (checkoutBtn) {
        checkoutBtn.disabled = hasOutOfStockItems;
    }
    if (outOfStockWarning) {
        outOfStockWarning.style.display = hasOutOfStockItems ? 'block' : 'none';
    }

    document.removeEventListener('click', handleBasketClick);
    document.addEventListener('click', handleBasketClick);
}

async function handleBasketClick(e) {
    const increaseBtn = e.target.closest('.basket-increase');
    if (increaseBtn) {
        e.preventDefault();
        e.stopPropagation();
        
        const productId = increaseBtn.dataset.id;
        const sizeId = increaseBtn.dataset.sizeId;
        
        const result = await updateBasket(productId, sizeId, 'add');
        if (result.success) {
            await initBasket();
        }
        return;
    }

    const decreaseBtn = e.target.closest('.basket-decrease');
    if (decreaseBtn) {
        e.preventDefault();
        e.stopPropagation();
        
        const productId = decreaseBtn.dataset.id;
        const sizeId = decreaseBtn.dataset.sizeId; 
        
        const result = await updateBasket(productId, sizeId, 'remove');
        if (result.success) {
            await initBasket();
        }
        return;
    }
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
                size_id: sizeId 
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