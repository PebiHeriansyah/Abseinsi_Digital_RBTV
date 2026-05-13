<?php

/**
 * Konfigurasi DomPDF untuk Vercel Serverless.
 *
 * Di Vercel (dan serverless pada umumnya), hanya direktori /tmp
 * yang bisa ditulis. DomPDF membutuhkan direktori writable untuk:
 *   - font_dir   : menyimpan file font (.ttf / .afm)
 *   - font_cache : menyimpan cache metrik font
 *   - temp_dir   : file sementara saat proses PDF
 *
 * Direktori-direktori ini dibuat lebih awal di api/index.php.
 */

return [

    'show_warnings' => false,

    'public_path' => null,

    'convert_entities' => true,

    'options' => [
        // ----------------------------------------------------------------
        // Path WAJIB writable — arahkan ke /tmp agar tidak crash di Vercel
        // ----------------------------------------------------------------
        'font_dir'   => '/tmp/storage/fonts/',
        'font_cache' => '/tmp/storage/fonts/',
        'temp_dir'   => '/tmp/dompdf/',

        // ----------------------------------------------------------------
        // Chroot: izinkan DomPDF membaca dari root project DAN /tmp
        // ----------------------------------------------------------------
        'chroot' => realpath(base_path()),

        // Protocol yang diizinkan — data:// PENTING untuk base64 inline
        'allowed_protocols' => [
            'data://' => ['rules' => []],
            'file://' => ['rules' => []],
            'http://'  => ['rules' => []],
            'https://' => ['rules' => []],
        ],

        'artifactPathValidation' => null,
        'log_output_file'        => null,

        // Font subsetting off (performa lebih cepat di serverless)
        'enable_font_subsetting' => false,

        'pdf_backend'            => 'CPDF',
        'default_media_type'     => 'screen',
        'default_paper_size'     => 'a4',
        'default_paper_orientation' => 'portrait',
        'default_font'           => 'serif',
        'dpi'                    => 96,

        // Nonaktifkan PHP & remote untuk keamanan
        'enable_php'             => false,
        'enable_javascript'      => false,

        // Remote dimatikan: semua gambar kita kirim sebagai base64
        'enable_remote'          => false,
        'allowed_remote_hosts'   => null,

        'font_height_ratio'      => 1.1,
        'enable_html5_parser'    => true,
    ],

];
