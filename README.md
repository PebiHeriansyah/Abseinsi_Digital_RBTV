# 📋 Absensi Digital RBTV

Sistem Absensi Digital berbasis web untuk **RBTV (Raakyat Bengkulu Televisi)**, dibangun menggunakan **Laravel 13**. Sistem ini memungkinkan karyawan melakukan absensi secara mandiri melalui pemindaian QR Code yang tervalidasi dengan **GPS real-time**, serta menyediakan panel administrasi lengkap untuk pengelolaan data karyawan dan laporan kehadiran.

---

## 🗂️ Daftar Isi

- [Definisi Sistem](#-definisi-sistem)
- [Fitur Utama](#-fitur-utama)
- [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
- [Persyaratan Sistem](#-persyaratan-sistem)
- [Panduan Instalasi](#-panduan-instalasi)
- [Cara Penggunaan](#-cara-penggunaan)
- [Konfigurasi Lanjutan](#-konfigurasi-lanjutan)
- [Deployment ke Production](#-deployment-ke-production)
- [Tim Pengembang](#-tim-pengembang)

---

## 📌 Definisi Sistem

**Absensi Digital RBTV** adalah aplikasi presensi digital yang menggantikan sistem absensi manual (buku tanda tangan / mesin finger print). Sistem ini dirancang dengan dua area utama:

| Area | Akses | Fungsi |
|------|-------|--------|
| **Scanner Page** | Publik (Guest) | Halaman scan QR Code untuk karyawan |
| **Admin Panel** | Login Wajib | Manajemen karyawan, laporan, dan pengaturan sistem |

### Alur Kerja Absensi

```
Karyawan membuka Scanner Page
        ↓
Sistem meminta izin GPS / Lokasi
        ↓
Karyawan scan QR Code kartu karyawan
        ↓
Sistem validasi: NIK + GPS + Radius + Jam
        ↓
Absen Masuk (06:00–09:00) / Absen Pulang (16:00–18:00)
        ↓
Data tersimpan ke database
```

### Aturan Absensi

- **Absen Masuk Hadir** : Pukul `06:00 – 08:00 WIB`
- **Absen Masuk Telat** : Pukul `08:01 – 09:00 WIB`
- **Absen Pulang**       : Pukul `16:00 – 18:00 WIB`
- **Batas Radius GPS**   : Sesuai pengaturan admin (ditambah toleransi 50 meter)
- **Anti Double Scan**   : Interval minimal 10 detik antar scan

---

## ✨ Fitur Utama

### 🔹 Area Scanner (Karyawan)
- Scan QR Code via kamera perangkat (mobile / desktop)
- Validasi lokasi GPS secara real-time
- Pengecekan radius jarak dari kantor
- Feedback instan (berhasil / gagal + pesan keterangan)
- Mendukung absen masuk dan absen pulang dalam satu hari

### 🔹 Panel Admin
- **Dashboard** — Statistik kehadiran hari ini, grafik tren 7 hari, grafik rasio 5 bulan, log aktivitas terbaru
- **Manajemen Karyawan** — CRUD data karyawan lengkap (NIK, nama, jenis kelamin, email, no. HP, alamat, foto, status aktif/nonaktif)
- **Cetak Kartu Karyawan** — Kartu ID dengan QR Code yang dapat dicetak sebagai PDF
- **Pengaturan Lokasi** — Menentukan titik koordinat kantor dan radius absensi
- **Laporan Kehadiran** — Filter laporan berdasarkan tanggal & nama, ekspor ke Excel (`.xlsx`)
- **Kalender** — Tampilan kalender kehadiran bulanan
- **Manajemen Profil** — Ubah nama dan password akun admin

### 🔹 Keamanan
- Autentikasi admin via Laravel Breeze
- Konfirmasi password saat hapus data karyawan
- Validasi GPS untuk mencegah absen dari luar kantor
- Proteksi CSRF pada semua form

---

## 🛠️ Teknologi yang Digunakan

| Komponen | Teknologi |
|----------|-----------|
| Backend Framework | Laravel 13 (PHP 8.3) |
| Frontend | Blade Template + Sneat Admin Theme |
| CSS Framework | Bootstrap 5 + Tailwind CSS |
| Database Lokal | MySQL 8 |
| Database Production | PostgreSQL (Supabase / Neon) |
| Storage Lokal | Laravel Public Disk |
| Storage Production | Supabase Storage (S3-compatible) |
| PDF Generator | barryvdh/laravel-dompdf |
| Export Excel | maatwebsite/excel |
| QR Code | simplesoftwareio/simple-qrcode |
| Build Tool | Vite |
| Deployment | Vercel (Serverless) |

---

## 📦 Persyaratan Sistem

Sebelum instalasi, pastikan sistem Anda memenuhi persyaratan berikut:

- **PHP** `>= 8.3` dengan ekstensi: `pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `gd`, `fileinfo`
- **Composer** `>= 2.x`
- **Node.js** `>= 20.x` dan **NPM**
- **MySQL** `>= 8.0` (untuk pengembangan lokal)
- **Web Server**: Apache / Nginx / Laragon (Windows)

---

## 🚀 Panduan Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/PebiHeriansyah/Abseinsi_Digital_RBTV.git
cd Abseinsi_Digital_RBTV
```

### 2. Install Dependensi PHP

```bash
composer install
```

### 3. Install Dependensi Node.js

```bash
npm install
```

### 4. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Kemudian buka file `.env` dan sesuaikan konfigurasi berikut:

```env
APP_NAME="Absensi RBTV"
APP_URL=http://localhost

# Koneksi Database MySQL (lokal)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=absensi_rbtv
DB_USERNAME=root
DB_PASSWORD=

# Storage (gunakan 'public' untuk lokal)
FILESYSTEM_DISK=public
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Buat Database

Buat database MySQL dengan nama `absensi_rbtv`, lalu jalankan migrasi:

```bash
php artisan migrate
```

### 7. Buat Storage Link

Agar foto karyawan dapat diakses secara publik:

```bash
php artisan storage:link
```

### 8. Buat Akun Admin

Buat akun admin pertama melalui Artisan Tinker:

```bash
php artisan tinker
```

Kemudian jalankan perintah berikut di dalam Tinker:

```php
\App\Models\User::create([
    'name'     => 'Administrator',
    'email'    => 'admin@rbtv.com',
    'password' => bcrypt('password123'),
]);
exit
```

### 9. Build Assets Frontend

```bash
npm run build
```

### 10. Jalankan Aplikasi

```bash
php artisan serve
```

Akses aplikasi di browser: **http://localhost:8000**

---

## 📖 Cara Penggunaan

### Untuk Karyawan — Absensi via QR Code

1. **Buka halaman utama** aplikasi di browser perangkat Anda (disarankan smartphone)
2. **Izinkan akses kamera dan lokasi** ketika diminta oleh browser
3. **Arahkan kamera** ke QR Code yang terdapat pada kartu karyawan Anda
4. Sistem akan **memvalidasi** NIK, GPS, dan jam secara otomatis
5. Jika berhasil, akan muncul notifikasi **"Berhasil absen masuk"** atau **"Absen pulang berhasil"**

> ⚠️ Pastikan Anda berada dalam radius yang telah ditentukan admin dan melakukan scan pada jam yang tepat.

---

### Untuk Admin — Panel Manajemen

Login ke panel admin melalui: **http://localhost:8000/login**

#### 📊 Dashboard
- Lihat statistik kehadiran hari ini secara real-time
- Pantau grafik tren kehadiran mingguan dan bulanan
- Cek log aktivitas absensi terbaru

#### 👤 Manajemen Karyawan

**Tambah Karyawan Baru:**
1. Klik menu **Karyawan** → **Tambah Karyawan**
2. Isi form: NIK, Nama, Jenis Kelamin, Email, No. HP, Alamat, Status, dan upload Foto
3. Klik **Simpan**

**Cetak Kartu ID + QR Code:**
1. Buka daftar karyawan
2. Klik tombol **Cetak Kartu** pada baris karyawan yang dituju
3. Halaman kartu akan terbuka, gunakan **Ctrl+P** atau klik **Print** untuk mencetak

**Hapus Karyawan:**
1. Klik tombol **Hapus** pada karyawan yang ingin dihapus
2. Masukkan **password admin** untuk konfirmasi penghapusan

#### 📍 Pengaturan Lokasi Kantor
1. Klik menu **Lokasi**
2. Masukkan **Koordinat Latitude & Longitude** kantor
3. Tentukan **Radius** absensi dalam meter
4. Klik **Simpan**

> 💡 Anda bisa mendapatkan koordinat dari [Google Maps](https://maps.google.com) dengan klik kanan pada titik lokasi kantor.

#### 📄 Laporan Kehadiran
1. Klik menu **Laporan**
2. Filter berdasarkan **tanggal** atau **nama karyawan**
3. Klik **Export Excel** untuk mengunduh laporan dalam format `.xlsx`

---

## ⚙️ Konfigurasi Lanjutan

### Mengubah Aturan Jam Absensi

Edit file `app/Http/Controllers/AbsensiController.php`:

```php
// Jam absen masuk (Hadir)
if ($jamSekarang >= '06:00:00' && $jamSekarang <= '08:00:00') { ... }

// Jam absen masuk (Telat)
elseif ($jamSekarang > '08:00:00' && $jamSekarang <= '09:00:00') { ... }

// Jam absen pulang
if ($jamSekarang >= '16:00:00' && $jamSekarang <= '18:00:00') { ... }
```

### Mengubah Toleransi GPS

Di file yang sama, ubah nilai `$toleransi`:

```php
$toleransi = 50; // meter (nilai default)
```

---

## 🌐 Deployment ke Production

Proyek ini mendukung deployment ke **Vercel** dengan database **Supabase (PostgreSQL)** dan storage **Supabase Storage**.

### Konfigurasi `.env` Production

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://nama-project.vercel.app

# Database PostgreSQL Supabase
DB_CONNECTION=pgsql
DB_URL=postgresql://postgres.[ref]:[password]@aws-0-ap-southeast-1.pooler.supabase.com:6543/postgres

# Storage Supabase S3
FILESYSTEM_DISK=supabase
SUPABASE_S3_KEY=your_s3_access_key
SUPABASE_S3_SECRET=your_s3_secret_key
SUPABASE_S3_REGION=ap-southeast-1
SUPABASE_S3_BUCKET=absensi-rbtv
SUPABASE_S3_ENDPOINT=https://[project-ref].supabase.co/storage/v1/s3
SUPABASE_STORAGE_URL=https://[project-ref].supabase.co/storage/v1/object/public
```

Pastikan file `vercel.json` sudah terkonfigurasi dengan benar sebelum push ke repository.

---

## 👨‍💻 Tim Pengembang

Proyek ini dikembangkan sebagai bagian dari tugas mata kuliah Rekayasa Perangkat Lunak.

| Nama | NPM | Peran |
|------|-----|-------|
| Pebi Heriansyah | G1A023003 | Ketua / Full-Stack Developer |
| Reffki | G1A023059 | Backend Developer |
| Dzaki | — | Frontend Developer |

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan akademis. Seluruh hak cipta dimiliki oleh tim pengembang.
