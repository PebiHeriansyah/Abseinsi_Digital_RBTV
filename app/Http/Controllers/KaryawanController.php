<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
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
            // Isi qr_code dengan NIK agar kolom tidak kosong
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
            // Sinkronisasi qr_code dengan NIK terbaru
            'qr_code' => $request->nik,
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

        // Buat URL foto — mendukung Supabase (production) dan disk lokal (development)
        $fotoUrl = null;
        if (!empty($karyawan->foto)) {
            $disk = config('filesystems.default', 'public');

            if ($disk === 'supabase') {
                // Production: gunakan Supabase public URL
                $supabasePublicUrl = env('SUPABASE_STORAGE_URL', '');
                if ($supabasePublicUrl) {
                    $fotoUrl = rtrim($supabasePublicUrl, '/') . '/' . ltrim($karyawan->foto, '/');
                }
            } else {
                // Lokal: gunakan URL dari Storage disk (public/local)
                try {
                    $fotoUrl = Storage::disk($disk)->url($karyawan->foto);
                } catch (\Exception $e) {
                    $fotoUrl = null;
                }
            }
        }

        // QR code: generate sebagai data URI agar tidak bergantung pada internet
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($karyawan->nik);

        // URL absolut logo untuk memastikan load benar di semua environment
        $logoUrl = rtrim(config('app.url'), '/') . '/images/RBTV.png';

        return view('admin.karyawan.kartu', compact('karyawan', 'fotoUrl', 'qrUrl', 'logoUrl'));
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