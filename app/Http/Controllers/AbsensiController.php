<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Absensi;
use App\Models\Lokasi;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index()
    {
        return view('absensi.scan');
    }

    public function store(Request $request)
    {
        $nik = $request->nik;
        $lat = $request->latitude;
        $lng = $request->longitude;

        // Ambil waktu saat ini berdasarkan zona waktu Jakarta
        $waktuSekarang = Carbon::now('Asia/Jakarta');
        $today = $waktuSekarang->toDateString();
        $jamSekarang = $waktuSekarang->format('H:i:s');

        // Cek data karyawan berdasarkan NIK
        $karyawan = Karyawan::where('nik', $nik)->first();

        if (!$karyawan) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR tidak valid'
            ]);
        }

        // Validasi koordinat GPS
        if (!$lat || !$lng || $lat == 0 || $lng == 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'GPS tidak valid, pastikan izin lokasi diaktifkan'
            ]);
        }

        // Cek pengaturan lokasi kantor
        $lokasi = Lokasi::first();

        if (!$lokasi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Titik lokasi kantor belum disetting'
            ]);
        }

        // Hitung jarak antara posisi karyawan dan kantor (Haversine formula)
        $jarak = $this->hitungJarak(
            $lat,
            $lng,
            $lokasi->latitude,
            $lokasi->longitude
        );

        // Toleransi GPS sebesar 50 meter untuk mengakomodasi ketidakakuratan sinyal
        $toleransi = 50;

        if ($jarak > ($lokasi->radius + $toleransi)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Diluar area radius absen (' . round($jarak) . ' meter)'
            ]);
        }

        // Cek apakah karyawan sudah memiliki catatan absensi hari ini
        $absensi = Absensi::where('karyawan_id', $karyawan->id)
            ->where('tanggal', $today)
            ->first();

        // Cegah double scan: tolak jika scan dilakukan dalam interval kurang dari 10 detik
        if ($absensi && $absensi->updated_at > $waktuSekarang->copy()->subSeconds(10)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sistem sedang memproses, tunggu sebentar...'
            ]);
        }

        // --- Logika Absen Masuk (Pagi) ---
        if (!$absensi) {
            
            // Aturan Jam: 06:00:00 - 08:00:00 (Hadir)
            if ($jamSekarang >= '06:00:00' && $jamSekarang <= '08:00:00') {
                $status = 'hadir';
            } 
            // Aturan Jam: 08:00:01 - 09:00:00 (Telat)
            elseif ($jamSekarang > '08:00:00' && $jamSekarang <= '09:00:00') {
                $status = 'telat';
            } 
            // Jika absen sebelum jam 6 pagi atau setelah jam 9 pagi -> DITOLAK
            else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Absen masuk hanya dibuka dari 06:00 - 09:00 WIB'
                ]);
            }

            // Simpan Absen Masuk
            Absensi::create([
                'karyawan_id' => $karyawan->id,
                'tanggal' => $today,
                'jam_masuk' => $jamSekarang,
                'latitude' => $lat,
                'longitude' => $lng,
                'status' => $status
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil absen masuk (' . strtoupper($status) . ')'
            ]);
        }

        // --- Logika Absen Pulang (Sore) ---
        if (!$absensi->jam_keluar) {
            
            // Keamanan tambahan: Pastikan dia punya jam masuk (tidak absen bolong)
            if (!$absensi->jam_masuk) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak bisa absen pulang karena tidak absen masuk hari ini'
                ]);
            }

            // Aturan Jam Pulang: 16:00:00 - 18:00:00
            if ($jamSekarang >= '16:00:00' && $jamSekarang <= '18:00:00') {
                $absensi->update([
                    'jam_keluar' => $jamSekarang
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Absen pulang berhasil'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Absen pulang hanya dibuka dari 16:00 - 18:00 WIB'
                ]);
            }
        }

        // Jika sudah absen masuk dan absen pulang
        return response()->json([
            'status' => 'error',
            'message' => 'Anda sudah menyelesaikan absensi hari ini'
        ]);
    }

    private function hitungJarak($lat1, $lon1, $lat2, $lon2)
    {
        $earth_radius = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earth_radius * $c;
    }
}