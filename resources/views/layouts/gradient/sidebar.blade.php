{{-- resources/views/layouts/gradient/sidebar.blade.php --}}
<nav class="pc-sidebar">
    <div class="navbar-wrapper">

        <style>
            .pc-sidebar {
                width: 260px;
            }

            .pc-sidebar .m-header {
                padding: 18px 20px;
                text-align: center;
            }

            .pc-item.pc-caption {
                padding: 12px 20px;
                margin-top: 10px;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .pc-item.pc-caption label {
                font-size: 12px;
                font-weight: 700;
                text-transform: uppercase;
                color: #6c757d;
                letter-spacing: .7px;
                margin: 0;
            }

            .pc-item.pc-caption i {
                color: #adb5bd;
                font-size: 14px;
            }

            .pc-navbar .pc-link {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 10px 16px;
                color: inherit;
                text-decoration: none;
            }

            .pc-navbar .pc-link:hover {
                background: rgba(13,110,253,0.06);
            }

            .pc-navbar .pc-item.active .pc-link {
                background: rgba(13,110,253,0.14);
                font-weight: 600;
            }

            .pc-sidebar hr {
                border: 0;
                border-top: 1px solid #eef1f5;
                margin: 8px 0;
            }
        </style>

        <div class="navbar-content">
            <ul class="pc-navbar list-unstyled mb-0">

                {{-- ===== NAVIGASI UTAMA ===== --}}
                <li class="pc-item pc-caption">
                    <i class="ti ti-dashboard"></i>
                    <label>Navigasi Utama</label>
                </li>

                {{-- DASHBOARD --}}
                <li class="pc-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-home"></i></span>
                        <span class="pc-mtext">Dashboard</span>
                    </a>
                </li>

                {{-- KARYAWAN --}}
                <li class="pc-item {{ request()->routeIs('karyawan.*') ? 'active' : '' }}">
                    <a href="{{ route('karyawan.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-users"></i></span>
                        <span class="pc-mtext">Data Karyawan</span>
                    </a>
                </li>

                {{-- TAMBAH KARYAWAN --}}
                <li class="pc-item {{ request()->routeIs('karyawan.create') ? 'active' : '' }}">
                    <a href="{{ route('karyawan.create') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-user-plus"></i></span>
                        <span class="pc-mtext">Tambah Karyawan</span>
                    </a>
                </li>

                {{-- LOKASI --}}
                <li class="pc-item {{ request()->routeIs('lokasi.*') ? 'active' : '' }}">
                    <a href="{{ route('lokasi.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-map-pin"></i></span>
                        <span class="pc-mtext">Atur Lokasi</span>
                    </a>
                </li>

                <li class="my-2"><hr></li>

                {{-- ===== AKUN ===== --}}
                <li class="pc-item pc-caption">
                    <i class="ti ti-user"></i>
                    <label>Akun</label>
                </li>

                {{-- LOGOUT --}}
                <li class="pc-item">
                    <form action="{{ route('logout') }}" method="POST" class="w-100">
                        @csrf
                        <button type="submit"
                            class="pc-link d-flex align-items-center px-3 py-2 border-0 bg-transparent w-100 text-start">
                            <span class="pc-micon"><i class="ti ti-power"></i></span>
                            <span class="pc-mtext">Logout</span>
                        </button>
                    </form>
                </li>

            </ul>
        </div>
    </div>
</nav>