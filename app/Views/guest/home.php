<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
    <div class="grid items-center gap-12 lg:grid-cols-2">
        <div class="max-w-2xl">
            <h1 class="text-4xl font-bold leading-tight text-stone-900 sm:text-5xl lg:text-6xl">
                Membangun Ekonomi Desa Bersama <span class="text-red-600">Koperasi Merah Putih</span>
            </h1>
            <p class="mt-6 text-lg leading-8 text-stone-600">
                Sistem manajemen terpadu yang memadukan semangat gotong royong dengan teknologi modern. Kami hadir untuk memastikan kebutuhan pokok masyarakat tersedia dengan harga terjangkau dan proses yang transparan.
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="<?= base_url('products') ?>" class="rounded-lg bg-red-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                    Lihat Katalog Produk
                </a>
                <a href="#produk-unggulan" class="rounded-lg border border-red-200 bg-white px-6 py-3 text-sm font-semibold text-red-700 transition hover:border-red-300 hover:bg-red-50">
                    Lihat Produk Unggulan
                </a>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-2xl border border-stone-200 bg-white p-4 shadow-sm">
            <img src="<?= base_url('assets/images/hero.webp') ?>" 
                alt="Koperasi Merah Putih" 
                class="h-[420px] w-full rounded-xl object-cover transition-all duration-300 ease-in-out group-hover:scale-110 group-hover:rounded-none">
        </div>
    </div>
</section>

<section id="produk-unggulan" class="border-t border-red-100 bg-white py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-red-600">Produk unggulan</p>
                <h2 class="mt-2 text-3xl font-semibold text-stone-900">Katalog Produk Unggulan</h2>
                <p class="mt-3 max-w-2xl text-stone-600">
                    Kebutuhan pokok dengan kualitas terbaik dan harga terjangkau untuk seluruh anggota masyarakat.
                </p>
            </div>

            <a href="<?= base_url('products') ?>" class="inline-flex w-fit items-center justify-center self-start rounded-full border border-red-200 bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700 sm:self-auto">
                Lihat Semua
            </a>
        </div>

        <div id="product-list" class="mt-8">
            <?= view('guest/partials/product_carousel', ['products' => $featuredProducts]) ?>
        </div>
    </div>
</section>

<?= $this->endSection(); ?>