document.addEventListener('DOMContentLoaded', function () {
    const storageKey = 'kdmp-pos-cart';
    const products = Array.isArray(window.posProducts) ? window.posProducts : [];
    const productMap = new Map(products.map((product) => [String(product.id), product]));
    const productGrid = document.getElementById('productGrid');
    const cartItems = document.getElementById('cartItems');
    const cartItemsMobile = document.getElementById('cartItemsMobile');
    const cartSubtotal = document.getElementById('cartSubtotal');
    const cartTotal = document.getElementById('cartTotal');
    const drawerCartTotal = document.getElementById('drawerCartTotal');
    const mobileCartCount = document.getElementById('mobileCartCount');
    const mobileCartTotal = document.getElementById('mobileCartTotal');
    const paymentTotal = document.getElementById('paymentTotal');
    const paymentModal = document.getElementById('paymentModal');
    const cartDrawer = document.getElementById('cartDrawer');
    const openCartDrawerButton = document.getElementById('openCartDrawer');
    const closeCartDrawerButton = document.getElementById('closeCartDrawer');
    const checkoutButtonMobile = document.getElementById('checkoutButtonMobile');
    const clearCartButtonMobile = document.getElementById('clearCartButtonMobile');
    const cartDrawerPanel = document.getElementById('cartDrawerPanel');
    const startShiftModal = document.getElementById('startShiftModal');
    const endShiftModal = document.getElementById('endShiftModal');
    const searchProduct = document.getElementById('searchProduct');
    const endShiftButton = document.getElementById('endShiftButton');
    const cashInput = document.getElementById('cashInput');
    const changePreview = document.getElementById('changePreview');
    const csrfName = document.querySelector('meta[name="csrf-name"]')?.getAttribute('content');
    const csrfValue = document.querySelector('meta[name="csrf-value"]')?.getAttribute('content');

    const formatCurrency = (value) => new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(value || 0));

    function loadCart() {
        try {
            const raw = localStorage.getItem(storageKey);
            return raw ? JSON.parse(raw) : [];
        } catch (error) {
            return [];
        }
    }

    function saveCart(cart) {
        localStorage.setItem(storageKey, JSON.stringify(cart));
    }

    function getCart() {
        return loadCart();
    }

    function getTotalCart(cart) {
        return cart.reduce((sum, item) => sum + (Number(item.qty) * Number(item.price || 0)), 0);
    }

    function getProductCartQty(productId) {
        const cart = getCart();
        const item = cart.find((entry) => String(entry.id) === String(productId));
        return Number(item?.qty || 0);
    }

    function canIncreaseProduct(productId) {
        const product = productMap.get(String(productId));
        const stock = Number(product?.stock || 0);
        const cartQty = getProductCartQty(productId);
        return stock > 0 && cartQty < stock;
    }

    function renderProducts() {
        if (!productGrid) {
            return;
        }

        const keyword = (searchProduct?.value || '').trim().toLowerCase();
        const filteredProducts = products.filter((product) => {
            const stock = Number(product.stock || 0);
            const name = (product.name || '').toLowerCase();
            return stock > 0 && name.includes(keyword);
        });

        if (!filteredProducts.length) {
            productGrid.innerHTML = `
                <div class="col-span-full rounded-2xl border border-dashed border-stone-200 bg-stone-50 p-8 text-center text-sm text-stone-500">
                    Produk yang dicari tidak ditemukan.
                </div>
            `;
            return;
        }

        productGrid.innerHTML = filteredProducts.map((product) => {
            const stock = Number(product.stock || 0);
            const price = Number(product.sell_price || 0);
            const cartQty = getProductCartQty(product.id);
            const isMaxReached = cartQty >= stock;

            return `
                <button type="button" class="group rounded-3xl border border-red-100 bg-white text-left shadow-sm transition ${isMaxReached ? 'cursor-not-allowed opacity-60' : 'hover:-translate-y-0.5 hover:shadow-md'}" data-product-id="${product.id}" ${isMaxReached ? 'disabled' : ''}>
                    <div class="relative h-32 overflow-hidden bg-red-50">
                        <img src="${product.image ? '/uploads/products/' + product.image : '/assets/images/product-placeholder.webp'}" alt="${product.name}" class="h-full w-full object-cover">
                    </div>
                    <div class="space-y-3 p-3">
                        <div class="flex items-center justify-between gap-2">
                            <span class="rounded-full bg-red-50 px-2 py-0.5 text-[10px] font-semibold text-red-700">${product.category_name || 'Umum'}</span>
                            <span class="text-[10px] font-medium text-stone-600">Stok ${stock}</span>
                        </div>
                        <h3 class="line-clamp-2 text-sm font-semibold text-stone-900">${product.name}</h3>
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-sm font-bold text-red-600">${formatCurrency(price)}</span>
                        </div>
                    </div>
                </button>
            `;
        }).join('');
    }

    function renderCart() {
        const cart = getCart();
        const total = getTotalCart(cart);
        const itemCount = cart.reduce((sum, item) => sum + Number(item.qty || 0), 0);

        if (mobileCartCount) {
            mobileCartCount.textContent = `${itemCount} item${itemCount === 1 ? '' : ''}`;
        }
        if (mobileCartTotal) {
            mobileCartTotal.textContent = formatCurrency(total);
        }
        if (drawerCartTotal) {
            drawerCartTotal.textContent = formatCurrency(total);
        }

        if (!cart.length) {
            const emptyCartMarkup = `
                <div class="rounded-2xl border border-dashed border-stone-200 bg-stone-50 px-4 py-10 text-center text-sm text-stone-500">
                    Keranjang masih kosong.
                </div>
            `;

            if (cartItems) {
                cartItems.innerHTML = emptyCartMarkup;
            }
            if (cartItemsMobile) {
                cartItemsMobile.innerHTML = emptyCartMarkup;
            }
            if (cartSubtotal) {
                cartSubtotal.textContent = formatCurrency(0);
            }
            cartTotal.textContent = formatCurrency(0);
            paymentTotal.textContent = formatCurrency(0);
            return;
        }

        const cartMarkup = cart.map((item) => {
            const product = productMap.get(String(item.id));
            const stock = Number(product?.stock || 0);
            const lineTotal = Number(item.qty || 0) * Number(item.price || 0);
            const maxReached = stock > 0 && Number(item.qty || 0) >= stock;
            return `
                <div class="rounded-2xl border border-stone-200 bg-stone-50 p-3">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-stone-900">${product?.name || item.name || 'Produk'}</p>
                            <p class="mt-1 text-xs text-stone-500">${formatCurrency(item.price || 0)} / pcs</p>
                        </div>
                        <button type="button" class="remove-item text-stone-400 transition hover:text-red-600" data-product-id="${item.id}">
                            <span class="material-icons text-base">close</span>
                        </button>
                    </div>
                    <div class="mt-3 flex items-center justify-between gap-3">
                        <div class="inline-flex items-center rounded-full border border-stone-200 bg-white">
                            <button type="button" class="qty-button px-2 py-1 text-stone-600 transition hover:text-stone-900" data-action="decrease" data-product-id="${item.id}">-</button>
                            <input type="number" min="1" max="${stock || 1}" value="${item.qty}" class="w-12 border-0 bg-transparent text-center text-sm font-semibold text-stone-800 outline-none qty-input" data-product-id="${item.id}">
                            <button type="button" class="qty-button px-2 py-1 text-stone-600 ${maxReached ? 'cursor-not-allowed opacity-40' : 'transition hover:text-stone-900'}" data-action="increase" data-product-id="${item.id}" ${maxReached ? 'disabled' : ''}>+</button>
                        </div>
                        <strong class="text-sm font-bold text-stone-900">${formatCurrency(lineTotal)}</strong>
                    </div>
                </div>
            `;
        }).join('');

        if (cartItems) {
            cartItems.innerHTML = cartMarkup;
        }
        if (cartItemsMobile) {
            cartItemsMobile.innerHTML = cartMarkup;
        }

        if (cartSubtotal) {
            cartSubtotal.textContent = formatCurrency(total);
        }
        cartTotal.textContent = formatCurrency(total);
        paymentTotal.textContent = formatCurrency(total);
    }

    function addToCart(productId) {
        const product = productMap.get(String(productId));
        if (!product) {
            return;
        }

        const stock = Number(product.stock || 0);
        const cart = getCart();
        const index = cart.findIndex((item) => String(item.id) === String(productId));
        const currentQty = index >= 0 ? Number(cart[index].qty || 0) : 0;

        if (stock <= 0 || currentQty >= stock) {
            showToast('error', 'Stok produk sudah maksimal atau tidak tersedia.');
            return;
        }

        if (index >= 0) {
            cart[index].qty = currentQty + 1;
        } else {
            cart.push({
                id: product.id,
                name: product.name,
                price: Number(product.sell_price || 0),
                qty: 1,
            });
        }

        saveCart(cart);
        renderCart();
        renderProducts();
    }

    function updateQty(productId, nextQty) {
        const cart = getCart();
        const itemIndex = cart.findIndex((item) => String(item.id) === String(productId));
        if (itemIndex < 0) {
            return;
        }

        const product = productMap.get(String(productId));
        const stock = Number(product?.stock || 0);
        const safeQty = Math.max(1, Number(nextQty || 1));
        const nextSafeQty = stock > 0 ? Math.min(safeQty, stock) : safeQty;

        if (stock > 0 && nextSafeQty < safeQty) {
            showToast('error', 'Jumlah pembelian tidak bisa melebihi stok yang tersedia.');
        }

        cart[itemIndex].qty = nextSafeQty;
        saveCart(cart);
        renderCart();
        renderProducts();
    }

    function removeFromCart(productId) {
        const cart = getCart().filter((item) => String(item.id) !== String(productId));
        saveCart(cart);
        renderCart();
        renderProducts();
    }

    function clearCart() {
        localStorage.removeItem(storageKey);
        renderCart();
        renderProducts();
    }

    function showToast(type, message) {
        const wrapper = document.createElement('div');
        wrapper.id = type === 'success' ? 'success-notification' : 'error-notification';
        wrapper.setAttribute('role', type === 'success' ? 'status' : 'alert');
        wrapper.setAttribute('aria-live', 'assertive');
        wrapper.className = 'fixed inset-x-4 top-4 z-50 mx-auto max-w-xl rounded-3xl border px-4 py-4 shadow-2xl backdrop-blur-sm sm:left-auto sm:right-4 sm:max-w-md transition-opacity duration-300 ' + (type === 'success' ? 'border-emerald-200 bg-emerald-50 text-emerald-950 shadow-emerald-950/10' : 'border-red-200 bg-red-50 text-red-950 shadow-red-950/10');

        const icon = type === 'success'
            ? '<div class="mt-0.5 flex h-9 w-9 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></div>'
            : '<div class="mt-0.5 flex h-9 w-9 items-center justify-center rounded-2xl bg-red-100 text-red-700"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" /></svg></div>';

        wrapper.innerHTML = `
            <div class="flex items-start gap-3">
                ${icon}
                <div class="min-w-0">
                    <p class="text-sm font-semibold">${type === 'success' ? 'Sukses' : 'Perhatian'}</p>
                    <p class="mt-1 text-sm leading-6 ${type === 'success' ? 'text-emerald-900' : 'text-red-900'}">${message}</p>
                </div>
            </div>
        `;

        document.body.appendChild(wrapper);
        setTimeout(() => {
            wrapper.classList.add('opacity-0', 'pointer-events-none');
            setTimeout(() => wrapper.remove(), 300);
        }, 3000);
    }

    function togglePaymentModal(show) {
        paymentModal.classList.toggle('hidden', !show);
        paymentModal.classList.toggle('flex', show);
    }

    function updatePreviewChange() {
        const total = getTotalCart(getCart());
        const paymentValue = Number(cashInput?.value || 0);
        const base = Number(paymentValue > 0 ? paymentValue : 0);
        changePreview.textContent = formatCurrency(Math.max(0, base - total));
    }

    function showPaymentSuccess(invoice, change) {
        const modal = document.getElementById('paymentSuccessModal');
        const invoiceEl = document.getElementById('successInvoice');
        const changeEl = document.getElementById('successChange');

        if (!modal || !invoiceEl || !changeEl) {
            return;
        }

        invoiceEl.textContent = invoice;
        changeEl.textContent = formatCurrency(change || 0);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    productGrid?.addEventListener('click', (event) => {
        const button = event.target.closest('[data-product-id]');
        if (!button) {
            return;
        }
        addToCart(button.getAttribute('data-product-id'));
    });

    searchProduct?.addEventListener('input', renderProducts);

    const handleCartActions = (event) => {
        const removeButton = event.target.closest('.remove-item');
        if (removeButton) {
            removeFromCart(removeButton.getAttribute('data-product-id'));
            return;
        }

        const qtyButton = event.target.closest('.qty-button');
        if (!qtyButton) {
            return;
        }

        const productId = qtyButton.getAttribute('data-product-id');
        const cart = getCart();
        const currentItem = cart.find((item) => String(item.id) === String(productId));
        if (!currentItem) {
            return;
        }

        const nextQty = qtyButton.getAttribute('data-action') === 'increase'
            ? Number(currentItem.qty || 1) + 1
            : Number(currentItem.qty || 1) - 1;

        updateQty(productId, Math.max(1, nextQty));
    };

    const handleCartInput = (event) => {
        const input = event.target.closest('.qty-input');
        if (!input) {
            return;
        }

        const productId = input.getAttribute('data-product-id');
        const nextQty = Number(input.value || 1);
        updateQty(productId, nextQty);
    };

    cartItems?.addEventListener('click', handleCartActions);
    cartItemsMobile?.addEventListener('click', handleCartActions);
    cartItems?.addEventListener('input', handleCartInput);
    cartItemsMobile?.addEventListener('input', handleCartInput);

    document.getElementById('clearCartButton')?.addEventListener('click', () => {
        const cart = getCart();
        if (!cart.length) {
            return;
        }

        clearCart();
    });

    clearCartButtonMobile?.addEventListener('click', () => {
        const cart = getCart();
        if (!cart.length) {
            return;
        }

        clearCart();
        closeCartDrawer();
    });

    function openCartDrawer() {
        if (!cartDrawer || !cartDrawerPanel) {
            return;
        }

        cartDrawer.classList.remove('hidden');
        requestAnimationFrame(() => {
            cartDrawerPanel.classList.remove('translate-x-full');
        });
    }

    function closeCartDrawer() {
        if (!cartDrawer || !cartDrawerPanel) {
            return;
        }

        cartDrawerPanel.classList.add('translate-x-full');
        setTimeout(() => {
            cartDrawer.classList.add('hidden');
        }, 300);
    }

    document.getElementById('checkoutButton')?.addEventListener('click', () => {
        if (!getCart().length) {
            showToast('error', 'Keranjang masih kosong.');
            return;
        }
        togglePaymentModal(true);
        updatePreviewChange();
    });

    checkoutButtonMobile?.addEventListener('click', () => {
        if (!getCart().length) {
            showToast('error', 'Keranjang masih kosong.');
            return;
        }
        closeCartDrawer();
        togglePaymentModal(true);
        updatePreviewChange();
    });

    openCartDrawerButton?.addEventListener('click', openCartDrawer);
    closeCartDrawerButton?.addEventListener('click', closeCartDrawer);
    cartDrawer?.addEventListener('click', (event) => {
        if (event.target === cartDrawer) {
            closeCartDrawer();
        }
    });

    document.querySelector('[data-close-payment]')?.addEventListener('click', () => togglePaymentModal(false));
    cashInput?.addEventListener('input', updatePreviewChange);

    document.getElementById('paymentForm')?.addEventListener('submit', async (event) => {
        event.preventDefault();
        const cart = getCart();

        if (!cart.length) {
            showToast('error', 'Keranjang masih kosong.');
            return;
        }

        const formData = new FormData(event.currentTarget);
        const payload = {
            cart,
            payment_type: formData.get('payment_type') || 'tunai',
            cash: Number(formData.get('cash') || 0),
        };

        const response = await fetch('pos/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfValue || '',
                'X-CSRF-Name': csrfName || 'csrf_test_name',
            },
            body: JSON.stringify(payload),
        });

        const result = await response.json();

        if (!result.success) {
            showToast('error', result.message || 'Transaksi gagal diproses.');
            return;
        }

        localStorage.removeItem(storageKey);
        renderCart();
        renderProducts();
        togglePaymentModal(false);
        document.getElementById('paymentForm').reset();
        showPaymentSuccess(result.invoice, result.change);
    });

    endShiftButton?.addEventListener('click', () => {
        if (!shiftState.active) {
            showToast('error', 'Sesi belum aktif. Silakan buka shift terlebih dahulu.');
            return;
        }
        endShiftModal.classList.remove('hidden');
        endShiftModal.classList.add('flex');
    });

    document.getElementById('closeSuccessModal')?.addEventListener('click', () => {
        const modal = document.getElementById('paymentSuccessModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    });

    document.addEventListener('click', (event) => {
        const target = event.target;
        if (target === endShiftModal) {
            endShiftModal.classList.add('hidden');
            endShiftModal.classList.remove('flex');
        }
        if (target === paymentModal) {
            togglePaymentModal(false);
        }
        if (target === document.getElementById('paymentSuccessModal')) {
            document.getElementById('paymentSuccessModal').classList.add('hidden');
            document.getElementById('paymentSuccessModal').classList.remove('flex');
        }
    });

    if (!shiftState.active && startShiftModal) {
        startShiftModal.classList.remove('hidden');
        startShiftModal.classList.add('flex');
    }

    renderProducts();
    renderCart();
    updatePreviewChange();
});
