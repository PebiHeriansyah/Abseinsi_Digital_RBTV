<?php

/**
 * Entry point untuk Vercel Serverless PHP Runtime.
 * File ini memboot Laravel dan meneruskan semua request ke aplikasi.
 */

// Tentukan root aplikasi
define('LARAVEL_START', microtime(true));

// Vercel menjalankan dari direktori /var/task, kita perlu naik ke root proyek
$appRoot = __DIR__ . '/..';

// Autoload Composer
require $appRoot . '/vendor/autoload.php';

// Boot aplikasi Laravel
$app = require_once $appRoot . '/bootstrap/app.php';

// Handle request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);
