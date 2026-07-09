let currentProduct = null;
let isEditing = false;

export async function initAdminProduct() {
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');
    
    if (id) {
        isEditing = true;
        await loadProduct(id);
    } else {
        isEditing = false;
        renderEmptyForm();
    }
}

async function loadProduct(id) {
    try {
        const response = await fetch('/FIFI/api/admin_products.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'get',
                id: id
            })
        });
        
        const data = await response.json();
        
        if (!data.success) {
            alert('Товар не найден');
            window.location.href = '/FIFI/pages/catalog.php';
            return;
        }
        
        currentProduct = data.product;
        renderEditForm(currentProduct);
    } catch (error) {
        console.error('Error loading product:', error);
        alert('Ошибка загрузки товара');
    }
}

function renderEditForm(product) {
    const container = document.getElementById('productPage');

    const category = product.category || '';
    let allSizes = [];
    if (category === 'Халаты' || category === 'Халат') {
        allSizes = ['XS', 'S', 'M', 'L', 'XL'];
    } else if (category === 'Маски') {
        allSizes = ['One Size'];
    } else {
        allSizes = ['XS', 'S', 'M', 'L', 'XL', 'One Size'];
    }
    
    let sizesHtml = '';
    allSizes.forEach(size => {
        const sizeInfo = product.sizes ? product.sizes.find(s => s.size === size) : null;
        const quantity = sizeInfo ? sizeInfo.quantity : 0;
        const sizeId = sizeInfo ? sizeInfo.id : '';
        
        sizesHtml += `
            <div class="size-row d-flex align-items-center gap-2" data-size-id="${sizeId}" data-size-name="${size}">
                <span class="fw-bold" style="min-width: 70px;">${size}</span>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm size-decrease" type="button" style="border: 1px solid #b2b2b2; background: transparent; border-radius: 50%; width: 28px; height: 28px; padding: 0; display: flex; align-items: center; justify-content: center;">−</button>
                    <input type="number" class="form-control form-control-sm size-quantity" 
                           value="${quantity}" min="0" style="width: 60px; text-align: center; border: none; border-bottom: 1px solid #b2b2b2; border-radius: 0; background: #FDFDFD;">
                    <button class="btn btn-sm size-increase" type="button" style="border: 1px solid #b2b2b2; background: transparent; border-radius: 50%; width: 28px; height: 28px; padding: 0; display: flex; align-items: center; justify-content: center;">+</button>
                </div>
            </div>
        `;
    });

    let mainImageHtml = '';
    if (product.image) {
        mainImageHtml = `
            <div class="image-item d-flex align-items-center gap-2 mb-2" style="background: #f8f8f8; padding: 8px 12px; border-radius: 4px; border: 2px solid #1E1E1E;">
                <img src="/FIFI/${product.image}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;" 
                     onerror="this.style.display='none'">
                <span class="small flex-grow-1 fw-bold">${product.image}</span>
                <span class="badge bg-dark">Главное</span>
                <button class="btn btn-sm remove-main-image" type="button" style="border: 1px solid #dc3545; color: #dc3545; background: transparent; border-radius: 0; padding: 2px 10px;">×</button>
            </div>
        `;
    }

    let additionalImagesHtml = '';
    if (product.images && product.images.length > 0) {
        product.images.forEach((img) => {
            if (img && img !== product.image) {
                additionalImagesHtml += `
                    <div class="image-item d-flex align-items-center gap-2 mb-2" style="background: #f8f8f8; padding: 8px 12px; border-radius: 4px;">
                        <img src="/FIFI/${img}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;" 
                             onerror="this.style.display='none'">
                        <span class="small flex-grow-1">${img}</span>
                        <button class="btn btn-sm remove-image" type="button" style="border: 1px solid #dc3545; color: #dc3545; background: transparent; border-radius: 0; padding: 2px 10px;">×</button>
                    </div>
                `;
            }
        });
    }
    
    container.innerHTML = `
        <div class="admin-product-form py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>${isEditing ? 'Редактирование товара' : 'Добавление товара'}</h2>
                <div>
                    ${isEditing ? `<button class="btn admin-delete-btn me-2" id="deleteProduct" style="border: 1px solid #dc3545; color: #dc3545; background: transparent; border-radius: 0; padding: 8px 16px; transition: all 0.3s ease;">УДАЛИТЬ</button>` : ''}
                    <a href="/FIFI/pages/catalog.php" class="btn" style="border: 1px solid #1E1E1E; color: #1E1E1E; background: transparent; border-radius: 0; padding: 8px 16px; transition: all 0.3s ease;">Назад</a>
                </div>
            </div>
            
            <form id="adminProductForm">
                <input type="hidden" id="productId" value="${product.id || ''}">
                <input type="hidden" id="productCategoryHidden" value="${product.category || ''}">
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Название товара</label>
                        <input type="text" class="form-control" id="productName" 
                               value="${product.name || ''}" style="border: none; border-bottom: 1px solid #b2b2b2; border-radius: 0; background: #FDFDFD;" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Цена (₽)</label>
                        <input type="number" class="form-control" id="productPrice" 
                               value="${product.price || ''}" min="0" style="border: none; border-bottom: 1px solid #b2b2b2; border-radius: 0; background: #FDFDFD;" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Категория</label>
                        <input type="text" class="form-control" id="productCategory" 
                               value="${product.category || ''}" style="border: none; border-bottom: 1px solid #b2b2b2; border-radius: 0; background: #FDFDFD;" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Цвет</label>
                        <input type="text" class="form-control" id="productColor" 
                               value="${product.color || ''}" style="border: none; border-bottom: 1px solid #b2b2b2; border-radius: 0; background: #FDFDFD;" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Описание</label>
                        <textarea class="form-control" id="productDescription" rows="4" style="border: none; border-bottom: 1px solid #b2b2b2; border-radius: 0; background: #FDFDFD;">${product.description || ''}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Главное изображение</label>
                        <div id="mainImageContainer">
                            ${mainImageHtml}
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <input type="text" class="form-control" id="productImage" 
                                   value="${product.image || ''}" placeholder="catalog/product.jpg" style="border: none; border-bottom: 1px solid #b2b2b2; border-radius: 0; background: #FDFDFD; flex: 1;">
                            <button class="btn btn-upload-main" type="button" style="border: 1px solid #b2b2b2; color: #1E1E1E; background: transparent; border-radius: 0; padding: 8px 16px; transition: all 0.3s ease;">Выбрать</button>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Дополнительные изображения</label>
                        <div id="imagesContainer">
                            ${additionalImagesHtml}
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <button class="btn btn-add-image" type="button" style="border: 1px solid #b2b2b2; color: #1E1E1E; background: transparent; border-radius: 0; padding: 8px 16px; transition: all 0.3s ease;">+ Добавить</button>
                        </div>
                    </div>
                    
                    <div class="col-12 text-center">
                        <label class="form-label fw-semibold">Наличие по размерам</label>
                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            ${sizesHtml}
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <button type="submit" class="btn" style="border: 1px solid #1E1E1E; color: #1E1E1E; background: transparent; border-radius: 0; padding: 10px 40px; transition: all 0.3s ease;">
                        ${isEditing ? 'СОХРАНИТЬ' : 'СОЗДАТЬ'}
                    </button>
                </div>
            </form>
        </div>
    `;
    
    setupFormEvents();
}

function renderEmptyForm() {
    renderEditForm({
        id: '',
        name: '',
        price: '',
        description: '',
        category: '',
        color: '',
        image: '',
        sizes: [],
        images: []
    });
}

function setupFormEvents() {
    document.getElementById('productCategory')?.addEventListener('change', function() {
        const category = this.value.trim();
        document.getElementById('productCategoryHidden').value = category;
        
        const product = {
            ...currentProduct,
            category: category
        };
        renderEditForm(product);
    });
    
    document.querySelectorAll('.size-decrease').forEach(btn => {
        btn.onclick = function() {
            const input = this.closest('.size-row').querySelector('.size-quantity');
            let val = parseInt(input.value) || 0;
            if (val > 0) input.value = val - 1;
        };
    });
    
    document.querySelectorAll('.size-increase').forEach(btn => {
        btn.onclick = function() {
            const input = this.closest('.size-row').querySelector('.size-quantity');
            let val = parseInt(input.value) || 0;
            input.value = val + 1;
        };
    });

    document.querySelectorAll('.remove-main-image').forEach(btn => {
        btn.onclick = function() {
            this.closest('.image-item').remove();
            document.getElementById('productImage').value = '';
        };
    });
    
    document.querySelectorAll('.remove-image').forEach(btn => {
        btn.onclick = function() {
            this.closest('.image-item').remove();
        };
    });
    
    const mainFileInput = document.createElement('input');
    mainFileInput.type = 'file';
    mainFileInput.accept = 'image/*';
    mainFileInput.style.display = 'none';
    document.body.appendChild(mainFileInput);
    
    document.querySelector('.btn-upload-main')?.addEventListener('click', function() {
        mainFileInput.click();
    });
    
    mainFileInput.onchange = async function() {
        const file = this.files[0];
        if (!file) return;
        
        const formData = new FormData();
        formData.append('image', file);
        
        try {
            const response = await fetch('/FIFI/api/upload_image.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            if (data.success) {
                const path = data.path;
                document.getElementById('productImage').value = path;
                const product = { ...currentProduct, image: path };
                renderEditForm(product);
            } else {
                alert('Ошибка загрузки: ' + data.error);
            }
        } catch (err) {
            alert('Ошибка загрузки файла');
        }
        mainFileInput.value = '';
    };
    
    const additionalFileInput = document.createElement('input');
    additionalFileInput.type = 'file';
    additionalFileInput.accept = 'image/*';
    additionalFileInput.style.display = 'none';
    additionalFileInput.multiple = true;
    document.body.appendChild(additionalFileInput);
    
    document.querySelector('.btn-add-image')?.addEventListener('click', function() {
        additionalFileInput.click();
    });
    
    additionalFileInput.onchange = async function() {
        const files = this.files;
        if (!files || files.length === 0) return;
        
        const container = document.getElementById('imagesContainer');
        
        for (const file of files) {
            const formData = new FormData();
            formData.append('image', file);
            
            try {
                const response = await fetch('/FIFI/api/upload_image.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                
                if (data.success) {
                    const path = data.path;
                    const div = document.createElement('div');
                    div.className = 'image-item d-flex align-items-center gap-2 mb-2';
                    div.style.cssText = 'background: #f8f8f8; padding: 8px 12px; border-radius: 4px;';
                    div.innerHTML = `
                        <img src="/FIFI/${path}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;" 
                             onerror="this.style.display='none'">
                        <span class="small flex-grow-1">${path}</span>
                        <button class="btn btn-sm remove-image" type="button" style="border: 1px solid #dc3545; color: #dc3545; background: transparent; border-radius: 0; padding: 2px 10px;">×</button>
                    `;
                    container.appendChild(div);
                    
                    div.querySelector('.remove-image').onclick = function() {
                        this.closest('.image-item').remove();
                    };
                } else {
                    alert('Ошибка загрузки: ' + data.error);
                }
            } catch (err) {
                alert('Ошибка загрузки файла');
            }
        }
        additionalFileInput.value = '';
    };
    
    document.getElementById('deleteProduct')?.addEventListener('click', async function() {
        if (!confirm('Вы уверены, что хотите удалить этот товар?')) return;
        
        const id = document.getElementById('productId').value;
        
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
                alert('Товар удален');
                window.location.href = '/FIFI/pages/catalog.php';
            } else {
                alert(data.error || 'Ошибка удаления');
            }
        } catch (error) {
            alert('Ошибка при удалении');
        }
    });
    
    document.getElementById('adminProductForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const id = document.getElementById('productId').value;
        const name = document.getElementById('productName').value.trim();
        const price = parseInt(document.getElementById('productPrice').value);
        const category = document.getElementById('productCategory').value.trim();
        const color = document.getElementById('productColor').value.trim();
        const description = document.getElementById('productDescription').value.trim();
        const image = document.getElementById('productImage').value.trim();
        
        if (!name || !price || !category || !color) {
            alert('Заполните все обязательные поля');
            return;
        }
        
        const sizes = [];
        document.querySelectorAll('.size-row').forEach(row => {
            const sizeId = row.dataset.sizeId;
            const quantity = parseInt(row.querySelector('.size-quantity').value) || 0;
            const sizeName = row.dataset.sizeName;
            sizes.push({ id: sizeId, size: sizeName, quantity });
        });
        
        const images = [];
        document.querySelectorAll('#imagesContainer .image-item img').forEach(img => {
            const src = img.getAttribute('src');
            if (src) {
                const path = src.replace(/^\/FIFI\//, '');
                if (path) images.push(path);
            }
        });
        
        let finalImage = image;
        if (!finalImage && images.length > 0) {
            finalImage = images[0];
        }
        
        const productData = {
            id: id,
            name: name,
            price: price,
            category: category,
            color: color,
            description: description,
            image: finalImage,
            sizes: sizes,
            images: images
        };
        
        try {
            const response = await fetch('/FIFI/api/admin_products.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: isEditing ? 'update' : 'create',
                    ...productData
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert(isEditing ? 'Товар сохранен' : 'Товар создан');
                window.location.href = '/FIFI/pages/catalog.php';
            } else {
                alert(data.error || 'Ошибка сохранения');
            }
        } catch (error) {
            alert('Ошибка при сохранении');
        }
    });
}