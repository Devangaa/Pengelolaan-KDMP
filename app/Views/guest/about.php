<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<section class="bg-white py-12 sm:py-20 lg:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-12 lg:grid-cols-12 lg:gap-8">
            <div class="lg:col-span-6">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-red-600">Tentang Koperasi</p>
                <h1 class="mt-6 text-3xl font-bold tracking-tight text-stone-900 sm:text-4xl lg:text-5xl lg:leading-tight">
                    Membangun Ekonomi Desa, Memajukan Kesejahteraan
                </h1>
                <p class="mt-6 text-lg leading-8 text-stone-600">
                    Koperasi Desa Merah Putih hadir sebagai pilar ekonomi lokal yang menjembatani kebutuhan pokok warga dan pemasaran produk unggulan desa secara transparan, mandiri, dan berdaya saing.
                </p>
                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="<?= base_url('products') ?>" class="rounded-2xl bg-red-600 px-6 py-3.5 text-sm font-semibold text-white shadow-md shadow-red-600/20 transition duration-200 hover:bg-red-700">
                        Lihat Katalog Produk
                    </a>
                    <a href="#visi-misi" class="rounded-2xl border border-stone-200 bg-white px-6 py-3.5 text-sm font-semibold text-stone-700 transition duration-200 hover:border-stone-300 hover:bg-stone-50">
                        Pelajari Lebih Lanjut
                    </a>
                </div>

                <div class="mt-12 grid grid-cols-3 gap-6 border-t border-stone-100 pt-8">
                    <div>
                        <p class="text-2xl font-black tracking-tight text-stone-900 lg:text-3xl">150+</p>
                        <p class="mt-1 text-xs font-medium uppercase tracking-wider text-stone-500">Produk Desa</p>
                    </div>
                    <div>
                        <p class="text-2xl font-black tracking-tight text-stone-900 lg:text-3xl">1,2K</p>
                        <p class="mt-1 text-xs font-medium uppercase tracking-wider text-stone-500">Anggota Aktif</p>
                    </div>
                    <div>
                        <p class="text-2xl font-black tracking-tight text-stone-900 lg:text-3xl">100%</p>
                        <p class="mt-1 text-xs font-medium uppercase tracking-wider text-stone-500">Milik Warga</p>
                    </div>
                </div>
            </div>

            <div class="hidden lg:block lg:col-span-6">
                <div class="mx-auto grid max-w-lg grid-cols-2 gap-4 sm:gap-5">
                    <div class="overflow-hidden rounded-3xl bg-stone-100 shadow-sm transition hover:shadow-md">
                        <img src="<?= base_url('assets/images/hero.webp') ?>" 
                            alt="Aktivitas Koperasi" 
                            class="h-full w-full object-cover">
                    </div>
                    <div class="overflow-hidden rounded-3xl bg-stone-100 shadow-sm transition hover:shadow-md">
                        <img src="<?= base_url('assets/images/about-2.webp') ?>" 
                            alt="Komoditas Lokal" 
                            class="aspect-[4/5] w-full object-cover">
                    </div>
                    <div class="col-span-2 overflow-hidden rounded-3xl bg-stone-100 shadow-sm transition hover:shadow-md">
                        <img src="<?= base_url('assets/images/about-3.webp') ?>" 
                            alt="Kemasan Produk Desa" 
                            class="aspect-[16/8] w-full object-cover">
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<section id="visi-misi" class="bg-stone-50 py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-2">
            <div class="rounded-3xl border border-stone-200 bg-white p-8 shadow-sm">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-red-600">Visi</p>
                <p class="mt-4 text-xl font-semibold text-stone-900">
                    Menjadi koperasi desa modern yang mandiri, terpercaya, dan berdaya saing dalam menyejahterakan masyarakat lokal.
                </p>
            </div>

            <div class="rounded-3xl border border-stone-200 bg-white p-8 shadow-sm">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-red-600">Misi</p>
                <ul class="mt-4 space-y-4 text-stone-600">
                    <li class="flex gap-3">
                        <span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-red-600"></span>
                        Menyediakan kebutuhan pokok berkualitas tinggi dengan harga yang terjangkau bagi warga.
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-red-600"></span>
                        Membantu mempublikasikan dan mempromosikan produk UMKM serta hasil tani warga desa.
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-red-600"></span>
                        Mengembangkan sistem katalog produk yang modern, cepat, dan mudah diakses dari mana saja.
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-red-600"></span>
                        Menjaga semangat kekeluargaan dan gotong royong dalam setiap kegiatan operasional.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="bg-stone-50 py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-stone-200 bg-white p-8 shadow-sm sm:p-10">
            <div class="text-center">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-red-600">Nilai Utama</p>
                <h2 class="mt-2 text-2xl font-bold text-stone-900 sm:text-3xl">Prinsip & Keunggulan Kami</h2>
            </div>
            
            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-stone-100 bg-stone-50/60 p-6 shadow-sm transition hover:border-red-200 hover:bg-white hover:shadow-md">
                    <p class="font-bold text-stone-900">Gotong Royong</p>
                    <p class="mt-2 text-sm text-stone-600">Dari warga, oleh warga, dan untuk kesejahteraan masyarakat desa.</p>
                </div>
                <div class="rounded-2xl border border-stone-100 bg-stone-50/60 p-6 shadow-sm transition hover:border-red-200 hover:bg-white hover:shadow-md">
                    <p class="font-bold text-stone-900">Kualitas Terjamin</p>
                    <p class="mt-2 text-sm text-stone-600">Produk pilihan langsung dari petani dan pelaku UMKM lokal terpercaya.</p>
                </div>
                <div class="rounded-2xl border border-stone-100 bg-stone-50/60 p-6 shadow-sm transition hover:border-red-200 hover:bg-white hover:shadow-md">
                    <p class="font-bold text-stone-900">Pemberdayaan Lokal</p>
                    <p class="mt-2 text-sm text-stone-600">Mengutamakan komoditas dan produk olahan hasil karya warga desa sendiri.</p>
                </div>
                <div class="rounded-2xl border border-stone-100 bg-stone-50/60 p-6 shadow-sm transition hover:border-red-200 hover:bg-white hover:shadow-md">
                    <p class="font-bold text-stone-900">Katalog Digital</p>
                    <p class="mt-2 text-sm text-stone-600">Kemudahan mengecek ketersediaan stok produk dan harga secara real-time.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-white py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-red-600">Struktur Organisasi</p>
            <h2 class="mt-2 text-2xl font-bold text-stone-900 sm:text-3xl">Pengurus & Tim Koperasi</h2>
            <p class="mt-2 text-sm text-stone-500">Mengenal lebih dekat tim pengelola Koperasi Desa Merah Putih.</p>
        </div>

        <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            <div class="group rounded-3xl border border-stone-200 bg-white p-6 text-center shadow-sm transition hover:border-red-200 hover:shadow-md">
                <div class="mx-auto h-24 w-24 overflow-hidden rounded-full border-4 border-red-500/20 shadow-inner group-hover:border-red-600 transition">
                    <img src="<?= base_url('assets/images/staff/ketua.webp') ?>" alt="Bambang Sugianto" class="h-full w-full object-cover">
                </div>
                <h3 class="mt-4 font-bold text-stone-900 text-lg">Bambang Sugianto</h3>
                <p class="mt-0.5 text-xs font-semibold uppercase tracking-wider text-red-600">Ketua Koperasi</p>
            </div>

            <div class="group rounded-3xl border border-stone-200 bg-white p-6 text-center shadow-sm transition hover:border-red-200 hover:shadow-md">
                <div class="mx-auto h-24 w-24 overflow-hidden rounded-full border-4 border-stone-200 shadow-inner group-hover:border-red-400 transition">
                    <img src="<?= base_url('assets/images/staff/sekretaris.webp') ?>" alt="Siti Rahmawati" class="h-full w-full object-cover">
                </div>
                <h3 class="mt-4 font-bold text-stone-900 text-lg">Siti Rahmawati</h3>
                <p class="mt-0.5 text-xs font-semibold uppercase tracking-wider text-stone-500">Sekretaris</p>
            </div>

            <div class="group rounded-3xl border border-stone-200 bg-white p-6 text-center shadow-sm transition hover:border-red-200 hover:shadow-md">
                <div class="mx-auto h-24 w-24 overflow-hidden rounded-full border-4 border-stone-200 shadow-inner group-hover:border-red-400 transition">
                    <img src="<?= base_url('assets/images/staff/bendahara.webp') ?>" alt="Dewi Lestari" class="h-full w-full object-cover">
                </div>
                <h3 class="mt-4 font-bold text-stone-900 text-lg">Dewi Lestari</h3>
                <p class="mt-0.5 text-xs font-semibold uppercase tracking-wider text-stone-500">Bendahara</p>
            </div>

            <div class="group rounded-3xl border border-stone-200 bg-white p-6 text-center shadow-sm transition hover:border-red-200 hover:shadow-md">
                <div class="mx-auto h-24 w-24 overflow-hidden rounded-full border-4 border-stone-200 shadow-inner group-hover:border-red-400 transition">
                    <img src="<?= base_url('assets/images/staff/pengelola-operasional.webp') ?>" alt="Eko Prasetyo" class="h-full w-full object-cover">
                </div>
                <h3 class="mt-4 font-bold text-stone-900 text-lg">Eko Prasetyo</h3>
                <p class="mt-0.5 text-xs font-semibold uppercase tracking-wider text-stone-500">Pengelola Operasional</p>
            </div>

        </div>

        <div class="mt-12 rounded-3xl border border-stone-200 bg-stone-50/60 p-6 sm:p-8">
            <p class="text-center text-xs font-semibold uppercase tracking-wider text-stone-400 mb-6">Staf & Tim Pelaksana</p>
            
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                
                <div class="flex items-center gap-4 rounded-2xl bg-white p-4 border border-stone-100 shadow-sm">
                    <img src="<?= base_url('assets/images/staff/staff-toko.webp') ?>" alt="Rina Kusuma" class="h-14 w-14 rounded-full object-cover border border-stone-200 shrink-0">
                    <div>
                        <h4 class="font-bold text-stone-900">Rina Kusuma</h4>
                        <p class="text-xs text-stone-500">Staf Toko & Pelayanan</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 rounded-2xl bg-white p-4 border border-stone-100 shadow-sm">
                    <img src="<?= base_url('assets/images/staff/staff-gudang.webp') ?>" alt="Budi Santoso" class="h-14 w-14 rounded-full object-cover border border-stone-200 shrink-0">
                    <div>
                        <h4 class="font-bold text-stone-900">Budi Santoso</h4>
                        <p class="text-xs text-stone-500">Staf Gudang & Logistik</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 rounded-2xl bg-white p-4 border border-stone-100 shadow-sm sm:col-span-2 lg:col-span-1">
                    <img src="<?= base_url('assets/images/staff/staff-admin.webp') ?>" alt="Anisa Putri" class="h-14 w-14 rounded-full object-cover border border-stone-200 shrink-0">
                    <div>
                        <h4 class="font-bold text-stone-900">Anisa Putri</h4>
                        <p class="text-xs text-stone-500">Staf Admin & Katalog Produk</p>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

<section id="layanan-lokasi" class="bg-stone-50 py-12 sm:py-16">
    <div class="mx-auto max-w-7xl px-3 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-stone-200 bg-white p-4 sm:p-8 lg:p-10 shadow-sm">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between border-b border-stone-100 pb-6 sm:pb-8">
                <div>
                    <p class="text-xs sm:text-sm font-semibold uppercase tracking-[0.2em] text-red-600">Layanan & Lokasi</p>
                    <h2 class="mt-2 text-xl sm:text-3xl font-bold text-stone-900">Kunjungi Toko & Hubungi Kami</h2>
                    <p class="mt-1 text-xs sm:text-sm text-stone-500">Kami siap melayani kebutuhan informasi dan transaksi warga desa.</p>
                </div>
                <div class="flex items-center gap-3 sm:gap-4 rounded-2xl border border-red-100 bg-red-50/60 p-3.5 sm:p-5">
                    <div class="flex h-10 w-10 sm:h-12 sm:w-12 shrink-0 items-center justify-center rounded-xl bg-red-600 text-white shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-red-700">Jam Operasional</p>
                        <p class="text-sm sm:text-base font-semibold text-stone-900 leading-tight mt-0.5">Senin – Sabtu: 08.00 – 16.00 WIB</p>
                        <p class="text-[11px] sm:text-xs text-stone-500 mt-0.5">Minggu & Hari Libur: <span class="font-medium text-red-600">Tutup</span></p>
                    </div>
                </div>
            </div>
            <div class="mt-6 sm:mt-8 grid gap-4 sm:gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <a href="#" target="_blank" rel="noopener noreferrer" class="group flex flex-col justify-between rounded-2xl border border-stone-100 bg-stone-50/50 p-4 sm:p-6 transition hover:border-red-200 hover:bg-white hover:shadow-md">
                    <div class="flex items-start gap-3 sm:gap-4">
                        <div class="flex h-9 w-9 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg bg-stone-200/60 text-stone-700 transition group-hover:bg-red-100 group-hover:text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-stone-500">Alamat Toko</p>
                            <p class="mt-0.5 text-sm sm:text-base font-semibold text-stone-900 leading-snug">Jl. Raya Merah Putih No. 123</p>
                            <p class="text-xs text-stone-500 leading-tight mt-0.5">Desa Merah Putih, Kec. Makmur</p>
                        </div>
                    </div>
                </a>
                <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" class="group flex flex-col justify-between rounded-2xl border border-stone-100 bg-stone-50/50 p-4 sm:p-6 transition hover:border-emerald-300 hover:bg-emerald-50/30 hover:shadow-md">
                    <div class="flex items-start gap-3 sm:gap-4">
                        <div class="flex h-9 w-9 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg bg-stone-200/60 text-stone-700 transition group-hover:bg-emerald-500 group-hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-stone-500">WhatsApp / Telepon</p>
                            <p class="mt-0.5 text-sm sm:text-base font-semibold text-stone-900 group-hover:text-emerald-700 leading-snug">+62 812-3456-7890</p>
                            <p class="text-xs text-emerald-600 font-medium mt-0.5 inline-flex items-center gap-1">
                                Chat WhatsApp &rarr;
                            </p>
                        </div>
                    </div>
                </a>
                <a href="mailto:info@koperasimerahputih.des.id" class="group flex flex-col justify-between rounded-2xl border border-stone-100 bg-stone-50/50 p-4 sm:p-6 transition hover:border-red-200 hover:bg-white hover:shadow-md sm:col-span-2 lg:col-span-1">
                    <div class="flex items-start gap-3 sm:gap-4">
                        <div class="flex h-9 w-9 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg bg-stone-200/60 text-stone-700 transition group-hover:bg-red-100 group-hover:text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-stone-500">Email Resmi</p>
                            <p class="mt-0.5 text-sm sm:text-base font-semibold text-stone-900 break-all leading-snug">info@koperasimerahputih.des.id</p>
                            <p class="text-xs text-stone-500 mt-0.5">Kirim pesan email</p>
                        </div>
                    </div>
                </a>

            </div>

        </div>
    </div>
</section>

<?= $this->endSection(); ?>