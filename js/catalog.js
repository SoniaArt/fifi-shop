import {loadFavouriteIds, isFavourite} from './user/favourites.js';
import {initFavouriteButtons} from './user/favourite-actions.js';

window.catalogProducts = [];
window.allProducts = [];
let searchQuery = '';

function getUrlParams() {
    const params = new URLSearchParams(window.location.search);
    return {
        search: params.get('search') || ''
    };
}

function renderProducts(products) {
    const productGrid = document.getElementById('productGrid');
    let html = '';
    const isAdmin = window.isAdmin || false;

    products.forEach(product => {
        const inStock = product.quantity > 0;
        const stockClass = inStock ? '' : 'product-out-of-stock';

        html += `
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="product-card ${stockClass}">
                    <a href="product.php?id=${product.id}" class="product-link">
                        <div class="product-image-wrapper">
                            <img src="../${product.image}" class="product-image img-fluid" alt="${product.name}">
                            <button class="favourite-btn ${isFavourite(product.id) ? 'active' : ''}" data-id="${product.id}">
                                <img src="../images/favourites_icon.png" alt="Избранное" class="favourite-icon">
                            </button>
                            ${!inStock ? '<span class="out-of-stock-badge">Нет в наличии</span>' : ''}
                            ${isAdmin ? `
                                <div class="admin-actions position-absolute bottom-0 start-0 w-100 p-2 d-flex gap-1" style="z-index: 5; background: rgba(253, 253, 253, 0.9);">
                                    <a href="admin_product.php?id=${product.id}" class="btn btn-sm admin-edit-btn flex-grow-1" style="border: 1px solid #1E1E1E; color: #1E1E1E; background: transparent; font-size: 12px; padding: 4px 8px; border-radius: 0; text-decoration: none; text-align: center;">
                                        Редактировать
                                    </a>
                                    <button class="btn btn-sm admin-delete-btn" data-id="${product.id}" style="border: 1px solid #dc3545; color: #dc3545; background: transparent; font-size: 12px; padding: 4px 8px; border-radius: 0; cursor: pointer;">
                                        Удалить
                                    </button>
                                </div>
                            ` : ''}
                        </div>
                    </a>
                    <div class="text-center">
                        <p>${product.name}</p>
                        <p>${Number(product.price).toLocaleString('ru-RU')} ₽</p>
                    </div>
                </div>  
            </div>
        `;
    });

    productGrid.innerHTML = html;
    
    if (isAdmin) {
        document.querySelectorAll('.admin-delete-btn').forEach(btn => {
            btn.onclick = async function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                if (!confirm('Удалить товар?')) return;
                
                const id = this.dataset.id;
                try {
                    const response = await fetch('/FIFI/api/admin_products.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            action: 'delete',
                            id: id
                        })
                    });
                    
                    const data = await response.json();
                    if (data.success) {
                        loadProducts();
                    } else {
                        alert(data.error || 'Ошибка удаления');
                    }
                } catch (error) {
                    alert('Ошибка при удалении');
                }
            };
        });
    }
}

async function loadProducts(params = {}) {
    const query = new URLSearchParams();

    query.append('sort', params.sort || 'newest');

    if (params.category) {
        params.category.forEach(item => {
            query.append('category[]', item);
        });
    }

    if (params.color) {
        params.color.forEach(item => {
            query.append('color[]', item);
        });
    }

    if (params.size) {
        params.size.forEach(item => {
            query.append('size[]', item);
        });
    }

    const urlParams = getUrlParams();
    const search = searchQuery || urlParams.search;
    if (search) {
        query.append('search', search);
    }

    const response = await fetch('../api/catalog.php?' + query.toString());
    const data = await response.json();

    if (!data.success) {
        return;
    }

    document.getElementById('sortLabel').textContent = data.sortLabel;

    if (window.allProducts.length === 0 || window.allProducts.length < data.products.length) {
        window.allProducts = data.products;
    }

    window.catalogProducts = data.products;
    renderProducts(data.products);
}

async function loadFilterOptions() {
    const categoriesContainer = document.getElementById('filterCategories');
    const colorsContainer = document.getElementById('filterColors');
    const sizesContainer = document.getElementById('filterSizes');

    categoriesContainer.innerHTML = '';
    colorsContainer.innerHTML = '';
    sizesContainer.innerHTML = '';

    const response = await fetch('../api/filters.php');
    const data = await response.json();
    
    if (!data.success) {
        return;
    }
    
    data.categories.forEach(cat => {
        const label = document.createElement('label');
        label.innerHTML = `<input type="checkbox" class="filter-checkbox" data-type="category" value="${cat}"> ${cat}`;
        categoriesContainer.appendChild(label);
    });
    
    data.colors.forEach(color => {
        const label = document.createElement('label');
        label.innerHTML = `<input type="checkbox" class="filter-checkbox" data-type="color" value="${color}"> ${color}`;
        colorsContainer.appendChild(label);
    });

    data.sizes.forEach(size => {
        const label = document.createElement('label');
        label.innerHTML = `<input type="checkbox" class="filter-checkbox" data-type="size" value="${size}"> ${size}`;
        sizesContainer.appendChild(label);
    });
}

document.addEventListener('DOMContentLoaded', async () => {
    try {
        const response = await fetch('/FIFI/api/auth.php?action=checkAdmin');
        const data = await response.json();
        window.isAdmin = data.isAdmin || false;
    } catch (e) {
        window.isAdmin = false;
    }
    
    initFavouriteButtons();
    await loadFavouriteIds();
    await loadFilterOptions();
    await loadProducts();
    
    const filter = new Filter(loadProducts);
    filter.updateDisabled();

    document.querySelectorAll('.sort-option').forEach(option => {
        option.addEventListener('click', e => {
            e.preventDefault();
            document.querySelectorAll('.sort-option').forEach(el => el.classList.remove('active'));
            option.classList.add('active');
            const filters = filter.getFilters();

            loadProducts({
                sort: option.dataset.sort,
                category: filters.category,
                color: filters.color,
                size: filters.size
            });
        });
    });
    
    if (window.isAdmin) {
        const catalogHeader = document.querySelector('.catalog-header');
        if (catalogHeader) {
            if (!catalogHeader.querySelector('.add-product-wrapper')) {
                const addWrapper = document.createElement('div');
                addWrapper.className = 'add-product-wrapper';
                addWrapper.style.cssText = `
                    margin-top: 16px;
                    text-align: right;
                `;
                addWrapper.innerHTML = `
                    <a href="admin_product.php" class="btn add-product-btn" style="
                        border: 1px solid #1E1E1E;
                        color: #1E1E1E;
                        background: transparent;
                        border-radius: 0;
                        padding: 8px 20px;
                        font-size: 14px;
                        text-decoration: none;
                        transition: all 0.3s ease;
                        display: inline-block;
                    ">+ Добавить товар</a>
                `;
                catalogHeader.appendChild(addWrapper);
                
                const link = addWrapper.querySelector('.add-product-btn');
                link.onmouseover = function() {
                    this.style.background = '#1E1E1E';
                    this.style.color = '#FDFDFD';
                };
                link.onmouseout = function() {
                    this.style.background = 'transparent';
                    this.style.color = '#1E1E1E';
                };
            }
        }
    }
});

document.addEventListener('favouritesUpdated', function() {
    document.querySelectorAll('#productGrid .favourite-btn').forEach(btn => {
        const productId = btn.dataset.id;
        if (isFavourite(productId)) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });
});