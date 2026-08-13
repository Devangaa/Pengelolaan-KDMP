<div class="overflow-x-auto">
    <table class="min-w-[760px] w-full divide-y divide-stone-200 text-sm">
        <thead class="bg-stone-50 text-left text-xs uppercase tracking-[0.24em] text-stone-500">
            <tr>
                <th class="px-4 py-3">Waktu Buka</th>
                <th class="px-4 py-3">Waktu Tutup</th>
                <th class="px-4 py-3">Uang Kasir Awal</th>
                <th class="px-4 py-3">Uang Kasir Akhir</th>
                <th class="px-4 py-3">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-200 bg-white text-stone-700">
            <?php if (!empty($shifts)): ?>
                <?php foreach ($shifts as $shiftItem): ?>
                    <tr>
                        <td class="px-4 py-4"><?= !empty($shiftItem['opened_at']) ? date('d/m/Y H:i', strtotime($shiftItem['opened_at'])) : '-' ?></td>
                        <td class="px-4 py-4"><?= !empty($shiftItem['closed_at']) ? date('d/m/Y H:i', strtotime($shiftItem['closed_at'])) : '-' ?></td>
                        <td class="px-4 py-4">Rp <?= number_format((float) ($shiftItem['modal_awal'] ?? 0), 0, ',', '.') ?></td>
                        <td class="px-4 py-4">Rp <?= number_format((float) ($shiftItem['uang_fisik'] ?? 0), 0, ',', '.') ?></td>
                        <td class="px-4 py-4">
                            <?php if (!empty($shiftItem['closed_at'])): ?>
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">Tutup</span>
                            <?php else: ?>
                                <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">Aktif</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td class="px-4 py-6 text-center text-sm text-stone-500" colspan="5">Belum ada riwayat shift kasir.</td>
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
