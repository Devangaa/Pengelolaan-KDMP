<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Dashboard Admin<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="rounded-3xl border border-stone-200 bg-gradient-to-r from-white via-stone-50 to-white p-6 shadow-sm">
        <div class="grid gap-6 lg:grid-cols-[1.5fr_1fr] lg:items-center">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-red-600">Ringkasan Admin</p>
                <h1 class="mt-3 text-3xl font-semibold tracking-tight text-stone-900 sm:text-4xl">Dashboard Koperasi KDMP</h1>
                <p class="mt-4 max-w-2xl text-stone-600">Semua performa penting dan peringatan operasional ditampilkan di sini, agar keputusan bisa diambil lebih cepat.</p>
            </div>
            <div class="flex flex-wrap items-center justify-start gap-3 lg:justify-end">
                <a href="<?= base_url('produk/tambah') ?>" class="inline-flex items-center justify-center gap-2 rounded-full bg-red-600 px-5 py-3 text-sm font-semibold text-white shadow-sm shadow-red-200 transition hover:bg-red-700">
                    <span class="material-icons">add</span>
                    <span>Tambah Produk</span>
                </a>
                <a href="<?= base_url('laporan') ?>" class="inline-flex items-center justify-center rounded-full border border-stone-200 bg-white px-5 py-3 text-sm font-semibold text-stone-700 transition hover:bg-stone-100">Laporan</a>
            </div>
        </div>
    </div>

    <div class="grid gap-4 xl:grid-cols-4">
        <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <div class="rounded-2xl bg-red-50 p-3 text-red-600">
                    <span class="material-icons">analytics</span>
                </div>
                <span class="rounded-full bg-stone-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-stone-500">Hari ini</span>
            </div>
            <div class="mt-6">
                <p class="text-sm text-stone-500">Penjualan</p>
                <p class="mt-3 text-3xl font-semibold text-stone-900">Rp <?= number_format($penjualanHariIni ?? 0, 0, ',', '.') ?></p>
            </div>
            <p class="mt-4 text-sm text-stone-500">Total transaksi hari ini.</p>
        </div>

        <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <div class="rounded-2xl bg-emerald-50 p-3 text-emerald-600">
                    <span class="material-icons">receipt_long</span>
                </div>
                <span class="rounded-full bg-stone-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-stone-500">Hari ini</span>
            </div>
            <div class="mt-6">
                <p class="text-sm text-stone-500">Transaksi</p>
                <p class="mt-3 text-3xl font-semibold text-stone-900"><?= $totalTransaksiHariIni ?? 0 ?></p>
            </div>
            <p class="mt-4 text-sm text-stone-500">Jumlah transaksi hari ini.</p>
        </div>

        <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <div class="rounded-2xl bg-amber-50 p-3 text-amber-600">
                    <span class="material-icons">inventory_2</span>
                </div>
                <span class="rounded-full bg-stone-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-stone-500">Urgent</span>
            </div>
            <div class="mt-6">
                <p class="text-sm text-stone-500">Stok Menipis</p>
                <p class="mt-3 text-3xl font-semibold text-amber-600"><?= $stokMenipisCount ?? 0 ?></p>
            </div>
            <p class="mt-4 text-sm text-stone-500">Produk yang perlu segera ditinjau.</p>
        </div>

        <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <div class="rounded-2xl bg-sky-50 p-3 text-sky-600">
                    <span class="material-icons">group</span>
                </div>
                <span class="rounded-full bg-stone-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-stone-500">Total</span>
            </div>
            <div class="mt-6">
                <p class="text-sm text-stone-500">Anggota</p>
                <p class="mt-3 text-3xl font-semibold text-stone-900"><?= $totalAnggota ?? 0 ?></p>
            </div>
            <p class="mt-4 text-sm text-stone-500">Jumlah anggota terdaftar.</p>
        </div>
    </div>

    <div class="grid gap-4 xl:grid-cols-2">
        <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm h-[28rem] overflow-hidden">
            <div class="flex items-center justify-between gap-3 border-b border-stone-100 pb-4">
                <div>
                    <h2 class="text-lg font-semibold text-stone-900">Peringatan Stok Menipis</h2>
                    <p class="text-sm text-stone-500">Produk yang memerlukan perhatian stok.</p>
                </div>
                <a href="<?= base_url('stok') ?>" class="text-sm font-semibold text-red-600 transition hover:text-red-700">Lihat Semua</a>
            </div>
            <div class="mt-5 h-[22rem] overflow-y-auto pr-1 space-y-3">
                <?php if (!empty($listStokMenipis)): ?>
                    <?php foreach ($listStokMenipis as $item): ?>
                        <?php $isHabis = ((int)$item['stok'] === 0); ?>
                        <div class="rounded-3xl border p-4 transition <?= $isHabis ? 'border-red-200 bg-red-50' : 'border-stone-100 bg-stone-50' ?>">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm font-semibold <?= $isHabis ? 'text-red-700' : 'text-stone-900' ?>">
                                        <?= esc($item['nama_produk']) ?>
                                    </p>
                                    <p class="text-xs <?= $isHabis ? 'text-red-500' : 'text-stone-500' ?>">
                                        <?= esc($item['kategori']) ?>
                                    </p>
                                </div>
                                <div class="rounded-full px-3 py-1 text-sm font-semibold <?= $isHabis ? 'bg-red-600 text-white' : 'bg-amber-100 text-amber-700' ?>">
                                    Stok: <?= esc($item['stok']) ?> <?= $isHabis ? '(Habis)' : '' ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="rounded-3xl border border-stone-100 bg-stone-50 p-6 text-center text-stone-500">Semua stok produk aman 👍</div>
                <?php endif; ?>
            </div>
        </section>

        <section class="flex h-[28rem] flex-col rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3 border-b border-stone-100 pb-4">
                <div>
                    <h2 class="text-lg font-semibold text-stone-900">Transaksi Terbaru</h2>
                    <p class="text-sm text-stone-500">Ringkasan transaksi hari ini.</p>
                </div>
                <a href="<?= base_url('laporan') ?>" class="text-sm font-semibold text-stone-600 transition hover:text-stone-900">Lihat Laporan</a>
            </div>

            <div class="mt-4 flex-1 space-y-3 overflow-y-auto pr-1">
                <?php if (!empty($transaksiTerbaru)): ?>
                    <?php foreach ($transaksiTerbaru as $trx): ?>
                        <div class="rounded-3xl border border-stone-100 bg-stone-50 p-4">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-stone-900">#<?= esc($trx['no_struk']) ?></p>
                                    <p class="text-xs text-stone-500"><?= esc($trx['nama_kasir'] ?? '—') ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-stone-900">Rp <?= number_format($trx['total_harga'], 0, ',', '.') ?></p>
                                    <p class="text-xs text-stone-500"><?= date('H:i', strtotime($trx['created_at'])) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="rounded-3xl border border-stone-100 bg-stone-50 p-6 text-center text-stone-500">Belum ada transaksi hari ini.</div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>
<?= $this->endSection() ?>