window.catalogProducts = [];
window.allProducts = [];

function renderProducts(products) {
    const productGrid = document.getElementById('productGrid');
    let html = '';

    products.forEach(product => {
        const inStock = product.quantity > 0;
        const stockClass = inStock ? '' : 'product-out-of-stock';

        html += `
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="product-card ${stockClass}">
                    <div class="product-image-wrapper">
                        <img src="../${product.image}" class="product-image img-fluid" alt="${product.name}">
                        <button class="favourite-btn" data-id="${product.id}">
                            <img src="../images/favourites_icon.png" alt="Избранное" class="favourite-icon">
                        </button>
                        ${!inStock ? '<span class="out-of-stock-badge">Нет в наличии</span>' : ''}
                    </div>
                    <div class="text-center">
                        <p>${product.name}</p>
                        <p>${Number(product.price).toLocaleString('ru-RU')} ₽</p>
                    </div>
                </div>
            </div>
        `;
    });

    productGrid.innerHTML = html;
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
});

document.addEventListener('click', function(e) {
    if (e.target.closest('.favourite-btn')) {
        const btn = e.target.closest('.favourite-btn');
        const id = btn.dataset.id;
        btn.classList.toggle('active');
        console.log('Товар #' + id + ' добавлен в избранное (заглушка)');
    }
});