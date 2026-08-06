    <header class="sticky top-0 z-50 border-b border-red-200 bg-white/95 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <?php $uri = service('uri')->getPath(); $firstSegment = explode('/', trim($uri, '/'))[0] ?? ''; ?>
            <a href="<?= base_url('/') ?>" class="flex items-center gap-3">
                <img src="<?= base_url('assets/images/logo.webp') ?>" alt="Logo Koperasi Merah Putih" class="h-10 w-10 rounded-full object-cover">
                <div>
                    <p class="text-xl font-semibold text-stone-900">Koperasi Merah Putih</p>
                </div>
            </a>

            <div class="flex items-center gap-8">
                <nav class="hidden items-center gap-8 text-sm font-medium md:flex">
                    <a href="<?= base_url('/') ?>" class="transition <?= url_is('/') ? 'text-red-600 font-semibold' : 'text-stone-600 hover:text-red-600' ?>">Beranda</a>
                    <a href="<?= base_url('products') ?>" class="transition <?= url_is('products*') ? 'text-red-600 font-semibold' : 'text-stone-600 hover:text-red-600' ?>">Katalog</a>
                    <a href="<?= base_url('about') ?>" class="transition <?= url_is('about*') ? 'text-red-600 font-semibold' : 'text-stone-600 hover:text-red-600' ?>">Tentang Kami</a>
                </nav>

                <button type="button" class="inline-flex items-center justify-center rounded-lg border border-red-200 p-2 text-red-600 transition hover:bg-red-50 md:hidden" aria-label="Buka menu" data-mobile-menu-toggle>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <a href="<?= base_url('products') ?>" class="hidden rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700 md:inline-flex">
                    Masuk
                </a>
            </div>
        </div>

        <div class="border-t border-red-100 bg-white px-4 py-0 shadow-sm transition-all duration-300 ease-in-out md:hidden max-h-0 overflow-hidden" data-mobile-menu>
            <nav class="flex flex-col gap-3 py-4 text-sm font-medium">
                <a href="<?= base_url('/') ?>" class="rounded-lg px-3 py-2 transition <?= url_is('/') ? 'bg-red-50 text-red-600 font-semibold' : 'text-stone-700 hover:bg-red-50 hover:text-red-600' ?>">Beranda</a>
                <a href="<?= base_url('products') ?>" class="rounded-lg px-3 py-2 transition <?= url_is('products*') ? 'bg-red-50 text-red-600 font-semibold' : 'text-stone-700 hover:bg-red-50 hover:text-red-600' ?>">Katalog</a>
                <a href="<?= base_url('about') ?>" class="rounded-lg px-3 py-2 transition <?= url_is('about*') ? 'bg-red-50 text-red-600 font-semibold' : 'text-stone-700 hover:bg-red-50 hover:text-red-600' ?>">Tentang Kami</a>
                <a href="<?= base_url('products') ?>" class="mt-2 inline-flex items-center justify-center rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700">
                    Masuk
                </a>
            </nav>
        </div>
    </header>
