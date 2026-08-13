<div class="overflow-x-auto">
    <table class="min-w-[760px] w-full divide-y divide-stone-200 text-sm">
        <thead class="bg-stone-50 text-left text-xs uppercase tracking-[0.24em] text-stone-500">
            <tr>
                <th class="px-4 py-3">No. Struk</th>
                <th class="px-4 py-3">Waktu Transaksi</th>
                <th class="px-4 py-3">Nama Member</th>
                <th class="px-4 py-3">Tipe Pembayaran</th>
                <th class="px-4 py-3">Total Belanja</th>
                <th class="px-4 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-200 bg-white text-stone-700">
            <?php if (!empty($transactions)): ?>
                <?php foreach ($transactions as $trx): ?>
                    <tr>
                        <td class="px-4 py-4 font-medium text-stone-900"><?= esc($trx['no_struk']) ?></td>
                        <td class="px-4 py-4"><?= date('d/m/Y H:i', strtotime($trx['created_at'])) ?></td>
                        <td class="px-4 py-4"><?= esc($trx['member_name'] ?? '-') ?></td>
                        <td class="px-4 py-4 capitalize"><?= esc($trx['payment_type']) ?></td>
                        <td class="px-4 py-4">Rp <?= number_format($trx['total'], 0, ',', '.') ?></td>
                        <td class="px-4 py-4">
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="<?= base_url('transaksi/' . esc($trx['no_struk'])) ?>" class="inline-flex items-center justify-center rounded-full bg-red-50 px-4 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-100 whitespace-nowrap">
                                    <span>Lihat Detail</span>
                                </a>
                                <a href="<?= base_url('transaksi/' . esc($trx['no_struk']) . '/nota') ?>" class="inline-flex items-center justify-center rounded-full bg-red-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-red-700 whitespace-nowrap">
                                    <span>Unduh Struk</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td class="px-4 py-6 text-center text-sm text-stone-500" colspan="6">Belum ada transaksi yang Anda lakukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php if (isset($pager) && $pager->getPageCount('transactions') > 1): ?>
    <div class="mt-6 flex justify-center">
        <?= $pager->links('transactions', 'tailwind') ?>
    </div>
<?php endif; ?>