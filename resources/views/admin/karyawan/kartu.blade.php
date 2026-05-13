<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<base href="{{ rtrim(config('app.url'), '/') }}/">
<title>Kartu Karyawan - {{ $karyawan->nama_depan }} {{ $karyawan->nama_belakang }}</title>

<style>
@page {
    size: 54mm 85.6mm;
    margin: 0;
}

body {
    margin: 0;
    font-family: 'Helvetica', 'Arial', sans-serif;
}

* {
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
    box-sizing: border-box;
}

/* WADAH KARTU */
.card {
    width: 54mm;
    height: 85.6mm;
    overflow: hidden;
    position: relative;
    background: #ffffff;
}

/* HEADER BIRU */
.header {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 50mm;
    background: #0d9cf5;
}

/* LUBANG TALI */
.hole {
    position: absolute;
    top: 3mm;
    left: 50%;
    transform: translateX(-50%);
    width: 12mm;
    height: 2.5mm;
    background: #ffffff;
    border-radius: 3mm;
    z-index: 10;
}

/* FOTO */
.foto {
    position: absolute;
    top: 24mm;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 32mm;
    height: 32mm;
    border-radius: 50%;
    border: 3px solid white;
    object-fit: cover;
    background: white;
    z-index: 5;
}

/* CONTENT (Pembungkus Teks) */
.content {
    position: absolute;
    top: 48mm;
    left: 0;
    width: 100%;
    text-align: left;
    padding-left: 6mm;
    padding-right: 6mm;
}

/* NAMA DEPAN */
.nama-depan {
    font-size: 14pt;
    font-weight: bold;
    color: #333333;
    text-transform: capitalize;
    line-height: 1;
    word-wrap: break-word;
    margin-top: 4mm;
}

/* NAMA BELAKANG */
.nama-belakang {
    font-size: 11pt;
    font-weight: normal;
    color: #555555;
    text-transform: capitalize;
    line-height: 1;
    margin-top: 2px;
    word-wrap: break-word;
}

/* LOGO */
.logo {
    position: absolute;
    bottom: 5mm;
    left: 0;
    width: 100%;
    text-align: left;
    padding-left: 6mm;
}

.logo img {
    width: 20mm;
}

/* QR BELAKANG — Tengah vertikal & horizontal */
.qr-container {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 38mm;
    height: 38mm;
    background: white;
    border: 2px solid #e0e0e0;
    border-radius: 3mm;
    padding: 2mm;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
}

/* Pastikan SVG di dalam qr-container mengisi penuh */
.qr-container svg {
    width: 100%;
    height: 100%;
    display: block;
}

.page-break {
    page-break-after: always;
}
</style>
</head>

<body>

{{-- ======================== --}}
{{-- HALAMAN DEPAN KARTU     --}}
{{-- ======================== --}}
<div class="card">

    <div class="header">
        <div class="hole"></div>
    </div>

    {{-- FOTO --}}
    @if(isset($fotoUrl) && $fotoUrl)
        <img src="{{ $fotoUrl }}" class="foto" crossorigin="anonymous" onerror="this.style.display='none'">
    @else
        {{-- Placeholder jika tidak ada foto --}}
        <div class="foto" style="display:flex;align-items:center;justify-content:center;background:#e0e0e0;font-size:18pt;color:#999;">
            {{ strtoupper(substr($karyawan->nama_depan, 0, 1)) }}
        </div>
    @endif

    <div class="content">
        {{-- NAMA DEPAN --}}
        <div class="nama-depan">{{ $karyawan->nama_depan }}</div>

        {{-- NAMA BELAKANG --}}
        @if($karyawan->nama_belakang)
            <div class="nama-belakang">{{ $karyawan->nama_belakang }}</div>
        @endif
    </div>

    {{-- LOGO --}}
    <div class="logo">
        <img src="{{ $logoUrl }}" onerror="this.style.display='none'">       
    </div>

</div>

<div class="page-break"></div>

{{-- ======================== --}}
{{-- HALAMAN BELAKANG (QR)   --}}
{{-- ======================== --}}
<div class="card">

    <div class="header"></div>

    {{-- QR CODE --}}
    <div class="qr-container">
        @if(isset($qrUrl) && $qrUrl)
            <img src="{{ $qrUrl }}" style="width: 100%; height: 100%; object-fit: contain;">
        @endif
    </div>

</div>

<script>
    // Tunggu semua gambar selesai dimuat sebelum membuka dialog print
    function triggerPrint() {
        var images = document.querySelectorAll('img');
        var total = images.length;

        if (total === 0) {
            // Tidak ada gambar, langsung print
            setTimeout(function() { window.print(); }, 300);
            return;
        }

        var loaded = 0;

        function checkAllLoaded() {
            loaded++;
            if (loaded >= total) {
                // Semua gambar sudah siap, buka dialog cetak
                setTimeout(function() { window.print(); }, 300);
            }
        }

        images.forEach(function(img) {
            if (img.complete) {
                checkAllLoaded();
            } else {
                img.addEventListener('load', checkAllLoaded);
                img.addEventListener('error', checkAllLoaded); // tetap lanjut meski gambar gagal
            }
        });

        // Fallback: paksa buka print setelah 3 detik jika gambar lama
        setTimeout(function() { window.print(); }, 3000);
    }

    // Jalankan setelah DOM selesai
    if (document.readyState === 'complete') {
        triggerPrint();
    } else {
        window.addEventListener('load', triggerPrint);
    }
</script>
</body>
</html>