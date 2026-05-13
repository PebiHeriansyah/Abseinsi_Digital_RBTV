<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo d-flex justify-content-center align-items-center" style="height: 100px;">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <img src="{{ asset('images/RBTV.png') }}" alt="Logo RBTV" style="max-height: 70px; width: auto; object-fit: contain;">
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large position-absolute d-block d-xl-none" style="right: 15px;">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        {{-- DASHBOARD --}}
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Data</span>
        </li>

        {{-- KARYAWAN --}}
        <li class="menu-item {{ request()->routeIs('karyawan.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-group"></i>
                <div data-i18n="Karyawan">Karyawan</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('karyawan.index') ? 'active' : '' }}">
                    <a href="{{ route('karyawan.index') }}" class="menu-link">
                        <div data-i18n="Data Karyawan">Data Karyawan</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('karyawan.create') ? 'active' : '' }}">
                    <a href="{{ route('karyawan.create') }}" class="menu-link">
                        <div data-i18n="Tambah Karyawan">Tambah Karyawan</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- ATUR LOKASI --}}
        <li class="menu-item {{ request()->routeIs('lokasi.*') ? 'active' : '' }}">
            <a href="{{ route('lokasi.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-map-pin"></i>
                <div data-i18n="Atur Lokasi">Atur Lokasi</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Rekapitulasi</span>
        </li>

        {{-- LAPORAN --}}
        <li class="menu-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <a href="{{ route('laporan.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Laporan Absensi">Laporan Absensi</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Kalender</span>
        </li>

        {{-- MINI CALENDAR SIDEBAR (SESUAI WARNA UNGU LAPORAN) --}}
        <li class="menu-item px-3 py-2">
            {{-- Menggunakan warna latar #f5f5f9 sesuai elemen aktif Sneat --}}
            <div id="mini-calendar-sidebar" class="p-3 rounded" style="background: #f5f5f9; border: 1px solid #e1e1e3;">
                <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-1" style="border-color: #d9dee3 !important;">
                    <span id="side-cal-month" class="fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px; color: #696cff;"></span>
                    <span id="side-cal-year" class="fw-bold" style="font-size: 0.8rem; color: #566a7f;"></span>
                </div>
                
                {{-- Nama Hari --}}
                <div class="d-grid text-center fw-bold" style="grid-template-columns: repeat(7, 1fr); font-size: 0.65rem; gap: 2px; color: #566a7f;">
                    <div>S</div><div>S</div><div>R</div><div>K</div><div>J</div><div>S</div><div class="text-danger">M</div>
                </div>

                {{-- Container Tanggal --}}
                <div id="side-cal-dates" class="d-grid text-center mt-2" style="grid-template-columns: repeat(7, 1fr); font-size: 0.75rem; gap: 4px; line-height: 20px; color: #566a7f;">
                    {{-- Render via JS --}}
                </div>
            </div>
        </li>
    </ul>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function renderSidebarCalendar() {
            const now = new Date();
            const month = now.getMonth();
            const year = now.getFullYear();
            const today = now.getDate();
            
            const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            
            const monthEl = document.getElementById('side-cal-month');
            const yearEl = document.getElementById('side-cal-year');
            const datesContainer = document.getElementById('side-cal-dates');

            if(!monthEl || !yearEl || !datesContainer) return;

            monthEl.innerText = monthNames[month];
            yearEl.innerText = year;

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            datesContainer.innerHTML = '';

            // Senin sebagai kolom pertama
            let startingDay = (firstDay === 0) ? 6 : firstDay - 1;

            for (let i = 0; i < startingDay; i++) {
                datesContainer.innerHTML += '<div></div>';
            }

            for (let date = 1; date <= daysInMonth; date++) {
                if (date === today) {
                    // Marker hari ini warna ungu cerah (#696cff)
                    datesContainer.innerHTML += `<div class="bg-primary rounded-circle fw-bold text-white shadow-sm">${date}</div>`;
                } else {
                    datesContainer.innerHTML += `<div class="fw-semibold">${date}</div>`;
                }
            }
        }
        renderSidebarCalendar();
    });
</script>