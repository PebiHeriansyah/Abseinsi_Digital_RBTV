<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Absensi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today('Asia/Jakarta')->toDateString();
        
        // 1. Data Dasar
        $totalKaryawan = Karyawan::count();
        $hadir = Absensi::where('tanggal', $today)->where('status', 'hadir')->count();
        $telat = Absensi::where('tanggal', $today)->where('status', 'telat')->count();
        
        $sudahAbsenCount = Absensi::where('tanggal', $today)->count();
        $belumAbsen = max(0, $totalKaryawan - $sudahAbsenCount);

        // 2. Logika Persentase
        $persenHadir = $totalKaryawan > 0 ? round((($hadir + $telat) / $totalKaryawan) * 100) : 0;
        $persenTelat = $totalKaryawan > 0 ? round(($telat / $totalKaryawan) * 100) : 0;

        // 3. Data Grafik Trend (7 Hari Terakhir)
        $labelHari = [];
        $dataHari = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = Carbon::today('Asia/Jakarta')->subDays($i)->toDateString();
            $labelHari[] = Carbon::today('Asia/Jakarta')->subDays($i)->isoFormat('D MMM');
            $dataHari[] = Absensi::where('tanggal', $tgl)->count();
        }

        // 4. Data Grafik Rasio (5 Bulan Terakhir)
        $labelBulan = [];
        $dataBulanan = [];
        for ($i = 4; $i >= 0; $i--) {
            $bulanTarget = Carbon::today('Asia/Jakarta')->subMonths($i);
            $labelBulan[] = $bulanTarget->isoFormat('MMMM');
            $dataBulanan[] = Absensi::whereMonth('tanggal', $bulanTarget->month)
                ->whereYear('tanggal', $bulanTarget->year)
                ->count();
        }

        // 5. Log Aktivitas Terbaru
        $recentAbsensi = Absensi::with('karyawan')
            ->where('tanggal', $today)
            ->latest()
            ->take(7)
            ->get();

        return view('admin.dashboard', compact(
            'totalKaryawan', 'hadir', 'telat', 'belumAbsen', 
            'recentAbsensi', 'persenHadir', 'persenTelat', 
            'labelHari', 'dataHari', 'labelBulan', 'dataBulanan'
        ));
    }
}