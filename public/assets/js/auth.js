document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('password');
    const toggleButton = document.getElementById('togglePassword');
    const eyeShowIcon = document.getElementById('eyeShow');
    const eyeHideIcon = document.getElementById('eyeHide');

    if (!passwordInput || !toggleButton || !eyeShowIcon || !eyeHideIcon) {
        return;
    }

    toggleButton.addEventListener('click', function () {
        const isPassword = passwordInput.getAttribute('type') === 'password';
        passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

        if (isPassword) {
            eyeShowIcon.classList.replace('block', 'hidden');
            eyeHideIcon.classList.replace('hidden', 'block');
            toggleButton.setAttribute('aria-label', 'Sembunyikan password');
        } else {
            eyeShowIcon.classList.replace('hidden', 'block');
            eyeHideIcon.classList.replace('block', 'hidden');
            toggleButton.setAttribute('aria-label', 'Tampilkan password');
        }

        passwordInput.focus();
    });
});
