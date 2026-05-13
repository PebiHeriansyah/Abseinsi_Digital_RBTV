<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// TEMPORARY: Migration endpoint (hapus setelah berhasil migrate)
Route::get('/setup-db', function () {
    $secret = request('key');
    $expected = env('MIGRATE_SECRET', 'rbtv-migrate-2024');
    
    if ($secret !== $expected) {
        abort(403, 'Unauthorized');
    }
    
    $output = [];
    try {
        Artisan::call('migrate', ['--force' => true]);
        $output[] = 'migrate: ' . Artisan::output();
        
        Artisan::call('db:seed', ['--force' => true]);
        $output[] = 'seed: ' . Artisan::output();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Database migrated and seeded!',
            'output' => $output,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'output' => $output,
        ], 500);
    }
});
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;

// AREA SCANNER (HANYA GUEST)
Route::middleware(['guest'])->group(function () {
    Route::get('/', [AbsensiController::class, 'index'])->name('home');
    Route::get('/absensi', function () {
        return redirect('/');
    });
});

// PROSES SCAN (POST)
Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');

// AREA ADMIN (WAJIB LOGIN)
Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('/karyawan', KaryawanController::class);
    Route::get('/karyawan/{id}/cetak', [KaryawanController::class, 'cetak'])->name('karyawan.cetak');

    Route::get('/lokasi', [LokasiController::class, 'index'])->name('lokasi.index');
    Route::post('/lokasi', [LokasiController::class, 'update'])->name('lokasi.update');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export', [LaporanController::class, 'exportExcel'])->name('laporan.export');

    Route::get('/kalender', function () {
        return view('admin.kalender.index');
    })->name('kalender.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';