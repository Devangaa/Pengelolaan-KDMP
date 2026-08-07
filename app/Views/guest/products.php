<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
    <div class="flex flex-col gap-8">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-red-600">Katalog Produk</p>
            <h1 class="mt-3 text-3xl font-semibold text-stone-900 sm:text-4xl">Temukan kebutuhan pokok favorit Anda</h1>
            <p class="mt-4 text-lg text-stone-600">
                Jelajahi produk unggulan koperasi dengan filter cepat berdasarkan popularitas, abjad, harga, dan kategori.
            </p>
        </div>

        <?php
        $sortLabel = 'Terlaris';
        if (($selectedSort ?? '') === 'name_asc') $sortLabel = 'A-Z';
        if (($selectedSort ?? '') === 'name_desc') $sortLabel = 'Z-A';
        if (($selectedSort ?? '') === 'price_asc') $sortLabel = 'Termurah';
        if (($selectedSort ?? '') === 'price_desc') $sortLabel = 'Termahal';

        $categoryLabel = 'Semua Kategori';
        if (!empty($selectedCategory)) {
            foreach ($categories as $c) {
                if (($selectedCategory ?? '') == $c['id']) {
                    $categoryLabel = $c['name'];
                    break;
                }
            }
        }
        ?>

        <form id="filter-form" action="<?= base_url('products/filter') ?>" method="GET">
            <div class="rounded-2xl border border-red-100 bg-white p-4 shadow-sm sm:p-6">
                <div class="space-y-4">
                    <!-- Search Bar -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-stone-700">Cari Produk</label>
                        <div class="relative">
                            <input 
                                id="productSearch" 
                                name="q" 
                                data-product-search 
                                type="text" 
                                value="<?= esc($searchKeyword ?? '') ?>" 
                                placeholder="Cari nama produk..." 
                                class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-2.5 pr-10 text-sm text-stone-700 shadow-sm focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-100" 
                            />
                            <button type="button" data-search-clear class="<?= empty($searchKeyword) ? 'hidden' : 'flex' ?> absolute inset-y-0 right-3 items-center justify-center text-stone-500 transition hover:text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="grid gap-4 lg:grid-cols-2">
                        <!-- Dropdown Sort -->
                        <div class="min-w-[180px] w-full">
                            <label class="mb-2 block text-sm font-medium text-stone-700">Urutkan</label>
                            <div class="relative" data-dropdown data-dropdown-name="sort">
                                <!-- Hidden Input untuk menyimpan nilai sort agar terbaca oleh FormData -->
                                <input type="hidden" name="sort" data-dropdown-hidden value="<?= esc($selectedSort ?? 'popular') ?>">

                                <button type="button" data-dropdown-toggle data-dropdown-name="sort" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 pr-10 text-sm font-medium text-stone-700 shadow-sm text-left">
                                    <span data-dropdown-value data-value="<?= esc($selectedSort ?? 'popular') ?>"><?= esc($sortLabel) ?></span>
                                    <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-stone-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </span>
                                </button>
                                <ul data-dropdown-list class="hidden absolute left-0 right-0 mt-2 z-50 max-h-60 overflow-auto rounded-xl border bg-white shadow-sm">
                                    <li data-dropdown-option data-value="popular" class="px-3 py-2 cursor-pointer hover:bg-stone-50">Terlaris</li>
                                    <li data-dropdown-option data-value="name_asc" class="px-3 py-2 cursor-pointer hover:bg-stone-50">A-Z</li>
                                    <li data-dropdown-option data-value="name_desc" class="px-3 py-2 cursor-pointer hover:bg-stone-50">Z-A</li>
                                    <li data-dropdown-option data-value="price_asc" class="px-3 py-2 cursor-pointer hover:bg-stone-50">Termurah</li>
                                    <li data-dropdown-option data-value="price_desc" class="px-3 py-2 cursor-pointer hover:bg-stone-50">Termahal</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Dropdown Category -->
                        <div class="min-w-[220px] w-full">
                            <label class="mb-2 block text-sm font-medium text-stone-700">Kategori</label>
                            <div class="relative" data-dropdown data-dropdown-name="category">
                                <!-- Hidden Input untuk menyimpan ID kategori agar terbaca oleh FormData -->
                                <input type="hidden" name="category" data-dropdown-hidden value="<?= esc($selectedCategory ?? '') ?>">

                                <button type="button" data-dropdown-toggle data-dropdown-name="category" class="w-full rounded-xl border border-stone-200 bg-white px-3 py-2.5 pr-10 text-sm font-medium text-stone-700 shadow-sm text-left">
                                    <span data-dropdown-value data-value="<?= esc($selectedCategory ?? '') ?>"><?= esc($categoryLabel) ?></span>
                                    <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-stone-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </span>
                                </button>
                                <ul data-dropdown-list class="hidden absolute left-0 right-0 mt-2 z-50 max-h-60 overflow-auto rounded-xl border bg-white shadow-sm">
                                    <li data-dropdown-option data-value="" class="px-3 py-2 cursor-pointer hover:bg-stone-50">Semua Kategori</li>
                                    <?php foreach ($categories as $category): ?>
                                        <li data-dropdown-option data-value="<?= esc($category['id']) ?>" class="px-3 py-2 cursor-pointer hover:bg-stone-50"><?= esc($category['name']) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div id="product-loader" class="hidden rounded-lg border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700">
            Memuat produk...
        </div>

        <div id="product-list-container" class="mt-2">
            <?= view('guest/partials/product_list', ['products' => $products]) ?>
        </div>
    </div>
</section>

<?= $this->endSection(); ?>
