<div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
    <?php if (!empty($products) && is_array($products)): ?>
        <?php foreach ($products as $product): ?>
            <div class="flex h-full flex-col overflow-hidden rounded-2xl border border-red-100 bg-stone-50 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div class="h-48 overflow-hidden bg-red-50">
                    <img
                        src="<?= !empty($product['image']) ? base_url('uploads/products/' . $product['image']) : 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=800&q=80' ?>"
                        alt="<?= esc($product['name'] ?? 'Produk koperasi') ?>"
                        class="h-full w-full object-cover"
                    >
                </div>

                <div class="flex flex-1 flex-col p-5">
                    <div class="flex items-center justify-between gap-2">
                        <span class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">
                            <?= esc($product['category_name'] ?? 'Umum') ?>
                        </span>
                        <span class="text-xs font-medium text-stone-500">Stok <?= esc($product['stock'] ?? 0) ?></span>
                    </div>

                    <h3 class="mt-4 text-lg font-semibold text-stone-900">
                        <?= esc($product['name'] ?? 'Produk') ?>
                    </h3>
                    <p class="mt-2 text-sm leading-6 text-stone-600">
                        <?= esc($product['description'] ?? 'Produk berkualitas dari koperasi desa.') ?>
                    </p>

                    <div class="mt-auto pt-5">
                        <p class="text-lg font-semibold text-red-600">Rp <?= number_format($product['sell_price'] ?? 0, 0, ',', '.') ?></p>
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
