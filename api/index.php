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

    // Salin $_SERVER dan $_ENV ke putenv agar Laravel bisa membacanya dengan getenv()
    foreach ($_ENV as $key => $value) {
        putenv("$key=$value");
    }
    foreach ($_SERVER as $key => $value) {
        if (is_string($value)) {
            putenv("$key=$value");
        }
    }

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
    
    // Override path cache agar menggunakan /tmp (penting untuk Laravel 11 di Vercel)
    $app->useBootstrapPath('/tmp/bootstrap');

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
        echo "Line: " . $prev->getLine() . "\n\n";
    }
    
    // Tampilkan env vars yang terdeteksi (HANYA KUNCI, BUKAN NILAI RAHASIANYA)
    echo "=== $_ENV KEYS ===\n";
    echo implode(', ', array_keys($_ENV)) . "\n\n";
    
    echo "=== $_SERVER KEYS ===\n";
    echo implode(', ', array_keys($_SERVER)) . "\n\n";
}
