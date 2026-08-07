function onReady(fn) {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', fn);
    } else {
        fn();
    }
}

onReady(function () {
    initMobileMenuToggle();
    initHomeCarousel();
    initProductPage();
});

function initMobileMenuToggle() {
    const mobileToggle = document.querySelector('[data-mobile-menu-toggle]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');

    if (!mobileToggle || !mobileMenu) {
        return;
    }

    mobileToggle.addEventListener('click', function () {
        const isClosed = mobileMenu.classList.contains('max-h-0');

        if (isClosed) {
            mobileMenu.classList.remove('max-h-0');
            mobileMenu.classList.add('max-h-80');
        } else {
            mobileMenu.classList.remove('max-h-80');
            mobileMenu.classList.add('max-h-0');
        }
    });
}

function initHomeCarousel() {
    const swiperElement = document.querySelector('.product-swiper');
    if (!swiperElement || typeof Swiper === 'undefined') {
        return;
    }

    new Swiper(swiperElement, {
        slidesPerView: 2,
        slidesPerGroup: 2,
        spaceBetween: 12,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            640: {
                slidesPerView: 3,
                slidesPerGroup: 3,
                spaceBetween: 16,
            },
            1024: {
                slidesPerView: 4,
                slidesPerGroup: 4,
                spaceBetween: 24,
            },
            1280: {
                slidesPerView: 5,
                slidesPerGroup: 5,
                spaceBetween: 24,
            },
        },
    });
}

function initProductPage() {
    const productContainer = document.getElementById('product-list-container');
    const filterForm = document.getElementById('filter-form');
    const searchInput = document.querySelector('[data-product-search]');
    const clearSearchBtn = document.querySelector('[data-search-clear]');

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

    function setSearchClearButton() {
        if (!searchInput || !clearSearchBtn) {
            return;
        }

        const hasValue = searchInput.value.trim() !== '';
        clearSearchBtn.classList.toggle('hidden', !hasValue);
        clearSearchBtn.classList.toggle('flex', hasValue);
    }

    function fetchProducts(url) {
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
                productContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
            })
            .catch((err) => {
                console.error('Error filter:', err);
            })
            .finally(() => {
                productContainer.classList.remove('opacity-50', 'pointer-events-none');
            });
    }

    function triggerFilter() {
        if (!filterForm) {
            return;
        }

        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);
        const actionUrl = filterForm.getAttribute('action') || '/products/filter';

        fetchProducts(`${actionUrl}?${params.toString()}`);
    }

    if (filterForm) {
        filterForm.addEventListener('change', function () {
            triggerFilter();
        });

        filterForm.addEventListener('submit', function (event) {
            event.preventDefault();
            triggerFilter();
        });
    }

    if (searchInput) {
        setSearchClearButton();

        searchInput.addEventListener('input', function () {
            setSearchClearButton();
        });

        searchInput.addEventListener('input', debounce(function () {
            triggerFilter();
        }, 400));
    }

    if (clearSearchBtn && searchInput) {
        clearSearchBtn.addEventListener('click', function () {
            searchInput.value = '';
            setSearchClearButton();
            triggerFilter();
        });
    }

    document.addEventListener('click', function (event) {
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

        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);
        
        params.set('page_products', pageNum);

        const actionUrl = filterForm ? (filterForm.getAttribute('action') || '/products/filter') : '/products/filter';
        fetchProducts(`${actionUrl}?${params.toString()}`);
    });
}

