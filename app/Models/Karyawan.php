<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Absensi;

class Karyawan extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal.
     * Disesuaikan dengan input: nama depan, belakang, jk, email, hp, status, alamat, foto.
     */
    protected $fillable = [
        'nik',
        'nama_depan',
        'nama_belakang',
        'jenis_kelamin',
        'email',
        'no_hp',
        'status',
        'alamat',
        'foto',
        'qr_code'
    ];

    /**
     * Aksesor untuk mendapatkan nama lengkap secara otomatis.
     * Anda bisa memanggil $karyawan->nama_lengkap di view.
     */
    public function getNamaLengkapAttribute()
    {
        return "{$this->nama_depan} {$this->nama_belakang}";
    }

    /**
     * Relasi ke model Absensi.
     * Satu karyawan memiliki banyak catatan absensi (One to Many).
     */
    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }
}