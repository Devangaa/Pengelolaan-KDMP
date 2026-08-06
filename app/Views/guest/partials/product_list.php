<div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
    <?php if (!empty($products) && is_array($products)): ?>
        <?php foreach ($products as $product): ?>
            <?php $isOutOfStock = ((int) ($product['stock'] ?? 0) <= 0); ?>
            <div class="flex h-full flex-col overflow-hidden rounded-2xl border shadow-sm transition hover:-translate-y-1 hover:shadow-md <?= $isOutOfStock ? 'border-stone-300 bg-stone-100' : 'border-red-100 bg-stone-50' ?>">
                <div class="h-48 overflow-hidden bg-red-50">
                    <img
                        src="<?= !empty($product['image']) ? base_url('uploads/products/' . $product['image']) : base_url('assets/images/product-placeholder.webp') ?>"
                        alt="<?= esc($product['name'] ?? 'Produk koperasi') ?>"
                        class="h-full w-full object-cover <?= $isOutOfStock ? 'grayscale opacity-70' : '' ?>"
                    >
                </div>

                <div class="flex flex-1 flex-col p-5">
                    <div class="flex items-center justify-between gap-2">
                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold <?= $isOutOfStock ? 'bg-stone-200 text-stone-500' : 'bg-red-100 text-red-700' ?>">
                            <?= esc($product['category_name'] ?? 'Umum') ?>
                        </span>
                        <span class="text-xs font-medium <?= $isOutOfStock ? 'text-red-500 font-semibold' : 'text-stone-500' ?>">
                            <?= ($product['stock'] ?? 0) <= 0 ? 'Stok Habis' : 'Stok ' . esc($product['stock']) ?>
                        </span>
                    </div>

                    <h3 class="mt-4 text-lg font-semibold <?= $isOutOfStock ? 'text-stone-500' : 'text-stone-900' ?>">
                        <?= esc($product['name'] ?? 'Produk') ?>
                    </h3>
                    <p class="mt-2 text-sm leading-6 <?= $isOutOfStock ? 'text-stone-400' : 'text-stone-600' ?>">
                        <?= esc($product['description'] ?? 'Produk berkualitas dari koperasi desa.') ?>
                    </p>

                    <div class="mt-auto pt-5">
                        <p class="text-lg font-semibold <?= $isOutOfStock ? 'text-stone-500' : 'text-red-600' ?>">Rp <?= number_format($product['sell_price'] ?? 0, 0, ',', '.') ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="rounded-2xl border border-dashed border-red-200 bg-red-50 p-8 text-center text-sm text-stone-600 md:col-span-2 xl:col-span-4">
            Belum ada produk yang cocok untuk ditampilkan.
        </div>
    <?php endif; ?>
</div>
