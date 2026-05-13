@extends('layouts.sneat.app')

@section('title', 'Setting Lokasi')

@push('page-style')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<style>
    #map {
        height: 400px;
        z-index: 1; /* Mencegah map menutupi dropdown/navbar Sneat */
    }
</style>
@endpush

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Master Data /</span> Setting Lokasi
</h4>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Pengaturan Titik Lokasi Absensi Kantor</h5>
            </div>

            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('lokasi.update') }}">
                    @csrf

                    {{-- MAP --}}
                    <div class="mb-4">
                        <div id="map" class="w-100 rounded border"></div>
                    </div>

                    {{-- GPS BUTTON --}}
                    <div class="d-grid gap-2 mb-4">
                        <button type="button" id="btn-gps" class="btn btn-primary" onclick="getLocation()">
                            <i class='bx bx-current-location me-1'></i> Ambil Lokasi Presisi (GPS)
                        </button>
                        <div id="accuracy-text" class="text-center small text-muted"></div>
                    </div>

                    {{-- INPUT --}}
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="latitude">Latitude</label>
                            <input type="text" id="latitude" name="latitude" class="form-control"
                                value="{{ $lokasi->latitude ?? '' }}" readonly required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="longitude">Longitude</label>
                            <input type="text" id="longitude" name="longitude" class="form-control"
                                value="{{ $lokasi->longitude ?? '' }}" readonly required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="radius">Radius (meter)</label>
                            <div class="input-group input-group-merge">
                                <input type="number" id="radius" name="radius" class="form-control"
                                    value="{{ $lokasi->radius ?? 100 }}" required>
                                <span class="input-group-text">m</span>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info py-2 d-flex align-items-center mb-4" role="alert">
                        <i class='bx bx-info-circle me-2'></i>
                        <small>Klik area pada peta atau geser marker (pin biru) untuk mengubah lokasi secara manual.</small>
                    </div>

                    <button type="submit" class="btn btn-success w-100">
                        <i class="bx bx-save me-1"></i> Simpan Pengaturan
                    </button>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@push('page-script')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
// Pastikan default value menggunakan koordinat yang valid jika database kosong (contoh: Jakarta)
let defaultLat = {{ $lokasi->latitude ?? -6.2000 }};
let defaultLng = {{ $lokasi->longitude ?? 106.8166 }};
let defaultRad = {{ $lokasi->radius ?? 100 }};

// 🔥 Gunakan tile yang mirip Google Maps
let map = L.map('map').setView([defaultLat, defaultLng], 16);

L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
    subdomains:['mt0','mt1','mt2','mt3']
}).addTo(map);

// Marker
let marker = L.marker([defaultLat, defaultLng], {
    draggable: true
}).addTo(map);

// Circle
let circle = L.circle([defaultLat, defaultLng], {
    radius: defaultRad,
    color: '#696cff', // Warna primary Sneat
    fillOpacity: 0.2
}).addTo(map);

// Drag marker
marker.on('dragend', function () {
    let pos = marker.getLatLng();
    update(pos.lat, pos.lng);
});

// Klik map
map.on('click', function(e) {
    update(e.latlng.lat, e.latlng.lng);
});

// Update radius saat input diubah
document.getElementById('radius').addEventListener('input', function() {
    let radValue = this.value;
    if(radValue > 0) {
        circle.setRadius(radValue);
    }
});

// Fungsi Update DOM & Map
function update(lat, lng) {
    marker.setLatLng([lat, lng]);
    circle.setLatLng([lat, lng]);

    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
}

// Fitur GPS (Geolocation)
function getLocation() {
    const btn = document.getElementById('btn-gps');
    btn.disabled = true;
    btn.innerHTML = '<i class="bx bx-loader-circle bx-spin me-1"></i> Mencari GPS...';

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                let lat = pos.coords.latitude;
                let lng = pos.coords.longitude;
                let acc = pos.coords.accuracy;

                update(lat, lng);
                map.setView([lat, lng], 18);

                document.getElementById('accuracy-text').innerHTML =
                    "<span class='text-success'><i class='bx bx-check-circle'></i> Akurasi didapat: " + Math.round(acc) + " meter</span>";

                resetButton();
            },
            (error) => {
                alert('Gagal mengambil lokasi GPS. Pastikan izin lokasi di browser Anda diaktifkan.');
                resetButton();
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
    } else {
        alert("Geolocation tidak didukung oleh browser ini.");
        resetButton();
    }

    function resetButton() {
        btn.disabled = false;
        btn.innerHTML = "<i class='bx bx-current-location me-1'></i> Ambil Lokasi Presisi (GPS)";
    }
}
</script>
@endpush