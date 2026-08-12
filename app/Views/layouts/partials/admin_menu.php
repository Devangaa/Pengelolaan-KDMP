<script>
    try {
        if (localStorage.getItem('kdmp-admin-sidebar-collapsed') === '1') {
            document.documentElement.classList.add('kdmp-sidebar-collapsed');
        }
    } catch (error) {
    }
</script>
<?php $uri = uri_string(); ?>

<div id="sidebar-backdrop" class="fixed inset-0 z-40 hidden bg-stone-900/50 lg:hidden"></div>

<aside id="admin-sidebar" class="flex w-72 flex-col border-r border-stone-200 bg-white shadow-sm transition-all duration-300">
    <div class="flex flex-1 flex-col overflow-hidden overflow-x-hidden">
        <div id="admin-sidebar-header" class="flex items-center gap-3 border-b border-stone-200 px-4 py-5">
            <div id="sidebar-logo" class="relative h-12 w-12 flex-shrink-0" role="button" aria-label="Toggle sidebar" tabindex="0">
                <img src="<?= base_url('assets/images/logo.webp') ?>" alt="Logo KDMP" class="h-12 w-12 rounded-full object-cover transition-opacity duration-200 group-hover:opacity-0">
                <span id="sidebar-logo-icon" class="pointer-events-none absolute inset-0 hidden items-center justify-center opacity-0 transition-opacity duration-200 group-hover:opacity-100">
                    <span class="material-icons flex h-10 w-10 items-center justify-center rounded-full border border-stone-200 bg-red-50 text-base text-red-600">chevron_right</span>
                </span>
            </div>
            <div class="sidebar-brand-text min-w-0">
                <p class="truncate text-base font-semibold text-stone-900">KDMP</p>
            </div>
            <button id="sidebar-toggle" type="button" class="ml-auto flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full border border-stone-200 bg-stone-50 text-stone-600 transition hover:bg-red-50 hover:text-red-600" aria-label="Toggle sidebar">
                <span class="material-icons text-base">chevron_left</span>
            </button>
            <button id="sidebar-mobile-close" type="button" class="ml-auto flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full border border-stone-200 bg-stone-50 text-stone-600 transition hover:bg-red-50 hover:text-red-600 lg:hidden" aria-label="Tutup menu" aria-controls="admin-sidebar">
                <span class="material-icons text-base">close</span>
            </button>
        </div>

        <nav id="admin-nav" class="flex-1 space-y-1 overflow-y-auto overflow-x-hidden px-4 py-5">
            <a href="<?= base_url('dasbor') ?>" class="sidebar-link group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium <?= (strpos($uri, 'dasbor') === 0 ? 'text-red-600 bg-red-50' : 'text-stone-700') ?> transition hover:bg-red-50 hover:text-red-600">
                <span class="material-icons flex w-6 flex-shrink-0 justify-center text-base transition-transform duration-200 group-hover:scale-110 <?= (strpos($uri, 'dasbor') === 0 ? 'scale-110 text-red-600' : '') ?>">dashboard</span>
                <span class="sidebar-label whitespace-nowrap transition-opacity duration-200 ease-out">Dashboard</span>
            </a>
            <a href="<?= base_url('admin/products') ?>" class="sidebar-link group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium <?= (strpos($uri, 'admin/products') === 0 ? 'text-red-600 bg-red-50' : 'text-stone-700') ?> transition hover:bg-red-50 hover:text-red-600">
                <span class="material-icons flex w-6 flex-shrink-0 justify-center text-base transition-transform duration-200 group-hover:scale-110 <?= (strpos($uri, 'admin/products') === 0 ? 'scale-110 text-red-600' : '') ?>">inventory_2</span>
                <span class="sidebar-label whitespace-nowrap transition-opacity duration-200 ease-out">Manajemen Produk</span>
            </a>
            <a href="<?= base_url('admin/stocks') ?>" class="sidebar-link group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium <?= (strpos($uri, 'admin/stocks') === 0 ? 'text-red-600 bg-red-50' : 'text-stone-700') ?> transition hover:bg-red-50 hover:text-red-600">
                <span class="material-icons flex w-6 flex-shrink-0 justify-center text-base transition-transform duration-200 group-hover:scale-110 <?= (strpos($uri, 'admin/stocks') === 0 ? 'scale-110 text-red-600' : '') ?>">warehouse</span>
                <span class="sidebar-label whitespace-nowrap transition-opacity duration-200 ease-out">Manajemen Stok</span>
            </a>
            <a href="<?= base_url('admin/members') ?>" class="sidebar-link group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium <?= (strpos($uri, 'admin/members') === 0 ? 'text-red-600 bg-red-50' : 'text-stone-700') ?> transition hover:bg-red-50 hover:text-red-600">
                <span class="material-icons flex w-6 flex-shrink-0 justify-center text-base transition-transform duration-200 group-hover:scale-110 <?= (strpos($uri, 'admin/members') === 0 ? 'scale-110 text-red-600' : '') ?>">group</span>
                <span class="sidebar-label whitespace-nowrap transition-opacity duration-200 ease-out">Manajemen Anggota</span>
            </a>
            <a href="<?= base_url('admin/cashiers') ?>" class="sidebar-link group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium <?= (strpos($uri, 'admin/cashiers') === 0 ? 'text-red-600 bg-red-50' : 'text-stone-700') ?> transition hover:bg-red-50 hover:text-red-600">
                <span class="material-icons flex w-6 flex-shrink-0 justify-center text-base transition-transform duration-200 group-hover:scale-110 <?= (strpos($uri, 'admin/cashiers') === 0 ? 'scale-110 text-red-600' : '') ?>">account_circle</span>
                <span class="sidebar-label whitespace-nowrap transition-opacity duration-200 ease-out">Manajemen Kasir</span>
            </a>
            <a href="<?= base_url('admin/reports') ?>" class="sidebar-link group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium <?= (strpos($uri, 'admin/reports') === 0 ? 'text-red-600 bg-red-50' : 'text-stone-700') ?> transition hover:bg-red-50 hover:text-red-600">
                <span class="material-icons flex w-6 flex-shrink-0 justify-center text-base transition-transform duration-200 group-hover:scale-110 <?= (strpos($uri, 'admin/reports') === 0 ? 'scale-110 text-red-600' : '') ?>">bar_chart</span>
                <span class="sidebar-label whitespace-nowrap transition-opacity duration-200 ease-out">Laporan</span>
            </a>
        </nav>
    </div>

    <div id="sidebar-profile" class="relative border-t border-stone-200 px-4 py-4">
        <button id="profile-trigger" type="button" class="flex w-full items-center gap-3 rounded-lg py-2 pl-1 pr-3 text-left transition hover:bg-red-50" aria-haspopup="true" aria-expanded="false" aria-controls="profile-menu">
            <?php if (!empty($admin['avatar'])): ?>
                <img src="<?= base_url('uploads/avatar/admin/' . esc($admin['avatar'])) ?>" alt="Foto profil" class="h-10 w-10 max-w-none flex-shrink-0 rounded-full object-cover">
            <?php else: ?>
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-stone-100 text-stone-500">
                    <span class="material-icons text-2xl">person</span>
                </div>
            <?php endif; ?>
            <span class="profile-text flex min-w-0 flex-1 flex-col overflow-hidden">
                <span class="truncate text-sm font-semibold text-stone-900"><?= esc($admin['name'] ?? 'Admin') ?></span>
                <span class="truncate text-xs text-stone-500"><?= esc($admin['email'] ?? 'admin@kdmp.id') ?></span>
            </span>
            <span id="profile-chevron" class="material-icons profile-text flex-shrink-0 text-base text-stone-400 transition-transform duration-200">expand_more</span>
        </button>

        <div id="profile-menu" class="hidden overflow-hidden rounded-lg border border-stone-200 bg-white shadow-lg">
            <a href="<?= base_url('admin/profile') ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-stone-700 transition hover:bg-red-50 hover:text-red-600">
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