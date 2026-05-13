<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RBTV Absensi</title>

    <!-- CSS -->
    <link href="{{ asset('monster/css/style.min.css') }}" rel="stylesheet">
</head>

<body>

<div id="main-wrapper">

    {{-- HEADER --}}
    @include('layouts.monster.header')

    {{-- SIDEBAR --}}
    @include('layouts.monster.sidebar')

    {{-- CONTENT --}}
    <div class="page-wrapper">
        <div class="container-fluid">

            @yield('content')

        </div>
    </div>

    {{-- FOOTER --}}
    @include('layouts.monster.footer')

</div>

<!-- JS -->
<script src="{{ asset('monster/assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('monster/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('monster/js/app.min.js') }}"></script>

</body>
</html>