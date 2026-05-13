<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = Karyawan::latest();

        // Logika Filter Pencarian Teks
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_depan', 'like', '%' . $request->search . '%')
                ->orWhere('nama_belakang', 'like', '%' . $request->search . '%')
                ->orWhere('nik', 'like', '%' . $request->search . '%');
            });
        }

        // Logika Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data = $query->get();
        return view('admin.karyawan.index', compact('data'));
    }

    public function create()
    {
        return view('admin.karyawan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:karyawans,nik|max:16',
            'nama_depan' => 'required',
            'nama_belakang' => 'required',
            'jenis_kelamin' => 'required',
            'email' => 'required|email|unique:karyawans,email',
            'no_hp' => 'required',
            'status' => 'required',
            'alamat' => 'required',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // Gunakan disk yang dikonfigurasi: 'public' (lokal) atau 'supabase' (production)
        $disk = config('filesystems.default', 'public');
        $fotoPath = $request->file('foto')->store('foto', $disk);

        Karyawan::create([
            'nik' => $request->nik,
            'nama_depan' => $request->nama_depan,
            'nama_belakang' => $request->nama_belakang,
            'jenis_kelamin' => $request->jenis_kelamin,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'status' => $request->status,
            'alamat' => $request->alamat,
            'foto' => $fotoPath,
            // 🔥 FIX ERROR 1364: Kita isi qr_code dengan NIK agar database tidak menolak
            'qr_code' => $request->nik 
        ]);

        return redirect()->route('karyawan.index')
            ->with('success', 'Data karyawan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        
        return view('admin.karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $request->validate([
            'nama_depan' => 'required',
            'nama_belakang' => 'required',
            'jenis_kelamin' => 'required',
            'no_hp' => 'required',
            'status' => 'required',
            'alamat' => 'required',
            'nik' => 'required|max:16|unique:karyawans,nik,' . $karyawan->id, 
            'email' => 'required|email|unique:karyawans,email,' . $karyawan->id,
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048' 
        ]);

        $dataUpdate = [
            'nik' => $request->nik,
            'nama_depan' => $request->nama_depan,
            'nama_belakang' => $request->nama_belakang,
            'jenis_kelamin' => $request->jenis_kelamin,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'status' => $request->status,
            'alamat' => $request->alamat,
            // 🔥 PASTIKAN TERISI JUGA SAAT UPDATE
            'qr_code' => $request->nik 
        ];

        // Jika ada upload foto baru
        if ($request->hasFile('foto')) {
            $disk = config('filesystems.default', 'public');
            if ($karyawan->foto) {
                Storage::disk($disk)->delete($karyawan->foto);
            }
            
            $fotoPath = $request->file('foto')->store('foto', $disk);
            $dataUpdate['foto'] = $fotoPath;
        }

        $karyawan->update($dataUpdate);

        return redirect()->route('karyawan.index')
            ->with('success', 'Data karyawan berhasil diperbarui');
    }

    public function cetak($id)
    {
        $karyawan = Karyawan::findOrFail($id);

        // =====================================================
        // FOTO KARYAWAN
        // Ambil langsung dari URL publik Supabase via cURL
        // (lebih reliable daripada Storage::disk() di serverless)
        // =====================================================
        $fotoBase64 = null;
        if (!empty($karyawan->foto)) {
            try {
                $supabasePublicUrl = env('SUPABASE_STORAGE_URL', '');
                if ($supabasePublicUrl) {
                    $fotoUrl  = rtrim($supabasePublicUrl, '/') . '/' . ltrim($karyawan->foto, '/');
                    $fotoData = $this->fetchUrlAsBase64($fotoUrl);
                    if ($fotoData) {
                        $ext = strtolower(pathinfo($karyawan->foto, PATHINFO_EXTENSION)) ?: 'jpeg';
                        $mime = in_array($ext, ['jpg', 'jpeg']) ? 'jpeg' : $ext;
                        $fotoBase64 = 'data:image/' . $mime . ';base64,' . $fotoData;
                    }
                }
            } catch (\Exception $e) {
                $fotoBase64 = null;
            }
        }

        // =====================================================
        // QR CODE
        // Gunakan cURL (file_get_contents diblokir di Vercel)
        // =====================================================
        $qrBase64 = null;
        try {
            $qrUrl  = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($karyawan->nik);
            $qrData = $this->fetchUrlAsBase64($qrUrl);
            if ($qrData) {
                $qrBase64 = 'data:image/png;base64,' . $qrData;
            }
        } catch (\Exception $e) {
            $qrBase64 = null;
        }

        // =====================================================
        // LOGO — file lokal, encode base64 agar DomPDF render
        // (DomPDF tidak bisa load file via path relatif di Vercel)
        // =====================================================
        $logoBase64 = null;
        $logoPath   = public_path('images/RBTV.png');
        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }

        // Pastikan DomPDF menulis font ke /tmp (writable di Vercel)
        // Path ini di-configure di config/dompdf.php
        $pdf = Pdf::loadView('admin.karyawan.kartu', compact('karyawan', 'fotoBase64', 'qrBase64', 'logoBase64'))
            ->setPaper([0, 0, 153.07, 242.65], 'portrait');

        return $pdf->stream('kartu-' . $karyawan->nama_depan . '.pdf');
    }

    /**
     * Fetch konten URL menggunakan cURL dan return sebagai base64.
     * Digunakan karena file_get_contents() untuk URL eksternal
     * tidak berfungsi di serverless environment (Vercel).
     */
    private function fetchUrlAsBase64(string $url): ?string
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (compatible; AbsensiRBTV/1.0)',
        ]);
        $data     = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($data === false || $httpCode < 200 || $httpCode >= 300) {
            return null;
        }

        return base64_encode($data);
    }

    public function destroy(Request $request, $id)
    {
        // 1. Ambil password yang dikirim dari SweetAlert
        $passwordKonfirmasi = $request->input('password_konfirmasi');

        // 2. Validasi apakah password cocok dengan akun admin yang sedang login
        if (!Hash::check($passwordKonfirmasi, Auth::user()->password)) {
            return back()->with('error', 'Gagal menghapus data. Password yang Anda masukkan salah.');
        }

        // 3. Jika password benar, lanjutkan proses hapus
        $karyawan = Karyawan::findOrFail($id);
        
        // Hapus foto jika ada
        if ($karyawan->foto) {
            $disk = config('filesystems.default', 'public');
            Storage::disk($disk)->delete($karyawan->foto);
        }
        
        $karyawan->delete();

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil dihapus.');
    }
}