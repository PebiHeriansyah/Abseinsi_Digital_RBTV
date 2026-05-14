<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Absensi RBTV') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Poppins', sans-serif;
            background-color: #f4f5fa;
        }

        .split-layout {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Panel Kiri (Gambar) - 50% */
        .split-left {
            flex: 1; 
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        /* Ukuran gambar dibuat lebih kecil (40%) agar lebih proporsional */
        .split-left img {
            max-width: 60%; 
            height: auto
        }

        /* Panel Kanan (Form Biru) - 50% */
        .split-right {
            flex: 1; 
            background-color: #696cff; 
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .bg-circles {
            position: absolute;
            bottom: -100px;
            right: -100px;
            width: 450px;
            height: 450px;
            border: 2px solid rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            z-index: 1;
        }

        /* Card Form */
        .login-card {
            background: #ffffff;
            padding: 2rem 1.8rem;
            border-radius: 16px;
            width: 100%;
            max-width: 330px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 10;
            text-align: center;
        }

        .login-card h1 {
            font-size: 28px;
            font-weight: 700;
            color: #323259;
            margin-bottom: 8px;
        }

        .login-card p.subtitle {
            color: #8898aa;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .input-group {
            position: relative;
            margin-bottom: 18px;
            text-align: left; 
        }
        .input-group .icon-left {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #696cff;
            font-size: 20px;
        }
        .input-group input {
            width: 100%;
            padding: 14px 20px 14px 52px;
            border: 1px solid #d9dee3;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            box-sizing: border-box;
            color: #435971;
            transition: 0.3s;
        }
        .input-group input:focus {
            border-color: #696cff;
            box-shadow: 0 0 0 0.25rem rgba(105, 108, 255, 0.15);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background-color: #696cff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }
        .btn-submit:hover {
            background-color: #5f61e6;
            transform: translateY(-1px);
        }

        .forgot-link {
            display: inline-block;
            margin-top: 25px;
            font-size: 14px;
            color: #696cff;
            text-decoration: none;
            font-weight: 500;
        }
        .forgot-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 992px) {
            .split-left { display: none; }
            .split-right { padding: 20px; }
        }
    </style>
</head>
<body>
    {{ $slot }}
</body>
</html>