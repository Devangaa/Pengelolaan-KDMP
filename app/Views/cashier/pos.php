<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-name" content="<?= csrf_token() ?>">
    <meta name="csrf-value" content="<?= csrf_hash() ?>">
    <title><?= esc($title ?? 'POS Kasir - Koperasi Desa Merah Putih') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" type="image/webp" href="<?= base_url('assets/images/logo.webp') ?>">
    <link rel="shortcut icon" type="image/webp" href="<?= base_url('assets/images/logo.webp') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/cashier-custom.css') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
    <script>
        window.posProducts = <?= json_encode($products ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP) ?>;
    </script>
    <script src="<?= base_url('assets/js/pos.js') ?>" defer></script>
</head>
<body class="min-h-screen bg-stone-100 text-stone-800">
    <?= $this->include('components/notification_success') ?>
    <?= $this->include('components/notification_error') ?>
    <div class="min-h-screen">
        <header class="sticky top-0 z-20 border-b border-stone-200 bg-white/90 backdrop-blur">
            <div class="mx-auto flex max-w-[1800px] items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <button type="button" onclick="window.location.href='<?= base_url('dasbor') ?>'" class="inline-flex items-center gap-2 rounded-full border border-stone-200 bg-white px-3 py-2 text-sm font-semibold text-stone-700 shadow-sm transition hover:bg-stone-100">
                        <span class="material-icons text-base">arrow_back</span>
                        Kembali Dashboard
                    </button>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-red-600">Mesin Kasir</p>
                        <h1 class="text-xl font-bold text-stone-900 sm:text-2xl">KDMP</h1>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <?php if (!empty($shiftActive)): ?>
                        <div class="hidden rounded-full border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 sm:flex sm:items-center sm:gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                            Sesi aktif sejak <?= esc($shiftStartedAt ?? '00:00') ?>
                        </div>
                    <?php else: ?>
                        <div class="hidden rounded-full border border-amber-200 bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-700 sm:flex sm:items-center sm:gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                            Sesi belum aktif
                        </div>
                    <?php endif; ?>

                    <button id="endShiftButton" type="button" class="inline-flex items-center gap-2 rounded-full bg-stone-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-stone-700">
                        <span class="material-icons text-base">logout</span>
                        Akhiri Sesi
                    </button>
                </div>
            </div>
        </header>

        <main class="mx-auto grid max-w-[1800px] gap-4 px-4 py-5 sm:px-6 lg:grid-cols-[1.7fr_0.9fr] lg:items-start lg:px-8">
            <section class="flex min-h-0 flex-col space-y-5 lg:max-h-[calc(100vh-7rem)]">
                <div class="rounded-3xl border border-stone-200 bg-white p-4 shadow-sm sm:p-5">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-3">
                            <div class="relative">
                                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-stone-400">search</span>
                                <input id="searchProduct" type="text" placeholder="Cari produk..." class="w-full rounded-full border border-stone-200 bg-stone-50 py-2.5 pl-10 pr-4 text-sm text-stone-700 shadow-sm outline-none transition focus:border-red-400 focus:ring-2 focus:ring-red-100 sm:w-72">
                            </div>
                            <button id="openBarcodeScanner" type="button" class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-700 shadow-sm transition hover:bg-red-100">
                                <span class="material-icons text-base">qr_code_2</span>
                                Scan QR
                            </button>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="rounded-full border border-stone-200 bg-stone-50 px-3 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-stone-500">
                                Modal Awal: Rp <?= number_format($openingBalance ?? 0, 0, ',', '.') ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="productGrid" class="grid min-h-0 grid-cols-2 gap-3 overflow-y-auto pb-24 pr-1 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 lg:pb-0 lg:max-h-[calc(100vh-15rem)]"></div>
            </section>

            <div class="hidden lg:block">
                <aside class="flex min-h-[28rem] flex-col rounded-3xl border border-stone-200 bg-white p-4 shadow-sm sm:p-5 lg:max-h-[calc(100vh-7rem)]">
                    <div class="flex items-center justify-between gap-3 border-b border-stone-200 pb-4">
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-red-600">Keranjang</p>
                            <h2 class="mt-1 text-xl font-bold text-stone-900">Pesanan</h2>
                        </div>
                        <button id="clearCartButton" type="button" class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 transition hover:bg-red-100">
                            <span class="material-icons text-base">delete</span>
                            Hapus
                        </button>
                    </div>

                    <div class="mt-4 min-h-0 flex-1 overflow-y-auto scrollbar-thin pr-1">
                        <div id="cartItems" class="space-y-3"></div>
                    </div>

                    <div class="mt-5 rounded-2xl bg-stone-50 p-4">
                        <div class="flex items-center justify-between text-2xl font-bold text-stone-900">
                            <span>Total</span>
                            <span id="cartTotal">Rp 0</span>
                        </div>
                    </div>

                    <button id="checkoutButton" type="button" class="mt-4 w-full rounded-2xl bg-red-600 px-4 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-red-700">
                        <span class="inline-flex items-center justify-center gap-2">
                            <span class="material-icons text-base">payments</span>
                            Bayar
                        </span>
                    </button>
                </aside>
            </div>
        </main>
    </div>

    <div id="mobileCartBar" class="fixed inset-x-0 bottom-0 z-40 border-t border-stone-200 bg-white/95 px-4 py-3 shadow-[0_-8px_24px_rgba(0,0,0,0.08)] backdrop-blur-sm lg:hidden">
        <div class="flex items-center justify-between gap-3">
            <div class="min-w-0">
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-stone-500">Pesanan</p>
                <div class="mt-1 flex items-center gap-2 text-sm text-stone-700">
                    <span id="mobileCartCount" class="font-semibold text-stone-900">0 item</span>
                    <span class="text-stone-300">•</span>
                    <span id="mobileCartTotal" class="truncate font-semibold text-stone-900">Rp 0</span>
                </div>
            </div>
            <button id="openCartDrawer" type="button" class="shrink-0 rounded-2xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700">
                Lihat Pesanan
            </button>
        </div>
    </div>

    <div id="cartDrawer" class="fixed inset-0 z-50 hidden lg:hidden">
        <div class="absolute inset-0 bg-stone-900/50 backdrop-blur-sm"></div>
        <div id="cartDrawerPanel" class="absolute right-0 top-0 flex h-full w-full max-w-md translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300 ease-out">
            <div class="flex items-center justify-between border-b border-stone-200 px-4 py-4">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-red-600">Keranjang</p>
                    <h2 class="mt-1 text-xl font-bold text-stone-900">Pesanan</h2>
                </div>
                <div class="flex items-center gap-2">
                    <button id="clearCartButtonMobile" type="button" class="inline-flex items-center gap-1 rounded-full border border-red-200 bg-red-50 px-2.5 py-1.5 text-[11px] font-semibold text-red-700 transition hover:bg-red-100">
                        <span class="material-icons text-sm">delete</span>
                        Hapus
                    </button>
                    <button id="closeCartDrawer" type="button" class="flex h-10 w-10 items-center justify-center rounded-full border border-stone-200 text-stone-600 transition hover:bg-stone-100">
                        <span class="material-icons">close</span>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto px-4 py-4 scrollbar-thin">
                <div id="cartItemsMobile" class="space-y-3"></div>
            </div>

            <div class="border-t border-stone-200 bg-white px-4 py-4">
                <div class="flex items-center justify-between text-lg font-bold text-stone-900">
                    <span>Total</span>
                    <span id="drawerCartTotal">Rp 0</span>
                </div>
                <button id="checkoutButtonMobile" type="button" class="mt-4 w-full rounded-2xl bg-red-600 px-4 py-3 text-base font-semibold text-white shadow-sm transition hover:bg-red-700">
                    <span class="inline-flex items-center justify-center gap-2">
                        <span class="material-icons text-base">payments</span>
                        Bayar
                    </span>
                </button>
            </div>
        </div>
    </div>

    <div id="startShiftModal" class="fixed inset-0 z-40 hidden items-center justify-center bg-stone-900/60 p-4 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-red-600">Mulai Sesi</p>
                    <h3 class="mt-2 text-2xl font-bold text-stone-900">Buka Shift</h3>
                </div>
            </div>

            <form action="<?= base_url('pos/mulai-shift') ?>" method="post" class="mt-5 space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label for="modalAwal" class="mb-2 block text-sm font-semibold text-stone-700">Uang awal kasir</label>
                    <input id="modalAwal" type="number" name="modal_awal" min="0" step="100" class="w-full rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3 text-base text-stone-800 outline-none transition focus:border-red-400 focus:ring-2 focus:ring-red-100" placeholder="Masukkan uang kasir awal" required>
                </div>
                <button type="submit" class="w-full rounded-2xl bg-red-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-red-700">Mulai Sesi</button>
            </form>
            <button type="button" onclick="window.location.href='<?= base_url('dasbor') ?>'" class="mt-3 w-full rounded-2xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-stone-700 transition hover:bg-stone-100">Kembali ke Beranda</button>
        </div>
    </div>

    <div id="endShiftModal" class="fixed inset-0 z-40 hidden items-center justify-center bg-stone-900/60 p-4 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-red-600">Akhiri Sesi</p>
                    <h3 class="mt-2 text-2xl font-bold text-stone-900">Hitung Uang Kasir</h3>
                </div>
            </div>

            <form action="<?= base_url('pos/tutup-shift') ?>" method="post" class="mt-5 space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label for="uangFisik" class="mb-2 block text-sm font-semibold text-stone-700">Uang fisik akhir kasir</label>
                    <input id="uangFisik" type="number" name="uang_fisik" min="0" step="100" class="w-full rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3 text-base text-stone-800 outline-none transition focus:border-red-400 focus:ring-2 focus:ring-red-100" placeholder="Masukkan uang fisik akhir" required>
                </div>
                <button type="submit" class="w-full rounded-2xl bg-stone-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-stone-700">Simpan & Tutup Sesi</button>
            </form>
        </div>
    </div>

    <div id="paymentModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-stone-900/60 p-4 backdrop-blur-sm">
        <div class="w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-red-600">Pembayaran</p>
                    <h3 class="mt-2 text-2xl font-bold text-stone-900">Bayar Transaksi</h3>
                </div>
                <button type="button" data-close-payment class="flex h-10 w-10 items-center justify-center rounded-full border border-stone-200 text-stone-600 transition hover:bg-stone-100">
                    <span class="material-icons">close</span>
                </button>
            </div>

            <div class="mt-5 rounded-2xl bg-stone-50 p-4">
                <div class="flex items-center justify-between text-sm text-stone-600">
                    <span>Total tagihan</span>
                    <strong id="paymentTotal" class="text-lg text-stone-900">Rp 0</strong>
                </div>
            </div>

            <form id="paymentForm" class="mt-5 space-y-4">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-stone-700">Cara bayar</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex cursor-pointer items-center justify-center gap-2 rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm font-semibold text-stone-700 transition hover:border-red-200 hover:bg-red-50">
                            <input type="radio" name="payment_type" value="tunai" checked class="h-4 w-4 text-red-600 focus:ring-red-500">
                            Tunai
                        </label>
                        <label class="flex cursor-pointer items-center justify-center gap-2 rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm font-semibold text-stone-700 transition hover:border-red-200 hover:bg-red-50">
                            <input type="radio" name="payment_type" value="nontunai" class="h-4 w-4 text-red-600 focus:ring-red-500">
                            Non Tunai
                        </label>
                    </div>
                </div>

                <div>
                    <label for="cashInput" class="mb-2 block text-sm font-semibold text-stone-700">Uang customer</label>
                    <input id="cashInput" type="number" name="cash" min="0" step="100" class="w-full rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3 text-base text-stone-800 outline-none transition focus:border-red-400 focus:ring-2 focus:ring-red-100" placeholder="Masukkan uang customer" required>
                </div>

                <div class="rounded-2xl border border-stone-200 bg-stone-50 p-4">
                    <div class="flex items-center justify-between text-sm text-stone-600">
                        <span>Kembalian</span>
                        <strong id="changePreview" class="text-lg text-stone-900">Rp 0</strong>
                    </div>
                </div>

                <button type="submit" class="w-full rounded-2xl bg-red-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-red-700">Konfirmasi Pembayaran</button>
            </form>
        </div>
    </div>

    <div id="paymentSuccessModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-stone-900/60 p-4 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl">
            <div class="flex items-center justify-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                    <span class="material-icons text-3xl">done</span>
                </div>
            </div>
            <div class="mt-5 text-center">
                <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-emerald-600">Transaksi Berhasil</p>
                <h3 class="mt-2 text-2xl font-bold text-stone-900">Pembayaran Selesai</h3>
                <div class="mt-5 rounded-2xl bg-emerald-50 p-4 ring-1 ring-emerald-100">
                    <p class="text-sm text-stone-600">Kembalian</p>
                    <p id="successChange" class="mt-2 text-3xl font-extrabold text-emerald-700">Rp 0</p>
                </div>
                <p class="mt-4 text-sm text-stone-600">No. struk: <span id="successInvoice" class="font-semibold text-stone-900">-</span></p>
            </div>
            <button id="closeSuccessModal" type="button" class="mt-6 w-full rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">Selesai</button>
        </div>
    </div>

    <script>
        const shiftState = <?= json_encode(['active' => (bool) ($shiftActive ?? false)]) ?>;
    </script>

    <div id="barcodeScannerModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-stone-900/60 p-4 backdrop-blur-sm">
        <div class="w-full max-w-lg rounded-3xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-stone-200 px-6 py-4">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-red-600">Scan Produk</p>
                    <h3 class="mt-2 text-2xl font-bold text-stone-900">Pemindai Barcode</h3>
                </div>
                <button id="closeBarcodeScanner" type="button" class="flex h-10 w-10 items-center justify-center rounded-full border border-stone-200 text-stone-600 transition hover:bg-stone-100">
                    <span class="material-icons">close</span>
                </button>
            </div>

            <div class="p-6">
                <div id="qr-reader" class="w-full overflow-hidden rounded-2xl border-2 border-stone-200"></div>
                
                <div class="mt-4 space-y-3">
                    <div>
                        <label for="barcodeManualInput" class="mb-2 block text-sm font-semibold text-stone-700">Atau masukkan barcode manual:</label>
                        <input id="barcodeManualInput" type="text" placeholder="Masukkan barcode..." class="w-full rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3 text-base text-stone-800 outline-none transition focus:border-red-400 focus:ring-2 focus:ring-red-100">
                    </div>
                    <button id="submitBarcodeManual" type="button" class="w-full rounded-2xl bg-red-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                        Cari Produk
                    </button>
                </div>

                <div id="scannerStatus" class="mt-4 rounded-2xl bg-stone-50 p-4 text-center text-sm text-stone-600"></div>
            </div>
        </div>
    </div>
</body>
</html>
