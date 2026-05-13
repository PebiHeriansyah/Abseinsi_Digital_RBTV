@extends('layouts.sneat.app')

@section('title', 'Data Karyawan')

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Karyawan /</span> Data Karyawan
</h4>

<div class="row">
    <div class="col-12">
        <div class="card">
            
            {{-- 🔥 HEADER BARU: Ditambahkan Form Filter & Pencarian --}}
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <h5 class="mb-0 text-nowrap">Daftar Karyawan</h5>
                
                <form action="{{ route('karyawan.index') }}" method="GET" class="d-flex align-items-center gap-2 w-100 justify-content-md-end">
                    
                    {{-- Filter Status --}}
                    <select name="status" class="form-select w-auto">
                        <option value="">Semua Status</option>
                        <option value="Karyawan Tetap" {{ request('status') == 'Karyawan Tetap' ? 'selected' : '' }}>Tetap</option>
                        <option value="Magang" {{ request('status') == 'Magang' ? 'selected' : '' }}>Magang</option>
                    </select>

                    {{-- Search Input --}}
                    <div class="input-group input-group-merge" style="max-width: 250px;">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari Nama / NIK..." value="{{ request('search') }}">
                    </div>

                    {{-- Tombol Cari & Reset --}}
                    <button type="submit" class="btn btn-primary">Cari</button>
                    @if(request('search') || request('status'))
                        <a href="{{ route('karyawan.index') }}" class="btn btn-outline-secondary btn-icon" title="Reset Filter">
                            <i class="bx bx-refresh"></i>
                        </a>
                    @endif
                </form>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%" style="color: #ffffff !important;">No</th>
                                <th class="text-start" style="color: #ffffff !important;">Profil Karyawan</th>
                                <th class="text-start" style="color: #ffffff !important;">Kontak</th>
                                <th class="text-start" width="25%" style="color: #ffffff !important;">Alamat</th>
                                <th style="color: #ffffff !important;">Status</th>
                                <th style="color: #ffffff !important;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($data as $index => $d)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td class="text-start">
                                    <div class="d-flex justify-content-start align-items-center gap-3">
                                        @if($d->foto)
                                            <img src="{{ \Illuminate\Support\Facades\Storage::disk(config('filesystems.default', 'public'))->url($d->foto) }}" alt="Foto" class="rounded-circle border" width="55" height="55" style="object-fit: cover;">
                                        @else
                                            <div class="avatar avatar-md">
                                                <span class="avatar-initial rounded-circle bg-label-primary fs-4">{{ substr($d->nama_depan, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $d->nama_depan }} {{ $d->nama_belakang }}</h6>
                                            <small class="text-muted">
                                                NIK: {{ $d->nik }} <br>
                                                <i class="bx {{ $d->jenis_kelamin == 'Laki-laki' ? 'bx-male text-info' : 'bx-female text-danger' }} fs-6 align-middle"></i> 
                                                {{ $d->jenis_kelamin }}
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-start">
                                    <div class="mb-1">
                                        <i class="bx bxl-whatsapp text-success fs-5 align-middle me-1"></i> 
                                        <span>{{ $d->no_hp }}</span>
                                    </div>
                                    <div>
                                        <i class="bx bx-envelope text-primary fs-5 align-middle me-1"></i> 
                                        <small>{{ $d->email }}</small>
                                    </div>
                                </td>

                                <td class="text-start text-wrap" style="line-height: 1.4;">
                                    <small>{{ $d->alamat }}</small>
                                </td>
                                
                                <td>
                                    @if($d->status == 'Karyawan Tetap')
                                        <span class="badge bg-label-success rounded-pill px-3 py-2"><i class="bx bx-check-shield me-1"></i>{{ $d->status }}</span>
                                    @else
                                        <span class="badge bg-label-warning rounded-pill px-3 py-2"><i class="bx bx-time-five me-1"></i>{{ $d->status }}</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('karyawan.edit', $d->id) }}" class="btn btn-icon btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit Data">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>

                                        <form action="{{ route('karyawan.destroy', $d->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-icon btn-danger btn-sm btn-hapus" data-nama="{{ $d->nama_depan }}" data-bs-toggle="tooltip" title="Hapus Data">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                        
                                        <a href="{{ route('karyawan.cetak', $d->id) }}" class="btn btn-icon btn-success btn-sm" target="_blank" data-bs-toggle="tooltip" title="Cetak Kartu">
                                            <i class="bx bx-printer"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bx bx-search fs-1 mb-2"></i><br>
                                    Data karyawan tidak ditemukan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('page-script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endpush