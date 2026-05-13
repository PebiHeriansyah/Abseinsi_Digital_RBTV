<!DOCTYPE html>
<html lang="id">

<head>
    <title>EduSafe Admin - @yield('title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="{{ asset('gradient/assets/images/favicon.ico') }}">

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- ICON -->
    <link rel="stylesheet" href="{{ asset('gradient/assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('gradient/assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('gradient/assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('gradient/assets/fonts/material.css') }}">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('gradient/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('gradient/assets/css/style-preset.css') }}">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* 🔥 HILANGKAN ARROW SIDEBAR */
        .pc-arrow,
        .pc-arrow-left,
        .pc-arrow-right,
        .pc-h-arrow,
        .pc-navbar-arrow {
            display: none !important;
        }

        /* RAPATIN CONTENT */
        .pc-content {
            padding-top: 15px !important;
        }
    </style>

    @yield('style')
</head>

<body data-pc-preset="preset-1"
      data-pc-sidebar-caption="true"
      data-pc-direction="ltr"
      data-pc-theme="light">

    <!-- LOADER -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <!-- SIDEBAR & NAVBAR -->
    @include('layouts.gradient.sidebar')
    @include('layouts.gradient.navbar')

    <!-- CONTENT -->
    <div class="pc-container">
        <div class="pc-content">

            <!-- HEADER -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">

                        <div class="col-md-12">

                            <!-- JUDUL -->
                            <div class="page-header-title">
                                <h5 class="m-b-10">@yield('title')</h5>
                            </div>

                            <!-- BREADCRUMB (FIX TANPA STAFF) -->
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ url('/dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    @yield('title')
                                </li>
                            </ul>

                        </div>

                    </div>
                </div>
            </div>

            <!-- MAIN CONTENT -->
            <div class="row">
                <div class="col-12">
                    @yield('content')
                </div>
            </div>

        </div>
    </div>

    <!-- SCRIPT -->
    <script src="{{ asset('gradient/assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('gradient/assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('gradient/assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('gradient/assets/js/pcoded.js') }}"></script>
    <script src="{{ asset('gradient/assets/js/plugins/feather.min.js') }}"></script>

    <script>
        // 🔥 Paksa hapus arrow kalau muncul lagi
        document.querySelectorAll(
            ".pc-arrow, .pc-arrow-left, .pc-arrow-right, .pc-h-arrow"
        ).forEach(el => el.remove());
    </script>

    @stack('scripts')

</body>
</html>