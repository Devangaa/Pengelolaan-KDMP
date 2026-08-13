<?= $this->extend('layouts/cashier') ?>

<?= $this->section('title') ?>Rekap Shift Kasir<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <section class="rounded-3xl border border-stone-200 bg-gradient-to-r from-white via-stone-50 to-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-red-600">Rekap Shift</p>
                <h1 class="mt-3 text-3xl font-semibold tracking-tight text-stone-900 sm:text-4xl">Laporan Kasir</h1>
            </div>
        </div>
    </section>

    <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-stone-900">Status Shift Saat Ini</h2>

        <?php if (!empty($shift) && empty($shift['closed_at'])): ?>
            <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-700">
                <p class="font-semibold">Shift aktif</p>
                <p class="mt-1 text-sm">Dibuka pada <?= esc($shift['opened_at'] ?? '-') ?>.</p>
                <p class="mt-1 text-sm">Modal awal: Rp <?= number_format((float) ($shift['modal_awal'] ?? 0), 0, ',', '.') ?></p>
            </div>
        <?php else: ?>
            <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-amber-700">
                <p class="font-semibold">Shift tidak aktif</p>
                <p class="mt-1 text-sm">Belum ada sesi kasir yang dibuka saat ini.</p>
            </div>
        <?php endif; ?>

        <?php if (!empty($shift)): ?>
            <div class="mt-6 grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl bg-stone-50 p-4">
                    <p class="text-sm text-stone-500">Waktu Buka</p>
                    <p class="mt-2 text-lg font-semibold text-stone-900"><?= esc($shift['opened_at'] ?? '-') ?></p>
                </div>
                <div class="rounded-2xl bg-stone-50 p-4">
                    <p class="text-sm text-stone-500">Waktu Tutup</p>
                    <p class="mt-2 text-lg font-semibold text-stone-900"><?= esc($shift['closed_at'] ?? '-') ?></p>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <section class="rounded-2xl border border-red-100 bg-white p-4 shadow-sm sm:p-6">
        <form id="transactionFilterForm" action="<?= base_url('cashier/rekap_shift') ?>" method="GET">
            <div class="space-y-4">
                <div class="grid gap-4 lg:grid-cols-3">
                    <div class="min-w-[180px] w-full">
                        <label class="mb-2 block text-sm font-medium text-stone-700">Urutan</label>
                        <div class="relative" data-dropdown data-dropdown-name="orderBy">
                            <input type="hidden" name="orderBy" data-dropdown-hidden value="<?= esc($orderBy ?? 'latest') ?>">
                            <button type="button" data-dropdown-toggle data-dropdown-name="orderBy" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 pr-10 text-sm font-medium text-stone-700 shadow-sm text-left">
                                <span data-dropdown-value data-value="<?= esc($orderBy ?? 'latest') ?>"><?= esc(($orderBy ?? 'latest') === 'oldest' ? 'Terlama' : 'Terbaru') ?></span>
                                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-stone-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </span>
                            </button>
                            <ul data-dropdown-list class="hidden absolute left-0 right-0 mt-2 z-50 max-h-60 overflow-auto rounded-xl border bg-white shadow-sm">
                                <li data-dropdown-option data-value="latest" class="px-3 py-2 cursor-pointer text-stone-700 hover:bg-stone-50">Terbaru</li>
                                <li data-dropdown-option data-value="oldest" class="px-3 py-2 cursor-pointer text-stone-700 hover:bg-stone-50">Terlama</li>
                            </ul>
                        </div>
                    </div>

                    <div class="min-w-[180px] w-full">
                        <label class="mb-2 block text-sm font-medium text-stone-700">Tanggal Awal</label>
                        <div class="relative" data-datepicker data-datepicker-name="startDate">
                            <input type="hidden" name="startDate" data-datepicker-hidden value="<?= esc($startDate ?? '') ?>">
                            <button type="button" data-datepicker-toggle class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 pr-10 text-sm font-medium text-stone-700 shadow-sm text-left transition focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-100">
                                <span data-datepicker-value class="<?= empty($startDate) ? 'text-stone-400' : '' ?>"><?= !empty($startDate) ? date('d M Y', strtotime($startDate)) : 'Pilih tanggal' ?></span>
                                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-stone-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </span>
                            </button>
                            <div data-datepicker-panel class="hidden absolute left-0 z-50 mt-2 w-72 rounded-xl border border-stone-200 bg-white p-4 shadow-lg">
                                <div class="mb-3 flex items-center justify-between">
                                    <button type="button" data-datepicker-prev class="flex h-8 w-8 items-center justify-center rounded-lg text-stone-500 transition hover:bg-red-50 hover:text-red-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                    <button type="button" data-datepicker-label class="rounded-lg px-2 py-1 text-sm font-semibold text-stone-800 transition hover:bg-red-50 hover:text-red-600"></button>
                                    <button type="button" data-datepicker-next class="flex h-8 w-8 items-center justify-center rounded-lg text-stone-500 transition hover:bg-red-50 hover:text-red-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </div>
                                <div data-datepicker-weekdays class="mb-1 grid grid-cols-7 gap-1 text-center text-xs font-medium text-stone-400">
                                    <span>Min</span><span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span>
                                </div>
                                <div data-datepicker-days class="grid grid-cols-7 gap-1"></div>
                                <button type="button" data-datepicker-clear class="mt-3 w-full rounded-lg border border-stone-200 px-3 py-1.5 text-xs font-medium text-stone-500 transition hover:bg-stone-50">
                                    Hapus tanggal
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="min-w-[180px] w-full">
                        <label class="mb-2 block text-sm font-medium text-stone-700">Tanggal Akhir</label>
                        <div class="relative" data-datepicker data-datepicker-name="endDate">
                            <input type="hidden" name="endDate" data-datepicker-hidden value="<?= esc($endDate ?? '') ?>">
                            <button type="button" data-datepicker-toggle class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 pr-10 text-sm font-medium text-stone-700 shadow-sm text-left transition focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-100">
                                <span data-datepicker-value class="<?= empty($endDate) ? 'text-stone-400' : '' ?>"><?= !empty($endDate) ? date('d M Y', strtotime($endDate)) : 'Pilih tanggal' ?></span>
                                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-stone-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </span>
                            </button>
                            <div data-datepicker-panel class="hidden absolute right-0 z-50 mt-2 w-72 rounded-xl border border-stone-200 bg-white p-4 shadow-lg">
                                <div class="mb-3 flex items-center justify-between">
                                    <button type="button" data-datepicker-prev class="flex h-8 w-8 items-center justify-center rounded-lg text-stone-500 transition hover:bg-red-50 hover:text-red-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                    <button type="button" data-datepicker-label class="rounded-lg px-2 py-1 text-sm font-semibold text-stone-800 transition hover:bg-red-50 hover:text-red-600"></button>
                                    <button type="button" data-datepicker-next class="flex h-8 w-8 items-center justify-center rounded-lg text-stone-500 transition hover:bg-red-50 hover:text-red-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </div>
                                <div data-datepicker-weekdays class="mb-1 grid grid-cols-7 gap-1 text-center text-xs font-medium text-stone-400">
                                    <span>Min</span><span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span>
                                </div>
                                <div data-datepicker-days class="grid grid-cols-7 gap-1"></div>
                                <button type="button" data-datepicker-clear class="mt-3 w-full rounded-lg border border-stone-200 px-3 py-1.5 text-xs font-medium text-stone-500 transition hover:bg-stone-50">
                                    Hapus tanggal
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>

    <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm" id="transactionTableContainer">
        <?= view('cashier/partials/shift_table', ['shifts' => $shifts, 'pager' => $pager ?? null]) ?>
    </section>
</div>
<script src="<?= base_url('assets/js/features/transaction-filter.js') ?>"></script>
<?= $this->endSection() ?>
