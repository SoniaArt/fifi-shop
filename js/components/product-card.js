import { isFavourite } from '../user/favourites.js';

export function createProductCard(product, options = {}) {
    const {
        favourite = false,
        basket = false,
        quantity = 1,
        maxQuantity = 0,
        size = '',
        sizeId = ''
    } = options;

    const id = product.product_id ?? product.id;
    const inStock = product.quantity > 0;
    const stockClass = inStock ? '' : 'product-out-of-stock';

    return `
    <div class="side-product-card ${stockClass}" data-id="${id}" data-size="${size}" data-size-id="${sizeId}">
        <a href="/FIFI/pages/product.php?id=${id}"
           class="side-product-image-link">
            <div class="side-product-image-wrapper">
                <img src="/FIFI/${product.image}" class="side-product-image" alt="${product.name}">
                ${!inStock ? '<span class="out-of-stock-badge">Нет в наличии</span>' : ''}
            </div>
        </a>

        <div class="side-product-info">
            <a href="/FIFI/pages/product.php?id=${id}"
               class="side-product-name">
                ${product.name}
            </a>

            <div class="side-product-price">
                ${Number(product.price).toLocaleString('ru-RU')} ₽
            </div>
            
            ${size ? `<div class="side-product-size">Размер: ${size}</div>` : ''}
        </div>

        ${
            favourite ? `
            <button class="favourite-btn ${isFavourite(id) ? 'active' : ''} side-product-action" data-id="${id}">
                <img src="/FIFI/images/favourites_icon.png" class="favourite-icon" alt="Избранное">
            </button> 
            ` : ''
        }

        ${
            basket ? `
            <div class="d-flex flex-column basket-controls">
            <div class="d-flex flex-row">
                <button class="basket-decrease" data-id="${id}" data-size-id="${sizeId}">−</button>
                <span class="basket-quantity">${quantity}</span>
                <button class="basket-increase" data-id="${id}" data-size-id="${sizeId}" data-max-quantity="${maxQuantity}" ${quantity >= maxQuantity && maxQuantity > 0 ? 'disabled' : ''}>
                    +
                </button>
            </div>
            <div>
                ${maxQuantity > 0 ? `<div class="available-info w-100 mt-1 text-secondary small">Осталось в наличии: ${maxQuantity - quantity}</div>` : ''}
            </div>
            </div>
            ` : ''
        }
    </div>
    `;
}