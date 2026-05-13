<script src="{{ asset('sneat/assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('sneat/assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('sneat/assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('sneat/assets/vendor/js/menu.js') }}"></script>

<script src="{{ asset('sneat/assets/js/main.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    /** * 1. LOGIKA SAKLAR TEMA (Dark/Light Mode)
     */
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const htmlTag = document.documentElement;

    function updateIcon(theme) {
        if (themeIcon) {
            if (theme === 'dark') {
                themeIcon.classList.remove('bx-moon');
                themeIcon.classList.add('bx-sun');
            } else {
                themeIcon.classList.remove('bx-sun');
                themeIcon.classList.add('bx-moon');
            }
        }
    }

    // Cek tema saat ini saat halaman dimuat
    const currentTheme = htmlTag.classList.contains('dark-style') ? 'dark' : 'light';
    updateIcon(currentTheme);

    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            let newTheme = 'light';
            
            if (htmlTag.classList.contains('light-style')) {
                htmlTag.classList.remove('light-style');
                htmlTag.classList.add('dark-style');
                htmlTag.setAttribute('data-theme', 'theme-dark');
                newTheme = 'dark';
            } else {
                htmlTag.classList.remove('dark-style');
                htmlTag.classList.add('light-style');
                htmlTag.setAttribute('data-theme', 'theme-default');
                newTheme = 'light';
            }

            localStorage.setItem('templateCustomizer-vertical-menu-template--Style', newTheme);
            updateIcon(newTheme);
        });
    }

    /** * 2. LOGIKA MINI CALENDAR SIDEBAR
     */
    function renderCalendar() {
        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        const now = new Date();
        const month = now.getMonth();
        const year = now.getFullYear();
        const today = now.getDate();

        const monthEl = document.getElementById('cal-month');
        const yearEl = document.getElementById('cal-year');
        const datesContainer = document.getElementById('cal-dates');

        if (monthEl && yearEl && datesContainer) {
            monthEl.innerText = monthNames[month];
            yearEl.innerText = year;

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            
            // Reset kontainer sebelum render
            datesContainer.innerHTML = '';

            // Penyesuaian agar hari Senin jadi index pertama (0)
            let startingDay = firstDay === 0 ? 6 : firstDay - 1;

            // Slot kosong awal bulan
            for (let i = 0; i < startingDay; i++) {
                datesContainer.innerHTML += '<div></div>';
            }

            // Isi angka tanggal
            for (let date = 1; date <= daysInMonth; date++) {
                const isToday = date === today ? 'background: rgba(255,255,255,0.3); border-radius: 50%; font-weight: bold;' : '';
                datesContainer.innerHTML += `<div style="padding: 2px; ${isToday}">${date}</div>`;
            }
        }
    }

    renderCalendar();
});
</script>

@stack('page-script')