<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Koperasi Merah Putih') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/webp" href="<?= base_url('assets/images/logo.webp') ?>">
    <link rel="shortcut icon" type="image/webp" href="<?= base_url('assets/images/logo.webp') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="<?= base_url('assets/css/swiper-custom.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="<?= base_url('assets/js/guest.js') ?>"></script>
    <script src="<?= base_url('assets/js/features/product-list.js') ?>"></script>
</head>
<body class="min-h-screen bg-stone-50 text-stone-800 antialiased">
    <?= $this->include('layouts/partials/header'); ?>

    <main class="min-h-screen">
        <?= $this->renderSection('content'); ?>
    </main>

    <?= $this->include('layouts/partials/footer'); ?>
</body>
</html>
