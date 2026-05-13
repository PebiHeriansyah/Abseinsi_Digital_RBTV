@extends('layouts.sneat.app')

@section('title', 'Dashboard Utama')

@section('content')
<div class="row">
    {{-- BARIS 1: HEADER --}}
    <div class="col-12 mb-4">
        <div class="card bg-primary text-white shadow-none border-0">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="text-white fw-bold mb-1">Monitoring Presensi Real-Time</h4>
                        <p class="mb-0 opacity-75">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- BARIS 2: ANALYTICS HUB (PENGGANTI KOTAK SUMMARY) --}}
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body py-4">
                <div class="row align-items-center">
                    {{-- Visual Lingkaran Persentase --}}
                    <div class="col-md-3 text-center border-md-end">
                        <div class="position-relative d-inline-block">
                            <canvas id="radialAttendance" width="150" height="150"></canvas>
                            <div class="position-absolute top-50 start-50 translate-middle text-center">
                                <h4 class="mb-0 fw-bold text-primary">{{ $persenHadir }}%</h4>
                                <small class="text-muted" style="font-size: 0.7rem;">Hadir</small>
                            </div>
                        </div>
                    </div>

                    {{-- Detail Statistik Horizontal --}}
                    <div class="col-md-9 ps-md-4 mt-3 mt-md-0">
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="badge bg-label-primary p-2 rounded me-3"><i class="bx bx-group fs-4"></i></div>
                                    <div>
                                        <h5 class="mb-0 fw-bold text-dark">{{ $totalKaryawan }}</h5>
                                        <small class="text-muted small text-uppercase">Total</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="d-flex align-items-center border-start ps-2 ps-md-3">
                                    <div class="badge bg-label-success p-2 rounded me-3"><i class="bx bx-user-check fs-4"></i></div>
                                    <div>
                                        <h5 class="mb-0 fw-bold text-success">{{ $hadir }}</h5>
                                        <small class="text-muted small text-uppercase">On-Time</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="d-flex align-items-center border-start ps-2 ps-md-3">
                                    <div class="badge bg-label-warning p-2 rounded me-3"><i class="bx bx-timer fs-4"></i></div>
                                    <div>
                                        <h5 class="mb-0 fw-bold text-warning">{{ $telat }}</h5>
                                        <small class="text-muted small text-uppercase">Telat</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="d-flex align-items-center border-start ps-2 ps-md-3">
                                    <div class="badge bg-label-danger p-2 rounded me-3"><i class="bx bx-user-x fs-4"></i></div>
                                    <div>
                                        <h5 class="mb-0 fw-bold text-danger">{{ $belumAbsen }}</h5>
                                        <small class="text-muted small text-uppercase">Absen</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- BARIS 3: GRAFIK TREND & BAR CHART --}}
    <div class="col-12 mb-4">
        <div class="row">
            <div class="col-md-8 mb-4 mb-md-0">
                <div class="card h-100 shadow-sm">
                    <div class="card-header pb-0"><h5 class="fw-bold m-0 text-primary">Trend Kehadiran (7 Hari)</h5></div>
                    <div class="card-body">
                        <canvas id="lineChart" style="min-height: 280px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header pb-0"><h5 class="fw-bold m-0 text-primary">Rasio Perbulan</h5></div>
                    <div class="card-body">
                        <canvas id="barChart" style="min-height: 280px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- BARIS 4: PERSENTASE PROGRESS & LOG --}}
    <div class="col-md-8 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-header border-bottom pb-3"><h5 class="card-title m-0 fw-bold">Log Scan Terbaru</h5></div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead><tr><th>Karyawan</th><th>Waktu</th><th class="text-center">Status</th></tr></thead>
                    <tbody>
                        @forelse($recentAbsensi as $ra)
                        <tr>
                            <td><strong>{{ $ra->karyawan->nama_depan }} {{ $ra->karyawan->nama_belakang }}</strong></td>
                            <td>{{ $ra->jam_masuk }}</td>
                            <td class="text-center">
                                <span class="badge bg-label-{{ $ra->status == 'hadir' ? 'success' : 'warning' }} rounded-pill">{{ strtoupper($ra->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-5 text-muted">Belum ada aktivitas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-header border-bottom pb-3"><h5 class="fw-bold m-0 text-primary">Analisis Performa (%)</h5></div>
            <div class="card-body pt-4">
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1"><small class="fw-bold">Tingkat Kehadiran</small><small>{{ $persenHadir }}%</small></div>
                    <div class="progress" style="height: 10px;"><div class="progress-bar bg-success shadow-none" style="width: {{ $persenHadir }}%"></div></div>
                </div>
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1"><small class="fw-bold">Tingkat Keterlambatan</small><small>{{ $persenTelat }}%</small></div>
                    <div class="progress" style="height: 10px;"><div class="progress-bar bg-warning shadow-none" style="width: {{ $persenTelat }}%"></div></div>
                </div>
                <div class="alert alert-label-primary small border-0" role="alert">
                    <i class="bx bx-info-circle me-1"></i> Data dihitung berdasarkan total karyawan yang terdaftar secara sistem.
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPTS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Radial Attendance Chart (Doughnut)
        new Chart(document.getElementById('radialAttendance'), {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Belum'],
                datasets: [{
                    data: [{{ $hadir + $telat }}, {{ $belumAbsen }}],
                    backgroundColor: ['#696cff', '#e1e1e3'],
                    borderWidth: 0,
                    cutout: '80%'
                }]
            },
            options: {
                responsive: false,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { enabled: false } }
            }
        });

        // 2. Line Chart (Trend 7 Hari)
        new Chart(document.getElementById('lineChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($labelHari) !!},
                datasets: [{
                    label: 'Total Absen',
                    data: {!! json_encode($dataHari) !!},
                    borderColor: '#696cff',
                    backgroundColor: 'rgba(105, 108, 255, 0.1)',
                    fill: true, tension: 0.4, pointRadius: 4
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, grid: { display: false } }, x: { grid: { display: false } } }
            }
        });

        // 3. Bar Chart (Rasio 5 Bulan)
        new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($labelBulan) !!},
                datasets: [{
                    label: 'Kehadiran',
                    data: {!! json_encode($dataBulanan) !!},
                    backgroundColor: '#696cff',
                    borderRadius: 5,
                    barThickness: 20
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 5 } } }
            }
        });
    });
</script>
@endsection