function onReady(fn) {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', fn);
    } else {
        fn();
    }
}

onReady(function () {
    initTransactionFilter();
    initTransactionDatepickers();
});

function initTransactionFilter() {
    const container = document.getElementById('transactionTableContainer');
    const filterForm = document.getElementById('transactionFilterForm');

    if (!container || !filterForm) {
        return;
    }

    function fetchTransactions(url) {
        container.classList.add('opacity-50', 'pointer-events-none');

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error('Network error');
                }
                return response.text();
            })
            .then((html) => {
                container.innerHTML = html;
            })
            .catch((err) => {
                console.error('Error fetching transactions:', err);
            })
            .finally(() => {
                container.classList.remove('opacity-50', 'pointer-events-none');
            });
    }

    function submitFilter() {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);
        const actionUrl = filterForm.getAttribute('action') || window.location.pathname;
        fetchTransactions(`${actionUrl}?${params.toString()}`);
    }

    function closeDropdowns(except = null) {
        document.querySelectorAll('[data-dropdown-list]').forEach((list) => {
            if (list !== except) {
                list.classList.add('hidden');
            }
        });
    }

    document.addEventListener('click', function (event) {
        const toggle = event.target.closest('[data-dropdown-toggle]');
        if (toggle) {
            const parent = toggle.closest('[data-dropdown]');
            const list = parent.querySelector('[data-dropdown-list]');
            closeDropdowns(list);
            list.classList.toggle('hidden');
            return;
        }

        const option = event.target.closest('[data-dropdown-option]');
        if (option) {
            const parent = option.closest('[data-dropdown]');
            const list = parent.querySelector('[data-dropdown-list]');
            const valueSpan = parent.querySelector('[data-dropdown-value]');
            const hiddenInput = parent.querySelector('[data-dropdown-hidden]');
            const selectedValue = option.getAttribute('data-value');
            const selectedText = option.textContent.trim();

            if (valueSpan) {
                valueSpan.textContent = selectedText;
                valueSpan.setAttribute('data-value', selectedValue);
            }
            if (hiddenInput) {
                hiddenInput.value = selectedValue;
            }

            list.classList.add('hidden');
            submitFilter();
            return;
        }

        if (!event.target.closest('[data-dropdown]')) {
            closeDropdowns();
        }
    });

    document.addEventListener('click', function (event) {
        const paginationLink = event.target.closest('.ajax-pagination');
        if (!paginationLink) {
            return;
        }

        event.preventDefault();
        const href = paginationLink.getAttribute('href');
        if (!href || href === '#') return;

        const urlObj = new URL(href, window.location.origin);
        const pageNum = urlObj.searchParams.get('page_transactions') || 1;
        const formData = new FormData(filterForm);
        formData.set('page_transactions', pageNum);
        const params = new URLSearchParams(formData);
        const actionUrl = filterForm.getAttribute('action') || window.location.pathname;
        fetchTransactions(`${actionUrl}?${params.toString()}`);
    });

    filterForm.addEventListener('submit', function (event) {
        event.preventDefault();
        submitFilter();
    });
}

function initTransactionDatepickers() {
    const roots = document.querySelectorAll('[data-datepicker]');
    if (!roots.length) {
        return;
    }

    const MIN_YEAR = 2000;
    const YEARS_PER_PAGE = 12;
    const MONTH_NAMES = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const MONTH_SHORT = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    function pad(value) {
        return String(value).padStart(2, '0');
    }

    function toISO(date) {
        return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
    }

    function formatDisplay(date) {
        return `${date.getDate()} ${MONTH_SHORT[date.getMonth()]} ${date.getFullYear()}`;
    }

    function parseISO(value) {
        if (!value) {
            return null;
        }

        const parts = value.split('-').map(Number);
        if (parts.length !== 3 || parts.some(isNaN)) {
            return null;
        }

        return new Date(parts[0], parts[1] - 1, parts[2]);
    }

    const datepickerRoots = Array.from(roots);
    const form = document.getElementById('transactionFilterForm');

    function submitFilter() {
        if (!form) {
            return;
        }
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);
        const actionUrl = form.getAttribute('action') || window.location.pathname;
        fetch(`${actionUrl}?${params.toString()}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error('Network error');
                }
                return response.text();
            })
            .then((html) => {
                const container = document.getElementById('transactionTableContainer');
                if (container) {
                    container.innerHTML = html;
                }
            })
            .catch((err) => {
                console.error('Error fetching transactions:', err);
            });
    }

    function closeAllPanels(except) {
        roots.forEach((root) => {
            const panel = root.querySelector('[data-datepicker-panel]');
            if (panel && panel !== except) {
                panel.classList.add('hidden');
            }
        });
    }

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    datepickerRoots.forEach((root) => {
        const toggle = root.querySelector('[data-datepicker-toggle]');
        const panel = root.querySelector('[data-datepicker-panel]');
        const hiddenInput = root.querySelector('[data-datepicker-hidden]');
        const valueLabel = root.querySelector('[data-datepicker-value]');
        const monthLabel = root.querySelector('[data-datepicker-label]');
        const weekdaysRow = root.querySelector('[data-datepicker-weekdays]');
        const daysGrid = root.querySelector('[data-datepicker-days]');
        const prevBtn = root.querySelector('[data-datepicker-prev]');
        const nextBtn = root.querySelector('[data-datepicker-next]');
        const clearBtn = root.querySelector('[data-datepicker-clear]');

        if (!toggle || !panel || !hiddenInput || !valueLabel || !monthLabel || !daysGrid) {
            return;
        }

        const initialDate = parseISO(hiddenInput.value) || new Date();
        let viewYear = initialDate.getFullYear();
        let viewMonth = initialDate.getMonth();
        let selectedDate = parseISO(hiddenInput.value);
        let view = 'days'; // 'days' | 'months' | 'years'
        let yearsPageStart = Math.floor(viewYear / YEARS_PER_PAGE) * YEARS_PER_PAGE;

        function setNextDisabled(disabled) {
            if (!nextBtn) return;
            nextBtn.disabled = disabled;
            nextBtn.classList.toggle('opacity-30', disabled);
            nextBtn.classList.toggle('cursor-not-allowed', disabled);
            nextBtn.classList.toggle('pointer-events-none', disabled);
        }

        function setPrevDisabled(disabled) {
            if (!prevBtn) return;
            prevBtn.disabled = disabled;
            prevBtn.classList.toggle('opacity-30', disabled);
            prevBtn.classList.toggle('cursor-not-allowed', disabled);
            prevBtn.classList.toggle('pointer-events-none', disabled);
        }

        function renderDays() {
            daysGrid.className = 'grid grid-cols-7 gap-1';
            if (weekdaysRow) weekdaysRow.classList.remove('hidden');

            monthLabel.textContent = `${MONTH_NAMES[viewMonth]} ${viewYear}`;
            daysGrid.innerHTML = '';

            const firstOfMonth = new Date(viewYear, viewMonth, 1);
            const startOffset = firstOfMonth.getDay();
            const daysInMonth = new Date(viewYear, viewMonth + 1, 0).getDate();

            const isCurrentOrFutureMonth =
                viewYear > today.getFullYear() ||
                (viewYear === today.getFullYear() && viewMonth >= today.getMonth());
            setNextDisabled(isCurrentOrFutureMonth);
            setPrevDisabled(false);

            for (let i = 0; i < startOffset; i++) {
                daysGrid.appendChild(document.createElement('span'));
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const cellDate = new Date(viewYear, viewMonth, day);
                cellDate.setHours(0, 0, 0, 0);

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = String(day);

                const isSelected = selectedDate
                    && cellDate.getFullYear() === selectedDate.getFullYear()
                    && cellDate.getMonth() === selectedDate.getMonth()
                    && cellDate.getDate() === selectedDate.getDate();
                const isToday = cellDate.getTime() === today.getTime();
                const isFutureDate = cellDate.getTime() > today.getTime();

                if (isFutureDate) {
                    btn.disabled = true;
                    btn.className = 'flex h-8 w-8 items-center justify-center rounded-lg text-sm text-stone-300 cursor-not-allowed bg-stone-50';
                } else {
                    btn.className = 'flex h-8 w-8 items-center justify-center rounded-lg text-sm transition ' +
                        (isSelected
                            ? 'bg-red-600 font-semibold text-white'
                            : isToday
                                ? 'border border-red-200 text-red-600 hover:bg-red-50'
                                : 'text-stone-700 hover:bg-red-50 hover:text-red-600');

                    btn.addEventListener('click', function (e) {
                        e.stopPropagation();
                        selectedDate = cellDate;
                        hiddenInput.value = toISO(cellDate);
                        valueLabel.textContent = formatDisplay(cellDate);
                        valueLabel.classList.remove('text-stone-400');
                        closePanel();
                        submitFilter();
                    });
                }

                daysGrid.appendChild(btn);
            }
        }

        function renderMonths() {
            daysGrid.className = 'grid grid-cols-3 gap-2';
            if (weekdaysRow) weekdaysRow.classList.add('hidden');

            monthLabel.textContent = String(viewYear);
            daysGrid.innerHTML = '';

            setPrevDisabled(viewYear <= MIN_YEAR);
            setNextDisabled(viewYear >= today.getFullYear());

            for (let m = 0; m < 12; m++) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = MONTH_SHORT[m];

                const isFuture = viewYear === today.getFullYear() && m > today.getMonth();
                const isSelectedMonth = viewMonth === m;

                if (isFuture) {
                    btn.disabled = true;
                    btn.className = 'flex h-9 items-center justify-center rounded-lg text-sm text-stone-300 cursor-not-allowed bg-stone-50';
                } else {
                    btn.className = 'flex h-9 items-center justify-center rounded-lg text-sm transition ' +
                        (isSelectedMonth
                            ? 'bg-red-600 font-semibold text-white'
                            : 'text-stone-700 hover:bg-red-50 hover:text-red-600');

                    btn.addEventListener('click', function (e) {
                        e.stopPropagation();
                        viewMonth = m;
                        view = 'days';
                        render();
                    });
                }

                daysGrid.appendChild(btn);
            }
        }

        function renderYears() {
            daysGrid.className = 'grid grid-cols-3 gap-2';
            if (weekdaysRow) weekdaysRow.classList.add('hidden');

            const yearsPageEnd = yearsPageStart + YEARS_PER_PAGE - 1;
            monthLabel.textContent = `${yearsPageStart} - ${yearsPageEnd}`;
            monthLabel.disabled = true;
            daysGrid.innerHTML = '';

            setPrevDisabled(yearsPageStart <= MIN_YEAR);
            setNextDisabled(yearsPageEnd >= today.getFullYear());

            for (let y = yearsPageStart; y <= yearsPageEnd; y++) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = String(y);

                const isDisabled = y < MIN_YEAR || y > today.getFullYear();
                const isSelectedYear = viewYear === y;

                if (isDisabled) {
                    btn.disabled = true;
                    btn.className = 'flex h-9 items-center justify-center rounded-lg text-sm text-stone-300 cursor-not-allowed bg-stone-50';
                } else {
                    btn.className = 'flex h-9 items-center justify-center rounded-lg text-sm transition ' +
                        (isSelectedYear
                            ? 'bg-red-600 font-semibold text-white'
                            : 'text-stone-700 hover:bg-red-50 hover:text-red-600');

                    btn.addEventListener('click', function (e) {
                        e.stopPropagation();
                        viewYear = y;
                        view = 'months';
                        render();
                    });
                }

                daysGrid.appendChild(btn);
            }
        }

        function render() {
            monthLabel.disabled = false;
            if (view === 'months') {
                renderMonths();
            } else if (view === 'years') {
                renderYears();
            } else {
                renderDays();
            }
        }

        function openPanel() {
            closeAllPanels(panel);
            document.querySelectorAll('[data-dropdown-list]').forEach((list) => list.classList.add('hidden'));
            view = 'days';
            render();
            panel.classList.remove('hidden');
        }

        function closePanel() {
            panel.classList.add('hidden');
            view = 'days';
        }

        toggle.addEventListener('click', function (e) {
            e.stopPropagation();
            const isOpen = !panel.classList.contains('hidden');
            if (isOpen) {
                closePanel();
            } else {
                openPanel();
            }
        });

        monthLabel.addEventListener('click', function (e) {
            e.stopPropagation();
            if (view === 'days') {
                view = 'months';
                render();
            } else if (view === 'months') {
                view = 'years';
                yearsPageStart = Math.floor(viewYear / YEARS_PER_PAGE) * YEARS_PER_PAGE;
                render();
            }
        });

        prevBtn?.addEventListener('click', function () {
            if (view === 'months') {
                viewYear -= 1;
            } else if (view === 'years') {
                yearsPageStart -= YEARS_PER_PAGE;
            } else {
                viewMonth -= 1;
                if (viewMonth < 0) {
                    viewMonth = 11;
                    viewYear -= 1;
                }
            }
            render();
        });

        nextBtn?.addEventListener('click', function () {
            if (view === 'months') {
                viewYear += 1;
            } else if (view === 'years') {
                yearsPageStart += YEARS_PER_PAGE;
            } else {
                viewMonth += 1;
                if (viewMonth > 11) {
                    viewMonth = 0;
                    viewYear += 1;
                }
            }
            render();
        });

        clearBtn?.addEventListener('click', function () {
            selectedDate = null;
            hiddenInput.value = '';
            valueLabel.textContent = 'Pilih tanggal';
            valueLabel.classList.add('text-stone-400');
            closePanel();
            submitFilter();
        });

        if (selectedDate) {
            valueLabel.textContent = formatDisplay(selectedDate);
            valueLabel.classList.remove('text-stone-400');
        }

        document.addEventListener('click', function (e) {
            if (!root.contains(e.target)) {
                closePanel();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closePanel();
            }
        });
    });
}