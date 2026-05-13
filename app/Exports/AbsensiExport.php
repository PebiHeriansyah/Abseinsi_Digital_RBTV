<?php

namespace App\Exports;

use App\Models\Absensi; // Pastikan model ini sesuai dengan model absensi Anda
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

// Tambahkan implementasi WithMapping dan WithColumnFormatting
class AbsensiExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting
{
    protected $tanggal_awal;
    protected $tanggal_akhir;
    protected $search;

    public function __construct($tanggal_awal = null, $tanggal_akhir = null, $search = null)
    {
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
        $this->search = $search;
    }

    public function collection()
    {
        // Pastikan Anda melakukan eager loading relasi karyawan
        $query = Absensi::with('karyawan')->latest('tanggal');

        // Filter tanggal
        if ($this->tanggal_awal && $this->tanggal_akhir) {
            $query->whereBetween('tanggal', [$this->tanggal_awal, $this->tanggal_akhir]);
        }

        // Filter pencarian
        if ($this->search) {
            $query->whereHas('karyawan', function ($q) {
                $q->where('nama_depan', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_belakang', 'like', '%' . $this->search . '%')
                  ->orWhere('nik', 'like', '%' . $this->search . '%');
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'NIK',
            'Nama Karyawan',
            'Tanggal',
            'Jam Masuk',
            'Jam Keluar',
            'Status'
        ];
    }

    // Fungsi map() ini berfungsi mengatur data baris per baris sebelum masuk ke Excel
    public function map($row): array
    {
        return [
            // 🔥 Trik NIK: Tambahkan spasi di depan agar Excel membacanya sebagai Teks murni
            ' ' . $row->karyawan->nik,
            
            // 🔥 Trik Nama: Gabungkan nama depan dan nama belakang
            $row->karyawan->nama_depan . ' ' . $row->karyawan->nama_belakang,
            
            $row->tanggal,
            $row->jam_masuk ?? '-',
            $row->jam_keluar ?? '-',
            $row->status,
        ];
    }

    // Memaksa kolom A (NIK) berformat teks di dalam sel Excel
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
        ];
    }
}