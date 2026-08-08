<?= $this->extend('layouts/auth') ?>

<?= $this->section('content'); ?>
<div class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(239,68,68,0.08),_transparent_35%),linear-gradient(135deg,_#fff7f7_0%,_#fff_100%)]">
    <div class="mx-auto flex min-h-screen max-w-7xl items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid w-full overflow-hidden rounded-[32px] border border-stone-200 bg-white shadow-[0_25px_80px_-20px_rgba(0,0,0,0.18)] lg:grid-cols-[1.05fr_0.95fr]">
            <div class="flex items-center justify-center p-8 sm:p-10 lg:p-14">
                <div class="w-full max-w-md">
                    <a href="<?= base_url('/') ?>" class="inline-flex items-center gap-2 rounded-full border border-stone-200 bg-stone-50 px-3 py-2 text-sm font-medium text-stone-600 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 stroke-[1.5]">
                            <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 0 1 0 1.06l-6.22 6.22H21a.75.75 0 0 1 0 1.5H4.81l6.22 6.22a.75.75 0 1 1-1.06 1.06l-7.5-7.5a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                        </svg>
                        Kembali ke Beranda
                    </a>

                    <div class="mt-6 flex items-center gap-3">
                        <img src="<?= base_url('assets/images/logo.webp') ?>" alt="Logo Koperasi" class="h-12 w-12 rounded-full object-cover">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-red-600">Koperasi Merah Putih</p>
                            <p class="text-sm text-stone-500">Portal Pengurus</p>
                        </div>
                    </div>

                    <h1 class="mt-8 text-3xl font-bold tracking-tight text-stone-900 sm:text-4xl">Masuk ke akun Anda</h1>
                    <p class="mt-3 text-sm leading-6 text-stone-600 sm:text-base">
                        Silakan masuk dengan email dan password Anda untuk mengakses dashboard koperasi.
                    </p>

                    <?php $errorMessage = session()->getFlashdata('error'); $validationErrors = session()->getFlashdata('errors'); ?>
                    <?php if ($errorMessage || !empty($validationErrors)): ?>
                        <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                            <?php if ($errorMessage): ?>
                                <p class="font-semibold"><?= esc($errorMessage) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($validationErrors)): ?>
                                <ul class="mt-2 list-disc space-y-1 pl-5">
                                    <?php foreach ($validationErrors as $field => $fieldErrors): ?>
                                        <?php if (is_array($fieldErrors)): ?>
                                            <?php foreach ($fieldErrors as $message): ?>
                                                <li><?= esc($message) ?></li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li><?= esc($fieldErrors) ?></li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <form class="mt-8 space-y-5" action="<?= base_url('login') ?>" method="post">
                        <div>
                            <label for="email" class="mb-2 block text-sm font-medium text-stone-700">Email</label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                required
                                placeholder="nama@email.com"
                                class="w-full rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-700 shadow-sm outline-none transition focus:border-red-400 focus:bg-white focus:ring-4 focus:ring-red-100"
                            >
                        </div>
                        <div>
                            <label for="password" class="mb-2 block text-sm font-medium text-stone-700">Kata Sandi</label>
                            <div class="relative group">
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    required
                                    placeholder="Masukkan kata sandi"
                                    class="w-full rounded-2xl border border-stone-200 bg-stone-50 pl-4 pr-12 py-3 text-sm text-stone-700 shadow-sm outline-none transition focus:border-red-400 focus:bg-white focus:ring-4 focus:ring-red-100"
                                >
                                <button 
                                    type="button" 
                                    id="togglePassword"
                                    tabindex="-1" 
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-stone-400 hover:text-red-600 transition outline-none"
                                    aria-label="Tampilkan password"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 block" id="eyeShow">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 hidden" id="eyeHide">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.243 4.243L9.878 9.878" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="w-full rounded-2xl bg-red-600 px-4 py-3.5 text-sm font-semibold text-white shadow-lg shadow-red-600/20 transition hover:bg-red-700">
                            Masuk
                        </button>
                    </form>

                    <p class="mt-6 text-sm text-stone-500">
                        <a href="<?= base_url('forgot-password') ?>" class="font-semibold text-red-600 hover:text-red-700"> Lupa kata sandi?</a>
                    </p>
                </div>
            </div>

            <div class="hidden bg-stone-100 lg:block">
                <div class="h-full w-full overflow-hidden">
                    <img
                        src="<?= base_url('assets/images/login.webp') ?>"
                        alt="Kegiatan koperasi"
                        class="h-full w-full object-cover"
                    >
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

