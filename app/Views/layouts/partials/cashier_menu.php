<script>
    try {
        if (localStorage.getItem('kdmp-cashier-sidebar-collapsed') === '1') {
            document.documentElement.classList.add('kdmp-sidebar-collapsed');
        }
    } catch (error) {
    }
</script>

<div id="cashier-sidebar-backdrop" class="fixed inset-0 z-40 hidden bg-stone-900/50 lg:hidden"></div>

<aside id="cashier-sidebar" class="flex w-72 flex-col border-r border-stone-200 bg-white shadow-sm transition-all duration-300 overflow-x-hidden">
    <div class="flex flex-1 flex-col overflow-hidden overflow-x-hidden">
        <div id="cashier-sidebar-header" class="flex items-center gap-3 border-b border-stone-200 px-4 py-5">
            <div id="cashier-sidebar-logo" class="relative h-12 w-12 flex-shrink-0" role="button" aria-label="Toggle sidebar" tabindex="0">
                <img src="<?= base_url('assets/images/logo.webp') ?>" alt="Logo KDMP" class="h-12 w-12 rounded-full object-cover transition-opacity duration-200 group-hover:opacity-0">
                <span id="cashier-sidebar-logo-icon" class="pointer-events-none absolute inset-0 hidden items-center justify-center opacity-0 transition-opacity duration-200 group-hover:opacity-100">
                    <span class="material-icons flex h-10 w-10 items-center justify-center rounded-full border border-stone-200 bg-red-50 text-base text-red-600">chevron_right</span>
                </span>
            </div>
            <div class="sidebar-brand-text min-w-0">
                <p class="truncate text-base font-semibold text-stone-900">KDMP</p>
            </div>
            <button id="cashier-sidebar-toggle" type="button" class="ml-auto flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full border border-stone-200 bg-stone-50 text-stone-600 transition hover:bg-red-50 hover:text-red-600" aria-label="Toggle sidebar">
                <span class="material-icons text-base">chevron_left</span>
            </button>
            <button id="cashier-sidebar-mobile-close" type="button" class="ml-auto flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full border border-stone-200 bg-stone-50 text-stone-600 transition hover:bg-red-50 hover:text-red-600 lg:hidden" aria-label="Tutup menu" aria-controls="cashier-sidebar">
                <span class="material-icons text-base">close</span>
            </button>
        </div>

        <nav id="cashier-nav" class="flex-1 space-y-1 overflow-y-auto overflow-x-hidden px-4 py-5">
            <a href="<?= base_url('dasbor') ?>" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-stone-700 transition hover:bg-red-50 hover:text-red-600">
                <span class="material-icons flex w-6 flex-shrink-0 justify-center text-base">dashboard</span>
                <span class="sidebar-label whitespace-nowrap transition-opacity duration-200 ease-out">Dashboard</span>
            </a>
            <a href="<?= base_url('katalog') ?>" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-stone-700 transition hover:bg-red-50 hover:text-red-600">
                <span class="material-icons flex w-6 flex-shrink-0 justify-center text-base">inventory</span>
                <span class="sidebar-label whitespace-nowrap transition-opacity duration-200 ease-out">Katalog Produk</span>
            </a>
            <a href="<?= base_url('cashier/reports') ?>" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-stone-700 transition hover:bg-red-50 hover:text-red-600">
                <span class="material-icons flex w-6 flex-shrink-0 justify-center text-base">description</span>
                <span class="sidebar-label whitespace-nowrap transition-opacity duration-200 ease-out">Riwayat Transaksi</span>
            </a>
            <a href="<?= base_url('cashier/rekap_shift') ?>" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-stone-700 transition hover:bg-red-50 hover:text-red-600">
                <span class="material-icons flex w-6 flex-shrink-0 justify-center text-base">query_stats</span>
                <span class="sidebar-label whitespace-nowrap transition-opacity duration-200 ease-out">Rekap Shift</span>
            </a>
        </nav>
    </div>

    <div id="cashier-pos-section" class="border-t border-stone-200 px-4 py-4">
        <a href="<?= base_url('cashier/pos') ?>" class="sidebar-link flex items-center gap-3 rounded-lg bg-red-600 px-3 py-2.5 text-sm font-medium text-white transition hover:bg-red-700">
            <span class="material-icons flex w-6 flex-shrink-0 justify-center text-base">point_of_sale</span>
            <span class="sidebar-label whitespace-nowrap transition-opacity duration-200 ease-out">Transaksi</span>
        </a>
    </div>

    <div id="cashier-sidebar-profile" class="relative border-t border-stone-200 px-4 py-4">
        <button id="cashier-profile-trigger" type="button" class="flex w-full items-center gap-3 rounded-lg py-2 pl-1 pr-3 text-left transition hover:bg-red-50" aria-haspopup="true" aria-expanded="false" aria-controls="cashier-profile-menu">
            <?php if (!empty($cashier['avatar'])): ?>
                <img src="<?= base_url('uploads/avatar/cashier/' . esc($cashier['avatar'])) ?>" alt="Foto profil" class="h-10 w-10 max-w-none flex-shrink-0 rounded-full object-cover">
            <?php else: ?>
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-stone-100 text-stone-500">
                    <span class="material-icons text-2xl">person</span>
                </div>
            <?php endif; ?>
            <span class="profile-text flex min-w-0 flex-1 flex-col overflow-hidden">
                <span class="truncate text-sm font-semibold text-stone-900"><?= esc($cashier['name'] ?? 'Kasir') ?></span>
                <span class="truncate text-xs text-stone-500"><?= esc($cashier['email'] ?? 'kasir@kdmp.id') ?></span>
            </span>
            <span id="cashier-profile-chevron" class="material-icons profile-text flex-shrink-0 text-base text-stone-400 transition-transform duration-200">expand_more</span>
        </button>

        <div id="cashier-profile-menu" class="hidden overflow-hidden rounded-lg border border-stone-200 bg-white shadow-lg">
            <a href="<?= base_url('cashier/profile') ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-stone-700 transition hover:bg-red-50 hover:text-red-600">
                <span class="material-icons text-base">person</span>
                Profil
            </a>
            <a href="<?= base_url('logout') ?>" class="flex items-center gap-3 border-t border-stone-100 px-4 py-2.5 text-sm font-medium text-stone-700 transition hover:bg-red-50 hover:text-red-600">
                <span class="material-icons text-base">logout</span>
                Keluar
            </a>
        </div>
    </div>
</aside>