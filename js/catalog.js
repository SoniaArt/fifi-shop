function renderProducts(products) {
    const productGrid = document.getElementById('productGrid');
    let html = '';

    products.forEach(product => {
        html += `
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="product-card">
                    <img src="../${product.image}" class="product-image img-fluid" alt="${product.name}">
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

    loadProducts();
});