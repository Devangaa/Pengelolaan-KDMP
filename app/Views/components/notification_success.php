<?php $successMessage = session()->getFlashdata('success'); ?>
<?php if ($successMessage): ?>
<div id="success-notification" role="status" aria-live="assertive" class="fixed inset-x-4 top-4 z-50 mx-auto max-w-xl rounded-3xl border border-emerald-200 bg-emerald-50 px-4 py-4 shadow-2xl shadow-emerald-950/10 text-emerald-950 backdrop-blur-sm sm:left-auto sm:right-4 sm:max-w-md transition-opacity duration-300">
    <div class="flex items-start gap-3">
        <div class="mt-0.5 flex h-9 w-9 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <div class="min-w-0">
            <p class="text-sm font-semibold">Sukses</p>
            <p class="mt-1 text-sm leading-6 text-emerald-900"><?= esc($successMessage) ?></p>
        </div>
    </div>
</div>
<script>
    (function () {
        var notif = document.getElementById('success-notification');
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
