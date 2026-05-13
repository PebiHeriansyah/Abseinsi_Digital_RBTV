<?php

/**
 * Entry point untuk Vercel Serverless PHP Runtime.
 * File ini memboot Laravel dan meneruskan semua request ke aplikasi.
 */

define('LARAVEL_START', microtime(true));

// Tentukan root aplikasi
$appRoot = __DIR__ . '/..';

// Di Vercel, filesystem adalah read-only kecuali /tmp
// Kita perlu membuat symlink storage ke /tmp agar Laravel bisa menulis
$tmpStorage = '/tmp/storage';
$tmpBootstrapCache = '/tmp/bootstrap/cache';

// Buat direktori yang dibutuhkan di /tmp
$dirs = [
    $tmpStorage . '/app/public',
    $tmpStorage . '/framework/cache/data',
    $tmpStorage . '/framework/sessions',
    $tmpStorage . '/framework/views',
    $tmpStorage . '/logs',
    $tmpBootstrapCache,
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Override storage path ke /tmp
$_ENV['APP_STORAGE_PATH'] = $tmpStorage;

// Autoload Composer
require $appRoot . '/vendor/autoload.php';

// Boot aplikasi Laravel
$app = require_once $appRoot . '/bootstrap/app.php';

// Override storage path
$app->useStoragePath($tmpStorage);

// Override bootstrap cache path
$app->instance('path.config_cache', $tmpBootstrapCache . '/config.php');
$app->instance('path.routes_cache', $tmpBootstrapCache . '/routes-v7.php');
$app->instance('path.events_cache', $tmpBootstrapCache . '/events.php');

// Handle request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);
