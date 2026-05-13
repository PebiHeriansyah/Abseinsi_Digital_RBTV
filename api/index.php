<?php

/**
 * Entry point untuk Vercel Serverless PHP Runtime.
 * File ini memboot Laravel dan meneruskan semua request ke aplikasi.
 */

// Tampilkan error sementara untuk debugging
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

try {
    define('LARAVEL_START', microtime(true));

    // Tentukan root aplikasi
    $appRoot = __DIR__ . '/..';

    // Di Vercel, filesystem adalah read-only kecuali /tmp
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

    // Autoload Composer
    require $appRoot . '/vendor/autoload.php';

    // Boot aplikasi Laravel
    $app = require_once $appRoot . '/bootstrap/app.php';

    // Override storage path ke /tmp
    $app->useStoragePath($tmpStorage);

    // Handle request
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    )->send();

    $kernel->terminate($request, $response);

} catch (\Throwable $e) {
    // Tampilkan error untuk debugging
    http_response_code(500);
    header('Content-Type: text/plain');
    echo "=== LARAVEL ERROR ===\n\n";
    echo "Message: " . $e->getMessage() . "\n\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n\n";
    echo "=== STACK TRACE ===\n";
    echo $e->getTraceAsString() . "\n\n";
    
    // Cek juga previous exception
    if ($prev = $e->getPrevious()) {
        echo "=== PREVIOUS ERROR ===\n";
        echo "Message: " . $prev->getMessage() . "\n";
        echo "File: " . $prev->getFile() . "\n";
        echo "Line: " . $prev->getLine() . "\n";
    }
    
    // Tampilkan env vars yang terdeteksi (tanpa value sensitif)
    echo "\n=== ENV CHECK ===\n";
    echo "APP_ENV: " . (getenv('APP_ENV') ?: 'NOT SET') . "\n";
    echo "APP_KEY: " . (getenv('APP_KEY') ? 'SET' : 'NOT SET') . "\n";
    echo "DB_CONNECTION: " . (getenv('DB_CONNECTION') ?: 'NOT SET') . "\n";
    echo "DB_HOST: " . (getenv('DB_HOST') ? 'SET' : 'NOT SET') . "\n";
    echo "FILESYSTEM_DISK: " . (getenv('FILESYSTEM_DISK') ?: 'NOT SET') . "\n";
}
