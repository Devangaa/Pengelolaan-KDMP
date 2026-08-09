<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(239,68,68,0.12),_transparent_35%),linear-gradient(135deg,_#fff7f7_0%,_#ffffff_100%)] text-stone-900">
    <main class="mx-auto flex min-h-screen w-full items-center justify-center px-4 py-8 sm:px-6 lg:px-8">
        <section class="w-full max-w-xl rounded-[32px] border border-red-100 bg-white/95 p-6 shadow-[0_30px_80px_-40px_rgba(15,23,42,0.18)] sm:p-10">
            <div class="space-y-5 text-center">
                <h1 class="text-5xl font-semibold tracking-tight text-stone-950 sm:text-6xl">404</h1>
                <p class="mx-auto max-w-xl text-base leading-7 text-stone-600 sm:text-lg">
                    <?php if (ENVIRONMENT !== 'production') : ?>
                        <?= nl2br(esc($message)) ?>
                    <?php else : ?>
                        <?= lang('Errors.sorryCannotFind') ?>
                    <?php endif; ?>
                </p>
            </div>

            <div class="mt-10 flex flex-col gap-3 sm:flex-row sm:justify-center">
                <a href="<?= base_url('/') ?>" class="inline-flex items-center justify-center rounded-full bg-red-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-red-600/20 transition hover:bg-red-700">
                    Kembali ke Beranda
                </a>
                <a href="javascript:history.back()" class="inline-flex items-center justify-center rounded-full border border-red-200 bg-white px-6 py-3 text-sm font-semibold text-stone-700 transition hover:border-red-300 hover:bg-red-50">
                    Kembali sebelumnya
                </a>
            </div>

            <?php if (ENVIRONMENT !== 'production' && ! empty($message)) : ?>
                <div class="mt-10 rounded-3xl border border-red-100 bg-red-50 p-5 text-sm text-stone-700">
                    <p class="font-semibold text-stone-900">Detail kesalahan</p>
                    <pre class="mt-3 overflow-x-auto rounded-2xl bg-white p-4 text-[0.95rem] leading-6 text-stone-700"> <?= nl2br(esc($message)) ?></pre>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
