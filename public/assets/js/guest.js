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

    function getCurrentCarouselSlidesPerView() {
        const width = window.innerWidth;

        if (width >= 1280) {
            return 5;
        }

        if (width >= 1024) {
            return 4;
        }

        if (width >= 640) {
            return 3;
        }

        return 2;
    }

    const slidesWrapper = swiperElement.querySelector('.swiper-wrapper');
    const slides = slidesWrapper ? Array.from(slidesWrapper.querySelectorAll('.swiper-slide')) : [];
    const slidesPerView = getCurrentCarouselSlidesPerView();
    const maxItems = 10;
    const visibleCount = Math.floor(Math.min(slides.length, maxItems) / slidesPerView) * slidesPerView;
    const limit = visibleCount > 0 ? visibleCount : slides.length;

    slides.slice(limit).forEach((slide) => slide.remove());

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


