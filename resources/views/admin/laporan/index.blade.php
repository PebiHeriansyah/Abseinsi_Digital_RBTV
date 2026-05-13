@extends('layouts.sneat.app')

@section('title', 'Laporan Absensi')

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Rekapitulasi /</span> Laporan Absensi
</h4>

<div class="row mb-4">
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4 mb-lg-0">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="card-info">
                        <p class="card-text text-muted mb-1">Total Karyawan</p>
                        <div class="d-flex align-items-end mb-2">
                            <h4 class="card-title mb-0 me-2">{{ $total }}</h4>
                        </div>
                    </div>
                    <div class="card-icon">
                        <span class="badge bg-label-primary rounded p-2">
                            <i class="bx bx-group bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 mb-4 mb-lg-0">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="card-info">
                        <p class="card-text text-muted mb-1">Total Hadir</p>
                        <div class="d-flex align-items-end mb-2">
                            <h4 class="card-title mb-0 me-2">{{ $hadir }}</h4>
                        </div>
                    </div>
                    <div class="card-icon">
                        <span class="badge bg-label-success rounded p-2">
                            <i class="bx bx-check-circle bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 mb-4 mb-sm-0">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="card-info">
                        <p class="card-text text-muted mb-1">Total Telat</p>
                        <div class="d-flex align-items-end mb-2">
                            <h4 class="card-title mb-0 me-2">{{ $telat }}</h4>
                        </div>
                    </div>
                    <div class="card-icon">
                        <span class="badge bg-label-warning rounded p-2">
                            <i class="bx bx-time-five bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="card-info">
                        <p class="card-text text-muted mb-1">Belum Absen</p>
                        <div class="d-flex align-items-end mb-2">
                            <h4 class="card-title mb-0 me-2">{{ $belum }}</h4>
                        </div>
                    </div>
                    <div class="card-icon">
                        <span class="badge bg-label-danger rounded p-2">
                            <i class="bx bx-user-x bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
        <h5 class="mb-0">Filter Data Absensi</h5>
        <a href="{{ route('laporan.export', ['tanggal_awal' => $tanggal_awal, 'tanggal_akhir' => $tanggal_akhir, 'search' => $search]) }}" class="btn btn-success mt-2 mt-md-0">
            <i class="bx bx-export me-1"></i> Download Excel
        </a>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('laporan.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label" for="tanggal_awal">Dari Tanggal</label>
                    <input type="date" id="tanggal_awal" name="tanggal_awal" class="form-control" value="{{ $tanggal_awal }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="tanggal_akhir">Sampai Tanggal</label>
                    <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="form-control" value="{{ $tanggal_akhir }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="search">Cari Nama / NIK</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text" id="basic-addon-search31"><i class="bx bx-search"></i></span>
                        <input type="text" id="search" name="search" class="form-control" placeholder="Ketik nama atau NIK..." aria-label="Search..." aria-describedby="basic-addon-search31" value="{{ $search }}">
                    </div>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Tampilkan</button>
                    <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary btn-icon" title="Reset Filter">
                        <i class="bx bx-refresh"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Riwayat Kehadiran</h5>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover table-bordered mb-0 align-middle">
            <thead class="table-dark text-center">
                <tr>
                    {{-- Fix Warna Teks Header --}}
                    <th width="5%" style="color: #ffffff !important;">No</th>
                    <th style="color: #ffffff !important;">Tanggal</th>
                    <th style="color: #ffffff !important;">NIK</th>
                    <th class="text-start" style="color: #ffffff !important;">Nama Karyawan</th>
                    <th style="color: #ffffff !important;">Jam Masuk</th>
                    <th style="color: #ffffff !important;">Jam Keluar</th>
                    <th style="color: #ffffff !important;">Status</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($data as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                    <td class="text-center"><strong>{{ $item->karyawan->nik }}</strong></td>
                    
                    {{-- Pemanggilan Nama Depan & Belakang --}}
                    <td class="text-start">{{ $item->karyawan->nama_depan }} {{ $item->karyawan->nama_belakang }}</td>
                    
                    <td class="text-center fw-bold text-primary">
                        {{ $item->jam_masuk ?? '-' }}
                    </td>
                    
                    <td class="text-center fw-bold text-danger">
                        {{ $item->jam_keluar ?? '-' }}
                    </td>
                    
                    <td class="text-center">
                        @if($item->status == 'telat')
                            <span class="badge bg-label-warning px-3 py-2"><i class="bx bx-time-five me-1"></i> Telat</span>
                        @else
                            <span class="badge bg-label-success px-3 py-2"><i class="bx bx-check me-1"></i> Hadir</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        <i class="bx bx-info-circle fs-4 mb-2 d-block"></i>
                        <em>Tidak ada data absensi pada rentang tanggal atau filter pencarian ini.</em>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection