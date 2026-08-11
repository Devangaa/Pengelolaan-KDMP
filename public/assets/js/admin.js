document.documentElement.classList.add('js-enabled');

document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('admin-sidebar');
    const toggle = document.getElementById('sidebar-toggle');
    const logo = document.getElementById('sidebar-logo');
    const logoIcon = document.getElementById('sidebar-logo-icon');
    const mobileToggle = document.getElementById('sidebar-mobile-toggle');
    const mobileClose = document.getElementById('sidebar-mobile-close');
    const backdrop = document.getElementById('sidebar-backdrop');
    const profileWrap = document.getElementById('sidebar-profile');
    const profileTrigger = document.getElementById('profile-trigger');
    const profileMenu = document.getElementById('profile-menu');
    const profileChevron = document.getElementById('profile-chevron');

    if (!sidebar || !toggle || !logo || !logoIcon) {
        return;
    }

    function closeProfileMenu() {
        if (profileMenu && !profileMenu.classList.contains('hidden')) {
            profileMenu.classList.add('hidden');
            profileTrigger.setAttribute('aria-expanded', 'false');
            if (profileChevron) {
                profileChevron.classList.remove('rotate-180');
            }
        }
    }

    function setCollapsed(isCollapsed, disableTransition = false) {
        if (disableTransition) {
            sidebar.classList.add('no-transition');
        }

        sidebar.classList.toggle('w-20', isCollapsed);
        sidebar.classList.toggle('w-72', !isCollapsed);
        sidebar.classList.toggle('collapsed', isCollapsed);
        document.documentElement.classList.toggle('kdmp-sidebar-collapsed', isCollapsed);

        sidebar.querySelectorAll('.sidebar-label').forEach(function (label) {
            if (disableTransition) {
                label.style.opacity = isCollapsed ? '0' : '1';
            }
        });

        const brandText = sidebar.querySelector('.sidebar-brand-text');
        if (brandText) {
            brandText.classList.toggle('hidden', isCollapsed);
        }

        toggle.classList.toggle('hidden', isCollapsed);
        logo.classList.toggle('group', isCollapsed);
        logo.classList.toggle('cursor-pointer', isCollapsed);
        logoIcon.classList.toggle('hidden', !isCollapsed);
        logoIcon.classList.toggle('flex', isCollapsed);

        closeProfileMenu();

        if (disableTransition) {
            requestAnimationFrame(function () {
                sidebar.classList.remove('no-transition');
                sidebar.querySelectorAll('.sidebar-label').forEach(function (label) {
                    label.style.opacity = '';
                });
            });
        }

        try {
            localStorage.setItem('kdmp-admin-sidebar-collapsed', isCollapsed ? '1' : '0');
        } catch (error) {
            // Ignore storage errors in private mode.
        }
    }

    function initSidebarState() {
        let collapsed = false;
        try {
            collapsed = localStorage.getItem('kdmp-admin-sidebar-collapsed') === '1';
        } catch (error) {
            collapsed = false;
        }
        if (collapsed) {
            document.documentElement.classList.add('kdmp-sidebar-collapsed');
        }
        setCollapsed(collapsed, true);
    }

    toggle.addEventListener('click', function () {
        setCollapsed(true);
    });

    logo.addEventListener('click', function () {
        if (sidebar.classList.contains('w-20')) {
            setCollapsed(false);
        }
    });

    logo.addEventListener('keydown', function (e) {
        if ((e.key === 'Enter' || e.key === ' ') && sidebar.classList.contains('w-20')) {
            e.preventDefault();
            setCollapsed(false);
        }
    });

    if (profileTrigger && profileMenu && profileWrap) {
        profileTrigger.addEventListener('click', function () {
            const isOpen = !profileMenu.classList.contains('hidden');
            profileMenu.classList.toggle('hidden', isOpen);
            profileTrigger.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
            if (profileChevron) {
                profileChevron.classList.toggle('rotate-180', !isOpen);
            }
        });

        document.addEventListener('click', function (e) {
            if (!profileWrap.contains(e.target)) {
                closeProfileMenu();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeProfileMenu();
            }
        });
    }

    if (mobileToggle && backdrop) {
        function setMobileOpen(isOpen) {
            sidebar.classList.toggle('mobile-open', isOpen);
            backdrop.classList.toggle('hidden', !isOpen);
            document.body.classList.toggle('overflow-hidden', isOpen);
            mobileToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            if (!isOpen) {
                closeProfileMenu();
            }
        }

        mobileToggle.addEventListener('click', function () {
            setMobileOpen(!sidebar.classList.contains('mobile-open'));
        });

        backdrop.addEventListener('click', function () {
            setMobileOpen(false);
        });

        if (mobileClose) {
            mobileClose.addEventListener('click', function () {
                setMobileOpen(false);
            });
        }

        sidebar.querySelectorAll('.sidebar-link').forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.innerWidth < 1024) {
                    setMobileOpen(false);
                }
            });
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth >= 1024) {
                setMobileOpen(false);
            }
        });
    }

    initSidebarState();
});