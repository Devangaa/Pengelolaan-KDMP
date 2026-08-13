<?= $this->extend('layouts/cashier') ?>

<?= $this->section('title') ?>Dashboard Kasir<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <section class="rounded-3xl border border-stone-200 bg-gradient-to-r from-white via-stone-50 to-white p-6 shadow-sm">
        <div class="grid gap-6 lg:grid-cols-[1.5fr_1fr] lg:items-center">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-red-600">Ringkasan Kasir</p>
                <h1 class="mt-3 text-3xl font-semibold tracking-tight text-stone-900 sm:text-4xl">Dashboard Kasir </h1>
                <p class="mt-4 max-w-2xl text-stone-600">Pantau performa shift kasir dan akses cepat ke mesin kasir.</p>
            </div>
            <div class="hidden md:flex md:flex-wrap md:items-center md:gap-3 md:justify-start lg:justify-end">
                <?php if (!empty($shiftActive)): ?>
                    <a href="<?= base_url('pos') ?>" class="inline-flex items-center justify-center gap-2 rounded-full bg-red-600 px-5 py-3 text-sm font-semibold text-white shadow-sm shadow-red-200 transition hover:bg-red-700">
                        <span class="material-icons">point_of_sale</span>
                        <span>Masuk Mesin Kasir</span>
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('pos') ?>" class="inline-flex items-center justify-center gap-2 rounded-full bg-red-600 px-5 py-3 text-sm font-semibold text-white shadow-sm shadow-red-200 transition hover:bg-red-700">
                        <span class="material-icons">play_arrow</span>
                        <span>Buka Shift</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="mt-8 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <?php if (!empty($shiftActive)): ?>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-emerald-600">Shift Aktif</p>
                        <h2 class="mt-2 text-2xl font-semibold text-stone-900">Aktif sejak <?= esc($shiftStartedAt ?? '00:00') ?> WIB</h2>
                    </div>
                    <div class="rounded-3xl bg-stone-50 px-4 py-3 text-sm font-semibold text-stone-700">
                        Modal Awal: <span class="text-stone-900">Rp <?= number_format($openingBalance ?? 0, 0, ',', '.') ?></span>
                    </div>
                </div>
            <?php else: ?>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-600">Belum Aktif</p>
                        <h2 class="mt-2 text-2xl font-semibold text-stone-900">Shift Belum Aktif</h2>
                    </div>
                    <div class="rounded-3xl bg-stone-50 px-4 py-3 text-sm font-semibold text-stone-700 hidden md:block">
                        Silakan buka shift untuk mulai mencatat transaksi hari ini.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="grid gap-4 xl:grid-cols-4">
        <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <div class="rounded-2xl bg-red-50 p-3 text-red-600">
                    <span class="material-icons">analytics</span>
                </div>
                <span class="rounded-full bg-stone-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-stone-500">Hari ini</span>
            </div>
            <div class="mt-6">
                <p class="text-sm text-stone-500">Total Omset Saya</p>
                <p class="mt-3 text-3xl font-semibold text-stone-900">Rp <?= number_format($totalOmset ?? 0, 0, ',', '.') ?></p>
            </div>
            <p class="mt-4 text-sm text-stone-500">Nominal penjualan oleh Anda hari ini.</p>
        </div>

        <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <div class="rounded-2xl bg-emerald-50 p-3 text-emerald-600">
                    <span class="material-icons">receipt_long</span>
                </div>
                <span class="rounded-full bg-stone-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-stone-500">Hari ini</span>
            </div>
            <div class="mt-6">
                <p class="text-sm text-stone-500">Jumlah Struk Diproses</p>
                <p class="mt-3 text-3xl font-semibold text-stone-900"><?= esc($receiptCount ?? 0) ?></p>
            </div>
            <p class="mt-4 text-sm text-stone-500">Total transaksi yang Anda selesaikan hari ini.</p>
        </div>

        <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <div class="rounded-2xl bg-amber-50 p-3 text-amber-600">
                    <span class="material-icons">payments</span>
                </div>
                <span class="rounded-full bg-stone-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-stone-500">Tunai</span>
            </div>
            <div class="mt-6">
                <p class="text-sm text-stone-500">Transaksi Tunai</p>
                <p class="mt-3 text-3xl font-semibold text-stone-900">Rp <?= number_format($cashTotal ?? 0, 0, ',', '.') ?></p>
            </div>
            <p class="mt-4 text-sm text-stone-500">Perkiraan jumlah uang tunai hari ini.</p>
        </div>

        <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <div class="rounded-2xl bg-sky-50 p-3 text-sky-600">
                    <span class="material-icons">credit_card</span>
                </div>
                <span class="rounded-full bg-stone-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-stone-500">Non-Tunai</span>
            </div>
            <div class="mt-6">
                <p class="text-sm text-stone-500">Transaksi Non-Tunai</p>
                <p class="mt-3 text-3xl font-semibold text-stone-900">Rp <?= number_format($nonCashTotal ?? 0, 0, ',', '.') ?></p>
            </div>
            <p class="mt-4 text-sm text-stone-500">QRIS/transfer hari ini.</p>
        </div>
    </section>

    <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between gap-3 border-b border-stone-100 pb-4">
            <div>
                <h2 class="text-lg font-semibold text-stone-900">Transaksi Hari Ini</h2>
                <p class="text-sm text-stone-500">Aktivitas transaksi terbaru yang Anda proses.</p>
            </div>
            <a href="<?= base_url('transaksi') ?>" class="text-sm font-semibold text-red-600 transition hover:text-red-700">Lihat Semua</a>
        </div>
        <div class="mt-5 h-[26rem] overflow-x-auto overflow-y-auto">
            <table class="min-w-full divide-y divide-stone-200 text-sm">
                <thead class="bg-stone-50 sticky top-0 z-20 text-left text-xs uppercase tracking-[0.24em] text-stone-500">
                    <tr>
                        <th class="px-4 py-3">No. Struk</th>
                        <th class="px-4 py-3">Waktu Transaksi</th>
                        <th class="px-4 py-3">Tipe Pembayaran</th>
                        <th class="px-4 py-3">Total Belanja</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200 bg-white text-stone-700">
                    <?php if (!empty($transactionsToday)): ?>
                        <?php foreach ($transactionsToday as $trx): ?>
                            <tr>
                                <td class="px-4 py-4 font-medium text-stone-900"><?= esc($trx['no_struk']) ?></td>
                                <td class="px-4 py-4"><?= date('H:i', strtotime($trx['created_at'])) ?></td>
                                <td class="px-4 py-4 capitalize"><?= esc($trx['payment_type']) ?></td>
                                <td class="px-4 py-4">Rp <?= number_format($trx['total'], 0, ',', '.') ?></td>
                                <td class="px-4 py-4">
                                    <a href="<?= base_url('transaksi/' . esc($trx['no_struk']) . '/nota') ?>" class="inline-flex items-center justify-center rounded-full bg-red-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-red-700 whitespace-nowrap">Unduh Struk</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td class="px-4 py-6 text-center text-sm text-stone-500" colspan="5">Belum ada transaksi hari ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?= $this->endSection() ?>