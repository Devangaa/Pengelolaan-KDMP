<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Dashboard - Koperasi Desa Merah Putih') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/webp" href="<?= base_url('assets/images/logo.webp') ?>">
    <link rel="shortcut icon" type="image/webp" href="<?= base_url('assets/images/logo.webp') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/cashier-custom.css') ?>">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="<?= base_url('assets/js/features/product-list.js') ?>"></script>
    <script src="<?= base_url('assets/js/cashier.js') ?>"></script>
</head>
<body class="h-screen overflow-hidden bg-stone-100 text-stone-800 antialiased" style="font-family: 'Inter', sans-serif;">
    <div class="flex h-screen overflow-hidden">
        <?= $this->include('layouts/partials/cashier_navbar'); ?>
        <?= $this->include('layouts/partials/cashier_menu'); ?>

            <main class="flex-1 overflow-y-auto p-6 pt-20 lg:pt-6">
                <?= $this->renderSection('content'); ?>
            </main>
    </div>
</body>
</html>