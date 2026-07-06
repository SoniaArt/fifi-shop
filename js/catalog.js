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
    const query = new URLSearchParams({
        sort: 'newest',
        ...params
    });

    const response = await fetch('../api/catalog.php?' + query);
    const data = await response.json();

    if (!data.success) {
        return;
    }

    document.getElementById('sortLabel').textContent = data.sortLabel;
    renderProducts(data.products);
}

async function loadFilterOptions() {
    const response = await fetch('../api/filters.php');
    const data = await response.json();
    
    if (!data.success) {
        return;
    }
    
    const categoriesContainer = document.getElementById('filterCategories');
    data.categories.forEach(cat => {
        const label = document.createElement('label');
        label.innerHTML = `<input type="checkbox" class="filter-checkbox" data-type="category" value="${cat}"> ${cat}`;
        categoriesContainer.appendChild(label);
    });
    
    const colorsContainer = document.getElementById('filterColors');
    data.colors.forEach(color => {
        const label = document.createElement('label');
        label.innerHTML = `<input type="checkbox" class="filter-checkbox" data-type="color" value="${color}"> ${color}`;
        colorsContainer.appendChild(label);
    });

    const sizesContainer = document.getElementById('filterSizes');
    data.sizes.forEach(size => {
        const label = document.createElement('label');
        label.innerHTML = `<input type="checkbox" class="filter-checkbox" data-type="size" value="${size}"> ${size}`;
        sizesContainer.appendChild(label);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.sort-option').forEach(option => {
        option.addEventListener('click', e => {
            e.preventDefault();
            document.querySelectorAll('.sort-option').forEach(el => el.classList.remove('active'));
            option.classList.add('active');
            loadProducts({
                sort: option.dataset.sort
            });
        });
    });

    loadFilterOptions();
    loadProducts();
});

document.addEventListener('click', function(e) {
    if (e.target.closest('.favourite-btn')) {
        const btn = e.target.closest('.favourite-btn');
        const id = btn.dataset.id;
        btn.classList.toggle('active');
        console.log('Товар #' + id + ' добавлен в избранное (заглушка)');
    }
});