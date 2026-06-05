<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Karyawan;
use App\Exports\AbsensiExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Default ke hari ini jika tidak ada filter
        $tanggal_awal = $request->tanggal_awal ?? date('Y-m-d');
        $tanggal_akhir = $request->tanggal_akhir ?? date('Y-m-d');
        $search = $request->search;

        $query = Absensi::with('karyawan')
            ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir]);

        // Filter pencarian nama/NIK
        if ($search) {
            $query->whereHas('karyawan', function($q) use ($search) {
                // Filter berdasarkan nama depan, nama belakang, atau NIK
                $q->where('nama_depan', 'like', "%{$search}%")
                  ->orWhere('nama_belakang', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $data = $query->orderBy('tanggal', 'desc')->get();

        // Hitung statistik kehadiran berdasarkan rentang waktu dan filter yang aktif
        $total = Karyawan::count();
        $hadir = $data->count();
        $telat = $data->where('status', 'telat')->count();
        
        // Logika "belum absen" hanya akurat jika filternya 1 hari (hari ini)
        // Jika rentang tanggal lebih dari 1 hari, kita set ke '-' agar tidak rancu
        $belum = ($tanggal_awal == $tanggal_akhir && !$search) ? ($total - $hadir) : '-';

        return view('admin.laporan.index', compact(
            'data',
            'tanggal_awal',
            'tanggal_akhir',
            'search',
            'total',
            'hadir',
            'telat',
            'belum'
        ));
    }

    public function exportExcel(Request $request)
    {
        $tanggal_awal = $request->tanggal_awal ?? date('Y-m-d');
        $tanggal_akhir = $request->tanggal_akhir ?? date('Y-m-d');
        $search = $request->search;

        $nama_file = "Laporan_Absensi_{$tanggal_awal}_sd_{$tanggal_akhir}.xlsx";

        return Excel::download(new AbsensiExport($tanggal_awal, $tanggal_akhir, $search), $nama_file);
    }
}