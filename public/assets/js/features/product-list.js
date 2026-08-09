function onReady(fn) {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', fn);
    } else {
        fn();
    }
}

onReady(function () {
    initProductPage();
});

function initProductPage() {
    let productContainer = document.getElementById('product-section');

    if (!productContainer) {
        return;
    }

    function debounce(func, delay = 400) {
        let timeoutId;

        return function (...args) {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => func.apply(this, args), delay);
        };
    }

    function getFilterForm() {
        return document.getElementById('filter-form');
    }

    function getSearchInput() {
        return document.querySelector('[data-product-search]');
    }

    function getClearSearchBtn() {
        return document.querySelector('[data-search-clear]');
    }

    function setSearchClearButton() {
        const searchInput = getSearchInput();
        const clearSearchBtn = getClearSearchBtn();

        if (!searchInput || !clearSearchBtn) {
            return;
        }

        const hasValue = searchInput.value.trim() !== '';
        clearSearchBtn.classList.toggle('hidden', !hasValue);
        clearSearchBtn.classList.toggle('flex', hasValue);
    }

    function fetchProducts(url) {
        const activeElement = document.activeElement;
        const isActiveSearch = activeElement && activeElement.matches && activeElement.matches('[data-product-search]');
        const selectionStart = isActiveSearch ? activeElement.selectionStart : null;
        const selectionEnd = isActiveSearch ? activeElement.selectionEnd : null;

        productContainer.classList.add('opacity-50', 'pointer-events-none');

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error('Network error');
                }
                return response.text();
            })
            .then((html) => {
                productContainer.innerHTML = html;
                setSearchClearButton();

                if (isActiveSearch) {
                    const newSearchInput = document.querySelector('[data-product-search]');
                    if (newSearchInput) {
                        newSearchInput.focus();
                        if (selectionStart !== null && selectionEnd !== null) {
                            newSearchInput.setSelectionRange(selectionStart, selectionEnd);
                        }
                    }
                }
            })
            .catch((err) => {
                console.error('Error filter:', err);
            })
            .finally(() => {
                productContainer.classList.remove('opacity-50', 'pointer-events-none');
            });
    }

    function triggerFilter() {
        const filterForm = getFilterForm();
        if (!filterForm) {
            return;
        }

        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);
        const actionUrl = filterForm.getAttribute('action') || (window.location.pathname.startsWith('/katalog') ? '/katalog/saring' : '/produk/saring');

        fetchProducts(`${actionUrl}?${params.toString()}`);
    }

    document.addEventListener('change', function (event) {
        if (event.target.closest('#filter-form')) {
            triggerFilter();
        }
    });

    document.addEventListener('submit', function (event) {
        const filterForm = event.target.closest('#filter-form');
        if (!filterForm) {
            return;
        }

        event.preventDefault();
        triggerFilter();
    });

    document.addEventListener('keydown', function (event) {
        const searchInput = event.target.closest('[data-product-search]');
        if (!searchInput) {
            return;
        }

        if (event.key === 'Enter') {
            event.preventDefault();
            setSearchClearButton();
            triggerFilter();
        }
    });

    document.addEventListener('click', function (event) {
        const clearSearchBtn = event.target.closest('[data-search-clear]');
        if (clearSearchBtn) {
            const searchInput = getSearchInput();
            if (!searchInput) {
                return;
            }

            searchInput.value = '';
            setSearchClearButton();
            triggerFilter();
            return;
        }

        const toggleBtn = event.target.closest('[data-dropdown-toggle]');
        if (toggleBtn) {
            const parent = toggleBtn.closest('[data-dropdown]');
            const list = parent.querySelector('[data-dropdown-list]');
            const allLists = document.querySelectorAll('[data-dropdown-list]');

            allLists.forEach((item) => {
                if (item !== list) {
                    item.classList.add('hidden');
                }
            });

            list.classList.toggle('hidden');
            return;
        }

        const option = event.target.closest('[data-dropdown-option]');
        if (option) {
            const parent = option.closest('[data-dropdown]');
            const list = parent.querySelector('[data-dropdown-list]');
            const valueSpan = parent.querySelector('[data-dropdown-value]');
            const hiddenInput = parent.querySelector('[data-dropdown-hidden]');
            const selectedVal = option.getAttribute('data-value');
            const selectedText = option.textContent.trim();

            if (valueSpan) {
                valueSpan.textContent = selectedText;
                valueSpan.setAttribute('data-value', selectedVal);
            }
            if (hiddenInput) {
                hiddenInput.value = selectedVal;
            }

            list.classList.add('hidden');
            triggerFilter();
            return;
        }

        if (!event.target.closest('[data-dropdown]')) {
            document.querySelectorAll('[data-dropdown-list]').forEach((item) => {
                item.classList.add('hidden');
            });
        }
    });

    document.addEventListener('click', function (event) {
        const paginationLink = event.target.closest('.ajax-pagination');
        if (!paginationLink) {
            return;
        }

        event.preventDefault();
        const href = paginationLink.getAttribute('href');
        if (!href || href === '#') return;

        const urlObj = new URL(href, window.location.origin);
        const pageNum = urlObj.searchParams.get('page_products') || urlObj.searchParams.get('page') || 1;

        const filterForm = getFilterForm();
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);
        
        params.set('page_products', pageNum);

        const actionUrl = filterForm
            ? (filterForm.getAttribute('action') || (window.location.pathname.startsWith('/katalog') ? '/katalog/saring' : '/produk/saring'))
            : (window.location.pathname.startsWith('/katalog') ? '/katalog/saring' : '/produk/saring');
        fetchProducts(`${actionUrl}?${params.toString()}`);
    });
}
