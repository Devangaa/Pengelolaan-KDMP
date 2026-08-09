<?= $this->extend('layouts/cashier') ?>

<?= $this->section('title') ?>Katalog Produk Kasir<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <section class="rounded-3xl border border-stone-200 bg-gradient-to-r from-white via-stone-50 to-white p-6 shadow-sm">
        <div class="grid gap-6 lg:grid-cols-[1.5fr_1fr] lg:items-center">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-red-600">Katalog Produk</p>
                <h1 class="mt-3 text-3xl font-semibold tracking-tight text-stone-900 sm:text-4xl">Katalog Produk Kasir</h1>
                <p class="mt-4 max-w-2xl text-stone-600">Jelajahi dan cari produk untuk penjualan di mesin kasir.</p>
            </div>
        </div>
    </section>

    <section>
        <div id="product-section">
            <?= view('partials/product_list', [
                'categories' => $categories ?? [],
                'selectedCategory' => $selectedCategory ?? null,
                'searchKeyword' => $searchKeyword ?? null,
                'selectedSort' => $selectedSort ?? 'popular',
                'products' => $products ?? [],
                'pager' => $pager ?? null,
                'filterAction' => base_url('katalog/saring'),
            ]) ?>
        </div>
    </section>
</div>
<?= $this->endSection() ?>