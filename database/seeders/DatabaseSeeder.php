<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Lokasi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Seeder ini membuat akun admin dan lokasi kantor RBTV.
     */
    public function run(): void
    {
        // =========================================================
        // Admin User
        // =========================================================
        User::firstOrCreate(
            ['email' => 'admin@rbtv.com'],
            [
                'name'     => 'Admin RBTV',
                'email'    => 'admin@rbtv.com',
                'password' => Hash::make('Admin@RBTV2024'),
            ]
        );

        // =========================================================
        // Lokasi Kantor (default: titik koordinat kantor RBTV)
        // Sesuaikan latitude & longitude dengan lokasi kantor Anda.
        // =========================================================
        Lokasi::firstOrCreate(
            ['nama_lokasi' => 'Kantor RBTV'],
            [
                'nama_lokasi' => 'Kantor RBTV',
                'latitude'    => -6.914744,   // ← Ganti sesuai koordinat kantor
                'longitude'   => 107.609810,  // ← Ganti sesuai koordinat kantor
                'radius'      => 100,          // 100 meter radius
            ]
        );
    }
}
