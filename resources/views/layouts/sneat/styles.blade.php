<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

<link rel="stylesheet" href="{{ asset('sneat/assets/vendor/fonts/boxicons.css') }}" />

<link rel="stylesheet" href="{{ asset('sneat/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
<link rel="stylesheet" href="{{ asset('sneat/assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
<link rel="stylesheet" href="{{ asset('sneat/assets/css/demo.css') }}" />

<link rel="stylesheet" href="{{ asset('sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

@stack('page-style')

<style>
    /* Menyesuaikan skala / ukuran keseluruhan website di Desktop (agar tidak terlalu besar) */
    @media (min-width: 992px) {
        html {
            zoom: 0.85;
        }
        html, body {
            height: 117.647vh !important; /* Kompensasi untuk zoom 0.85 */
            min-height: 117.647vh !important;
        }
    }

    /* Mengubah warna latar belakang utama dan teks */
    html.dark-style body {
        background-color: #232333 !important;
        color: #a1b0cb !important;
    }
    
    /* Mengubah warna Card, Sidebar, Navbar, dan Footer */
    html.dark-style .card,
    html.dark-style .bg-menu-theme,
    html.dark-style .bg-navbar-theme,
    html.dark-style .bg-footer-theme,
    html.dark-style .dropdown-menu {
        background-color: #2b2c40 !important;
        color: #a1b0cb !important;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.4) !important;
    }

    /* Mengubah warna teks judul dan label */
    html.dark-style h1, html.dark-style h2, html.dark-style h3, 
    html.dark-style h4, html.dark-style h5, html.dark-style h6,
    html.dark-style .text-dark,
    html.dark-style .card-title,
    html.dark-style .fw-semibold {
        color: #cbcbe2 !important;
    }

    /* Mengubah warna teks menu sidebar */
    html.dark-style .menu-link,
    html.dark-style .menu-header-text {
        color: #a1b0cb !important;
    }

    /* Mengubah warna tabel dan garis pembatas */
    html.dark-style .table,
    html.dark-style .border,
    html.dark-style .border-bottom,
    html.dark-style .dropdown-divider {
        border-color: #444564 !important;
    }
    
    html.dark-style .table-dark {
        background-color: #1c1c28 !important;
    }

    /* Input form */
    html.dark-style .form-control,
    html.dark-style .input-group-text,
    html.dark-style .form-select {
        background-color: #232333 !important;
        border-color: #444564 !important;
        color: #a1b0cb !important;
    }

    /* Memperbaiki teks di dalam input saat fokus */
    html.dark-style .form-control:focus {
        color: #cbcbe2 !important;
        background-color: #2b2c40 !important;
    }
    
    /* Hover menu sidebar aktif */
    html.dark-style .menu-item.active > .menu-link {
        background-color: rgba(105, 108, 255, 0.16) !important;
        color: #696cff !important;
    }

    /* Modal dialog */
    html.dark-style .modal-content {
        background-color: #2b2c40 !important;
        color: #a1b0cb !important;
    }
</style>

<script src="{{ asset('sneat/assets/vendor/js/helpers.js') }}"></script>
<script src="{{ asset('sneat/assets/js/config.js') }}"></script>