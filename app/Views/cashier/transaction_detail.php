<?= $this->extend('layouts/cashier') ?>

<?= $this->section('title') ?>Detail Transaksi<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <section class="rounded-3xl border border-stone-200 bg-gradient-to-r from-white via-stone-50 to-white p-6 shadow-sm">
        <div class="grid gap-6 lg:grid-cols-[1.5fr_1fr] lg:items-center">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-red-600">Detail Transaksi</p>
                <h1 class="mt-3 text-3xl font-semibold tracking-tight text-stone-900 sm:text-4xl">Detail Transaksi Kasir</h1>
                <p class="mt-4 max-w-2xl text-stone-600">Lihat ringkasan pembayaran, barang yang dibeli, dan status transaksi dengan tampilan kasir yang konsisten.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                <a href="<?= base_url('transaksi') ?>" class="inline-flex items-center justify-center rounded-full border border-stone-200 bg-white px-5 py-3 text-sm font-semibold text-stone-700 transition hover:bg-stone-100">
                    <span class="material-icons">arrow_back</span>
                    <span>Kembali</span>
                </a>
                <?php if (empty($printMode)): ?>
                    <a href="<?= base_url('transaksi/' . esc($transaction['transaction_id']) . '/nota') ?>" class="inline-flex items-center justify-center gap-2 rounded-full bg-red-600 px-5 py-3 text-sm font-semibold text-white shadow-sm shadow-red-200 transition hover:bg-red-700">
                        <span class="material-icons">download</span>
                        <span>Unduh Struk</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="mt-6 grid gap-4 lg:grid-cols-2">
            <div class="rounded-3xl border border-stone-200 bg-stone-50 p-5">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-stone-500">No. Struk</p>
                <p class="mt-2 text-lg font-semibold text-stone-900"><?= esc($transaction['transaction_id']) ?></p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-stone-50 p-5">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-stone-500">Waktu</p>
                <p class="mt-2 text-lg font-semibold text-stone-900"><?= date('d/m/Y H:i', strtotime($transaction['created_at'])) ?></p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-stone-50 p-5">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-stone-500">Nama Member</p>
                <p class="mt-2 text-lg font-semibold text-stone-900"><?= esc($transaction['member_name'] ?? '-') ?></p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-stone-50 p-5">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-stone-500">Tipe Pembayaran</p>
                <p class="mt-2 text-lg font-semibold text-stone-900 capitalize"><?= esc($transaction['payment_type']) ?></p>
            </div>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-stone-200 text-sm">
                <thead class="bg-stone-50 text-left text-xs uppercase tracking-[0.24em] text-stone-500">
                    <tr>
                        <th class="px-4 py-3">Produk</th>
                        <th class="px-4 py-3">Harga</th>
                        <th class="px-4 py-3">Jumlah</th>
                        <th class="px-4 py-3">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200 bg-white text-stone-700">
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td class="px-4 py-4"><?= esc($item['product_name'] ?? '-') ?></td>
                                <td class="px-4 py-4">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                                <td class="px-4 py-4"><?= esc($item['quantity']) ?></td>
                                <td class="px-4 py-4">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td class="px-4 py-6 text-center text-sm text-stone-500" colspan="4">Detail produk tidak tersedia.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-6 grid gap-4 lg:grid-cols-3">
            <div class="rounded-3xl border border-stone-200 bg-stone-50 p-5">
                <p class="text-sm text-stone-500">Total Belanja</p>
                <p class="mt-2 text-xl font-semibold text-stone-900">Rp <?= number_format($transaction['total'] ?? 0, 0, ',', '.') ?></p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-stone-50 p-5">
                <p class="text-sm text-stone-500">Bayar</p>
                <p class="mt-2 text-xl font-semibold text-stone-900">Rp <?= number_format($transaction['pay'] ?? 0, 0, ',', '.') ?></p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-stone-50 p-5">
                <p class="text-sm text-stone-500">Kembalian</p>
                <p class="mt-2 text-xl font-semibold text-stone-900">Rp <?= number_format($transaction['change'] ?? 0, 0, ',', '.') ?></p>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
