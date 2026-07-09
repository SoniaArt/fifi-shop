import {loadFavouriteIds, isFavourite} from './user/favourites.js';
import {initFavouriteButtons} from './user/favourite-actions.js';

const ALL_SIZES = ['XS', 'S', 'M', 'L', 'XL', 'One Size'];
let currentProduct = null;
let currentSizeId = null;

async function loadProduct() {
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');
    const response = await fetch('../api/product.php?id=' + id);
    const data = await response.json();

    if (!data.success) {
        return;
    }

    await loadFavouriteIds();
    currentProduct = data.product;
    renderProduct(data.product);
    await updateCartButtonState();
}

function renderProduct(product) {
    let sizesHtml = '';
    ALL_SIZES.forEach(size => {
        const sizeInfo = product.sizes.find(s => s.size === size);
        const available = sizeInfo && sizeInfo.quantity > 0;
        sizesHtml += `
            <button class="btn ${available ? 'btn-outline-dark' : 'btn-outline-secondary'} size-btn" 
                    ${available ? '' : 'disabled'} 
                    data-size-id="${sizeInfo ? sizeInfo.id : ''}"
                    data-size-name="${size}">
                ${size}
            </button>
        `;
    });

    let images = [product.image];

    if (product.images && product.images.length) {
        images.push(...product.images);
    }

    let carouselItems = '';

    images.forEach((image, index) => {
        carouselItems += `
            <div class="carousel-item ${index === 0 ? 'active' : ''}">
                <img src="../${image}" class="d-block w-100 product-image">
            </div>
        `;
    });

    let imagesHtml = `
        <div id="productCarousel" class="carousel slide" data-bs-touch="true">
            <div class="carousel-inner">
                ${carouselItems}
            </div>
            ${
                images.length > 1
                ? `
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                `
                : ''
            }
        </div>
    `;

    document.getElementById('productPage').innerHTML = `
        <div class="row g-0 align-items-start">
            <div class="col-lg-6 product-gallery">
                ${imagesHtml}
            </div>

            <div class="col-lg-6 d-flex flex-column pt-4 ps-lg-5">
                <h2>${product.name}</h2>
                <h3 class="my-4">
                    ${Number(product.price).toLocaleString('ru-RU')} ₽
                </h3>
                <p>${product.description}</p>
                <h5 class="mt-5 mb-3">Размер</h5>
                <div class="d-flex flex-wrap gap-2 mb-4">
                    ${sizesHtml}
                </div>
                <p>
                    <strong>Цвет:</strong>
                    ${product.color}
                </p>

                <div class="d-flex gap-3 mt-5 align-items-center">
                    <div id="cartControls" class="flex-grow-1">
                        <button class="btn btn-dark w-100" id="addToCart">
                            ДОБАВИТЬ В КОРЗИНУ
                        </button>
                    </div>

                    <button class="btn favourite-btn ${isFavourite(product.id) ? 'active' : ''}" data-id="${product.id}">
                        <img src="../images/favourites_icon.png" class="header-icon" alt="Избранное">
                    </button>
                </div>
                <div id="cartStatus" class="mt-2 text-secondary small"></div>
            </div>
        </div>
    `;
    
    document.querySelectorAll('.size-btn:not(:disabled)').forEach(btn => {
        btn.onclick = () => {
            const isSelected = btn.classList.contains('btn-dark');

            document.querySelectorAll('.size-btn:not(:disabled)')
                .forEach(b => {
                    b.classList.remove('btn-dark');
                    b.classList.add('btn-outline-dark');
                });

            if (!isSelected) {
                btn.classList.remove('btn-outline-dark');
                btn.classList.add('btn-dark');
                currentSizeId = btn.dataset.sizeId;
                updateCartButtonState();
            } else {
                currentSizeId = null;
                updateCartButtonState();
            }
        };
    });

    document.getElementById('addToCart').onclick = async () => {
        await handleAddToCart();
    };
}

async function handleAddToCart() {
    if (!currentSizeId) {
        alert('Выберите размер');
        return;
    }

    const sizeInfo = currentProduct.sizes.find(s => s.id == currentSizeId);
    
    if (!sizeInfo || sizeInfo.quantity <= 0) {
        alert('Выбранный размер отсутствует в наличии');
        return;
    }

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
    
    if (data.success) {
        const existingItem = data.items.find(item => 
            item.product_id == currentProduct.id && 
            item.size_id == currentSizeId
        );
        
        const currentQuantity = existingItem ? existingItem.quantity : 0;
        const available = sizeInfo.quantity - currentQuantity;
        
        if (available <= 0) {
            alert('Нет доступного количества');
            return;
        }
    }

    const addResponse = await fetch('/FIFI/api/basket.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: 'add',
            product_id: currentProduct.id,
            size_id: currentSizeId
        })
    });

    const addData = await addResponse.json();

    if (!addData.success) {
        if (addData.auth === false) {
            alert('Сначала войдите в аккаунт');
        } else if (addData.error) {
            alert(addData.error);
        }
        return;
    }

    await updateCartButtonState();
}

async function updateCartButtonState() {
    const cartControls = document.getElementById('cartControls');
    const cartStatus = document.getElementById('cartStatus');
    if (!cartControls) return;

    if (!currentSizeId) {
        cartControls.innerHTML = `
            <button class="btn btn-dark w-100" id="addToCart">
                ДОБАВИТЬ В КОРЗИНУ
            </button>
        `;
        if (cartStatus) cartStatus.textContent = 'Выберите размер';
        const btn = document.getElementById('addToCart');
        if (btn) {
            btn.onclick = handleAddToCart;
        }
        return;
    }

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
    let quantityInCart = 0;
    let maxQuantity = 0;
    
    if (data.success) {
        const sizeInfo = currentProduct.sizes.find(s => s.id == currentSizeId);
        maxQuantity = sizeInfo ? sizeInfo.quantity : 0;
        
        const existingItem = data.items.find(item => 
            item.product_id == currentProduct.id && 
            item.size_id == currentSizeId
        );
        quantityInCart = existingItem ? existingItem.quantity : 0;
    }

    const available = maxQuantity - quantityInCart;

    if (quantityInCart === 0) {
        cartControls.innerHTML = `
            <button class="btn btn-dark w-100" id="addToCart">
                ДОБАВИТЬ В КОРЗИНУ
            </button>
        `;
        const btn = document.getElementById('addToCart');
        if (btn) {
            btn.onclick = handleAddToCart;
        }
        if (cartStatus) cartStatus.textContent = `Доступно: ${available}`;
    } else {
        cartControls.innerHTML = `
            <div class="d-flex align-items-center gap-3 w-100">
                <button class="btn btn-secondary flex-grow-1" style="opacity: 0.6; cursor: default;">
                    В КОРЗИНЕ
                </button>
                <button class="btn btn-outline-secondary cart-decrease" id="cartDecrease">−</button>
                <span class="fw-semibold fs-5" id="cartQuantity">${quantityInCart}</span>
                <button class="btn btn-outline-secondary cart-increase" id="cartIncrease" ${available <= 0 ? 'disabled' : ''}>+</button>
            </div>
        `;
        
        document.getElementById('cartDecrease').onclick = async () => {
            await updateCartQuantity('remove');
        };
        
        document.getElementById('cartIncrease').onclick = async () => {
            await updateCartQuantity('add');
        };
        
        if (cartStatus) cartStatus.textContent = `Осталось: ${available}`;
    }
}

async function updateCartQuantity(action) {
    const response = await fetch('/FIFI/api/basket.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: action,
            product_id: currentProduct.id,
            size_id: currentSizeId
        })
    });

    const data = await response.json();

    if (!data.success) {
        if (data.auth === false) {
            alert('Сначала войдите в аккаунт');
        } else if (data.error) {
            alert(data.error);
        }
        return;
    }

    await updateCartButtonState();
}

document.addEventListener('DOMContentLoaded', async ()=>{
    initFavouriteButtons();
    await loadProduct();
});

document.addEventListener('favouritesUpdated', function() {
    const btn = document.querySelector('.product-page .favourite-btn, #productPage .favourite-btn');
    if (btn) {
        const productId = btn.dataset.id;
        if (isFavourite(productId)) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    }
});