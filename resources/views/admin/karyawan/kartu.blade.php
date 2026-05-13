<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Kartu Karyawan</title>

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

/* QR BELAKANG */
.qr-container {
    position: absolute;
    top: 30mm;
    left: 50%;
    transform: translateX(-50%);
}

.qr-container img {
    width: 30mm;
    height: 30mm;
    border: 1px solid #000;
    padding: 2mm;
    background: white;
}

.page-break {
    page-break-after: always;
}
</style>
</head>

<body>

<div class="card">

    <div class="header">
        <div class="hole"></div>
    </div>

    {{-- FOTO --}}
    @if($fotoBase64)
        <img src="{{ $fotoBase64 }}" class="foto">
    @endif

    <div class="content">
        {{-- MENGGUNAKAN KOLOM NAMA DEPAN DAN BELAKANG DARI DATABASE --}}
        
        {{-- NAMA DEPAN --}}
        <div class="nama-depan">{{ $karyawan->nama_depan }}</div>
        
        {{-- NAMA BELAKANG --}}
        @if($karyawan->nama_belakang)
            <div class="nama-belakang">{{ $karyawan->nama_belakang }}</div>
        @endif
    </div>

    {{-- LOGO --}}
    @if($logoBase64)
    <div class="logo">
        <img src="{{ $logoBase64 }}">
    </div>
    @endif

</div>

<div class="page-break"></div>

<div class="card">

    <div class="header"></div>

    <div class="qr-container">
        <img src="{{ $qrBase64 }}">
    </div>

</div>

</body>
</html>