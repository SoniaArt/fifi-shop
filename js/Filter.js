class Filter {

    constructor(loadProducts) {
        this.loadProducts = loadProducts;
        this.filters = {
            category: [],
            color: [],
            size: []
        };
        this.init();
    }

    init() {
        document.addEventListener('change', (e) => {
            if (!e.target.classList.contains('filter-checkbox')) {
                return;
            }
            this.collectFilters();
            this.updateDisabled();
        });

        document.getElementById('applyFilters').addEventListener('click', () => {
            this.collectFilters();
            this.loadProducts({
                category: this.filters.category,
                color: this.filters.color,
                size: this.filters.size
            });

            document.getElementById('filterForm').classList.remove('active');
            $('.overlay').fadeOut(300);
            $('html, body').removeClass('menu-open form-open');
        });

        document.getElementById('resetFilters').addEventListener('click', () => {
            document.querySelectorAll('.filter-checkbox').forEach(cb => {
                cb.checked = false;
                cb.disabled = false;
            });

            this.filters = {
                category: [],
                color: [],
                size: []
            };

            this.loadProducts();
        });
    }

    collectFilters() {
        this.filters = {
            category: [],
            color: [],
            size: []
        };

        document.querySelectorAll('.filter-checkbox:checked').forEach(cb => {
            this.filters[cb.dataset.type].push(cb.value);
        });
    }

    updateDisabled() {
        const checkboxes = document.querySelectorAll('.filter-checkbox');

        const currentProducts = window.allProducts.filter(product => {
            if (this.filters.category.length && !this.filters.category.includes(product.category)) {
                return false;
            }

            if (this.filters.color.length && !this.filters.color.includes(product.color)) {
                return false;
            }

            if (this.filters.size.length) {
                let ok = false;
                for (const size of product.sizes) {
                    if (this.filters.size.includes(size)) {
                        ok = true;
                        break;
                    }
                }
                if (!ok) {
                    return false;
                }
            }
            return true;
        });


        checkboxes.forEach(current => {
            if (current.checked) {
                current.disabled = false;
                return;
            }

            const found = window.allProducts.some(product => {
                if (this.filters.category.length && current.dataset.type !== 'category' && !this.filters.category.includes(product.category)) {
                    return false;
                }

                if (this.filters.color.length && current.dataset.type !== 'color' && !this.filters.color.includes(product.color)
                ) {
                    return false;
                }

                if (this.filters.size.length && current.dataset.type !== 'size') {
                    const ok = product.sizes.some(size => this.filters.size.includes(size));

                    if (!ok) {
                        return false;
                    }
                }

                if (current.dataset.type === 'category') {
                    return product.category === current.value;
                }

                if (current.dataset.type === 'color') {
                    return product.color === current.value;
                }

                return product.sizes.includes(current.value);

            });

            current.disabled = !found;
        });

        checkboxes.forEach(cb => {
            if (cb.checked) {
                return;
            }

            let exists = false;

            for (const product of window.allProducts) {
                if (cb.dataset.type === 'category' && product.category === cb.value) {
                    exists = true;
                }

                if (cb.dataset.type === 'color' && product.color === cb.value) {
                    exists = true;
                }

                if (cb.dataset.type === 'size' && product.sizes.includes(cb.value)) {
                    exists = true;
                }

            }

            if (!exists) {
                cb.disabled = true;
            }

        });

    }

    getFilters() {
        this.collectFilters();
        return this.filters;
    }
}