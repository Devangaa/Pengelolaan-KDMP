<?php $errorMessage = session()->getFlashdata('error'); ?>
<?php if ($errorMessage): ?>
<div id="error-notification" role="alert" aria-live="assertive" class="fixed inset-x-4 top-4 z-50 mx-auto max-w-xl rounded-3xl border border-red-200 bg-red-50 px-4 py-4 shadow-2xl shadow-red-950/10 text-red-950 backdrop-blur-sm sm:left-auto sm:right-4 sm:max-w-md transition-opacity duration-300">
    <div class="flex items-start gap-3">
        <div class="mt-0.5 flex h-9 w-9 items-center justify-center rounded-2xl bg-red-100 text-red-700">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
            </svg>
        </div>
        <div class="min-w-0">
            <p class="text-sm font-semibold">Perhatian</p>
            <p class="mt-1 text-sm leading-6 text-red-900"><?= esc($errorMessage) ?></p>
        </div>
    </div>
</div>
<script>
    (function () {
        var notif = document.getElementById('error-notification');
        if (!notif) return;

        setTimeout(function () {
            notif.classList.add('opacity-0', 'pointer-events-none');
            notif.addEventListener('transitionend', function () {
                if (notif.parentNode) {
                    notif.parentNode.removeChild(notif);
                }
            });
        }, 3000);
    })();
</script>
<?php endif; ?>
