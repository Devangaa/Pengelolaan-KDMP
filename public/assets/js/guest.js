document.addEventListener('DOMContentLoaded', function () {
    const mobileToggle = document.querySelector('[data-mobile-menu-toggle]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');

    if (mobileToggle && mobileMenu) {
        mobileToggle.addEventListener('click', function () {
            const isOpen = mobileMenu.classList.contains('max-h-0');

            if (isOpen) {
                mobileMenu.classList.remove('max-h-0');
                mobileMenu.classList.add('max-h-80');
            } else {
                mobileMenu.classList.remove('max-h-80');
                mobileMenu.classList.add('max-h-0');
            }
        });
    }

    const list = document.getElementById('product-list');
    const loader = document.getElementById('product-loader');
    const dropdowns = document.querySelectorAll('[data-dropdown]');
    const buttons = document.querySelectorAll('[data-product-filter]');
    const searchInput = document.querySelector('[data-product-search]');
    const searchClear = document.querySelector('[data-search-clear]');
    let searchKeyword = searchInput ? searchInput.value.trim() : '';
    let searchTimer = null;

    function getCurrentFilters() {
        return {
            category: document.querySelector('[data-dropdown-name="category"] [data-dropdown-value]')?.getAttribute('data-value') || '',
            sort: document.querySelector('[data-dropdown-name="sort"] [data-dropdown-value]')?.getAttribute('data-value') || 'popular',
        };
    }

    function loadProducts(category, sort) {
        if (!list || !loader) {
            return;
        }

        loader.classList.remove('hidden');
        list.innerHTML = '';

        const params = new URLSearchParams();
        if (category) {
            params.set('category', category);
        }
        if (sort) {
            params.set('sort', sort);
        }

        if (searchKeyword) {
            params.set('q', searchKeyword);
        }

        fetch('/products/filter?' + params.toString())
            .then(function (response) {
                return response.text();
            })
            .then(function (html) {
                list.innerHTML = html;
                loader.classList.add('hidden');
            })
            .catch(function () {
                loader.classList.remove('hidden');
                loader.textContent = 'Gagal memuat produk. Coba lagi.';
            });
    }

    // Custom dropdown behavior
    dropdowns.forEach(function (root) {
        const name = root.getAttribute('data-dropdown-name');
        const toggle = root.querySelector('[data-dropdown-toggle]');
        const valueEl = root.querySelector('[data-dropdown-value]');
        const listEl = root.querySelector('[data-dropdown-list]');

        if (!toggle || !listEl) return;

        // Toggle open/close
        toggle.addEventListener('click', function (e) {
            e.stopPropagation();
            const isHidden = listEl.classList.contains('hidden');
            document.querySelectorAll('[data-dropdown-list]').forEach(function (el) { if (el !== listEl) el.classList.add('hidden'); });
            listEl.classList.toggle('hidden', !isHidden);
        });

        // Option click
        listEl.querySelectorAll('[data-dropdown-option]').forEach(function (opt) {
            opt.addEventListener('click', function () {
                const val = this.getAttribute('data-value') || '';
                const label = this.textContent.trim();
                if (valueEl) {
                    valueEl.textContent = label;
                    valueEl.setAttribute('data-value', val);
                }
                listEl.classList.add('hidden');

                // trigger load
                const currentCategory = document.querySelector('[data-dropdown-name="category"] [data-dropdown-value]')?.getAttribute('data-value') || '';
                const currentSort = document.querySelector('[data-dropdown-name="sort"] [data-dropdown-value]')?.getAttribute('data-value') || 'popular';
                if (name === 'sort') {
                    loadProducts(currentCategory, val || 'popular');
                } else if (name === 'category') {
                    loadProducts(val || '', currentSort);
                }
            });
        });
    });

    // Close dropdown on outside click
    document.addEventListener('click', function () {
        document.querySelectorAll('[data-dropdown-list]').forEach(function (el) { el.classList.add('hidden'); });
    });

    if (searchInput) {
        const toggleClear = function () {
            if (searchClear) {
                searchClear.classList.toggle('hidden', !searchInput.value.trim());
            }
        };

        toggleClear();

        searchInput.addEventListener('input', function () {
            searchKeyword = this.value.trim();
            toggleClear();

            if (searchTimer) {
                clearTimeout(searchTimer);
            }

            searchTimer = setTimeout(function () {
                loadProducts(getCurrentFilters().category, getCurrentFilters().sort);
            }, 300);
        });

        searchInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                searchKeyword = this.value.trim();
                if (searchTimer) {
                    clearTimeout(searchTimer);
                }
                loadProducts(getCurrentFilters().category, getCurrentFilters().sort);
            }

            if (event.key === 'Escape') {
                event.preventDefault();
                if (searchInput.value.trim()) {
                    searchInput.value = '';
                    searchKeyword = '';
                    toggleClear();
                    if (searchTimer) {
                        clearTimeout(searchTimer);
                    }
                    loadProducts(getCurrentFilters().category, getCurrentFilters().sort);
                }
            }
        });
    }

    if (searchClear) {
        searchClear.addEventListener('click', function () {
            if (searchInput) {
                searchInput.value = '';
                searchKeyword = '';
                this.classList.add('hidden');
                loadProducts(
                    document.querySelector('[data-dropdown-name="category"] [data-dropdown-value]')?.getAttribute('data-value') || '',
                    document.querySelector('[data-dropdown-name="sort"] [data-dropdown-value]')?.getAttribute('data-value') || 'popular'
                );
            }
        });
    }

    if (buttons.length && list && loader) {
        buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                buttons.forEach(function (item) {
                    item.classList.remove('bg-red-600', 'text-white');
                    item.classList.add('bg-white', 'text-stone-700');
                });

                this.classList.remove('bg-white', 'text-stone-700');
                this.classList.add('bg-red-600', 'text-white');

                loadProducts(this.getAttribute('data-product-filter') || '', 'popular');
            });
        });
    }

    // Initial load based on dropdown default values
    const initialCategory = document.querySelector('[data-dropdown-name="category"] [data-dropdown-value]')?.getAttribute('data-value') || '';
    const initialSort = document.querySelector('[data-dropdown-name="sort"] [data-dropdown-value]')?.getAttribute('data-value') || 'popular';
    loadProducts(initialCategory, initialSort);
});
