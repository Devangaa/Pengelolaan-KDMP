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

<div>
    <form id="filter-form" action="<?= isset($filterAction) ? $filterAction : base_url('produk/saring') ?>" method="GET">
        <div class="rounded-2xl border border-red-100 bg-white p-4 shadow-sm sm:p-6">
        <div class="space-y-4">
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
                <div class="min-w-[180px] w-full">
                    <label class="mb-2 block text-sm font-medium text-stone-700">Urutkan</label>
                    <div class="relative" data-dropdown data-dropdown-name="sort">
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

                <div class="min-w-[220px] w-full">
                    <label class="mb-2 block text-sm font-medium text-stone-700">Kategori</label>
                    <div class="relative" data-dropdown data-dropdown-name="category">
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

<div id="product-list-container" class="mt-8">
    <div class="grid grid-cols-2 gap-3 md:grid-cols-4 md:gap-4 xl:grid-cols-5 xl:gap-6">
        <?php if (!empty($products) && is_array($products)): ?>
            <?php foreach ($products as $product): ?>
                <?php $isOutOfStock = ((int) ($product['stock'] ?? 0) <= 0); ?>
                <div class="flex h-full flex-col overflow-hidden rounded-2xl border shadow-sm transition hover:-translate-y-1 hover:shadow-md <?= $isOutOfStock ? 'border-stone-300 bg-stone-100' : 'border-red-100 bg-stone-50' ?>">
                    <div class="aspect-square w-full overflow-hidden bg-red-50">
                        <img
                            src="<?= !empty($product['image']) ? base_url('uploads/products/' . $product['image']) : base_url('assets/images/product-placeholder.webp') ?>"
                            alt="<?= esc($product['name'] ?? 'Produk koperasi') ?>"
                            class="h-full w-full object-cover <?= $isOutOfStock ? 'grayscale opacity-70' : '' ?>"
                        >
                    </div>

                    <div class="flex flex-1 flex-col p-3.5 sm:p-5">
                        <div class="flex items-center justify-between gap-1 sm:gap-2">
                            <span class="truncate rounded-full px-2 py-0.5 text-[10px] font-semibold sm:px-2.5 sm:py-1 sm:text-xs <?= $isOutOfStock ? 'bg-stone-200 text-stone-500' : 'bg-red-100 text-red-700' ?>">
                                <?= esc($product['category_name'] ?? 'Umum') ?>
                            </span>
                            <span class="text-[10px] font-medium sm:text-xs <?= $isOutOfStock ? 'text-red-500 font-semibold' : 'text-stone-500' ?>">
                                <?= ($product['stock'] ?? 0) <= 0 ? 'Habis' : 'Stok ' . esc($product['stock']) ?>
                            </span>
                        </div>

                        <h3 class="mt-2 text-sm font-semibold line-clamp-1 sm:mt-4 sm:text-lg <?= $isOutOfStock ? 'text-stone-500' : 'text-stone-900' ?>">
                            <?= esc($product['name'] ?? 'Produk') ?>
                        </h3>

                        <div class="mt-auto pt-3 sm:pt-5">
                            <p class="text-sm font-semibold sm:text-lg <?= $isOutOfStock ? 'text-stone-500' : 'text-red-600' ?>">
                                Rp <?= number_format($product['sell_price'] ?? 0, 0, ',', '.') ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-2 rounded-2xl border border-dashed border-red-200 bg-red-50 p-8 text-center text-sm text-stone-600 md:col-span-4 xl:col-span-5">
                Belum ada produk yang cocok untuk ditampilkan.
            </div>
        <?php endif; ?>
    </div>

    <?php if (isset($pager) && $pager->getPageCount('products') > 1): ?>
        <div class="mt-10 flex justify-center">
            <?= $pager->links('products', 'tailwind') ?>
        </div>
    <?php endif; ?>
</div>
</div>