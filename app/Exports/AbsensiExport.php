<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

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

    // Memetakan data baris per baris sebelum ditulis ke file Excel
    public function map($row): array
    {
        return [
            // Tambahkan spasi di depan NIK agar Excel memperlakukannya sebagai teks
            ' ' . $row->karyawan->nik,
            
            // Gabungkan nama depan dan nama belakang menjadi nama lengkap
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