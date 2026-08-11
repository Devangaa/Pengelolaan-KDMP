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

        <div id="product-section">
            <?= view('partials/product_list', [
                'categories' => $categories,
                'selectedCategory' => $selectedCategory,
                'searchKeyword' => $searchKeyword,
                'selectedSort' => $selectedSort,
                'products' => $products,
                'pager' => $pager,
            ]) ?>
        </div>
    </div>
</section>

<?= $this->endSection(); ?>
