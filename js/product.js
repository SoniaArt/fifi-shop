const ALL_SIZES = ['XS', 'S', 'M', 'L', 'XL', 'One Size'];

async function loadProduct() {
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');
    const response = await fetch('../api/product.php?id=' + id);
    const data = await response.json();

    if (!data.success) {
        return;
    }

    renderProduct(data.product);
}

function renderProduct(product) {
    let sizesHtml = '';
    ALL_SIZES.forEach(size => {
        const sizeInfo = product.sizes.find(s => s.size === size);
        const available = sizeInfo && sizeInfo.quantity > 0;
        sizesHtml += `
            <button class="btn ${available ? 'btn-outline-dark' : 'btn-outline-secondary'} size-btn" ${available ? '' : 'disabled'}> ${size}
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

                <div class="d-flex gap-3 mt-5">
                    <button class="btn btn-dark flex-grow-1" id="addToCart">
                        ДОБАВИТЬ В КОРЗИНУ
                    </button>

                    <button class="btn favourite-btn">
                        <img src="../images/favourites_icon.png" class="header-icon" alt="Избранное">
                    </button>
                </div>
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
            }
        };
    });
}

document.addEventListener('DOMContentLoaded', loadProduct);