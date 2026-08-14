<?php
$totalProducts = !empty($products) && is_array($products) ? count($products) : 0;

if ($totalProducts > 0) {
    $limit = min($totalProducts, 10);
    $products = array_slice($products, 0, $limit);
}
?>

<div class="swiper product-swiper relative">
    <div class="swiper-wrapper">
        <?php if (!empty($products) && is_array($products)): ?>
            <?php foreach ($products as $product): ?>
                <?php $isOutOfStock = ((int) ($product['stock'] ?? 0) <= 0); ?>
                
                <div class="swiper-slide">
                    <div class="flex h-full w-full flex-col overflow-hidden rounded-2xl border shadow-sm transition hover:-translate-y-1 hover:shadow-md <?= $isOutOfStock ? 'border-stone-300 bg-stone-100' : 'border-red-100 bg-stone-50' ?>">
                        <div class="aspect-square w-full overflow-hidden bg-red-50">
                            <img
                                src="<?= esc(product_image_url($product['image'] ?? null)) ?>"
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
                                    <?= rupiah($product['sell_price'] ?? 0) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="w-full rounded-2xl border border-dashed border-red-200 bg-red-50 p-8 text-center text-sm text-stone-600">
                Belum ada produk yang cocok untuk ditampilkan.
            </div>
        <?php endif; ?>
    </div>

    <div class="swiper-pagination"></div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>