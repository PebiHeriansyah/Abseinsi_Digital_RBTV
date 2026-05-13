@extends('layouts.sneat.app')

@section('title', 'Edit Karyawan')

@push('page-style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">

<style>
/* Bulat */
.cropper-view-box,
.cropper-face {
    border-radius: 50%;
}

/* 🔥 FIX UTAMA: batasi area crop */
.img-container {
    width: 100%;
    max-height: 400px;
    overflow: hidden;
    display: flex;
    justify-content: center;
}

#imageToCrop {
    max-width: 100%;
    max-height: 400px;
    object-fit: contain;
}

/* Modal fix */
.modal {
    z-index: 9999 !important;
}

.cropper-container {
    z-index: 99999 !important;
}

/* Preview */
.preview-container img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #696cff;
}
</style>
@endpush

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Karyawan /</span> Edit Data
</h4>

<div class="row justify-content-center">
    <div class="col-md-8 mx-auto">

        {{-- ALERT ERROR --}}
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Formulir Edit Karyawan</h5>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('karyawan.update', $karyawan->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- NIK --}}
                    <div class="mb-3">
                        <label class="form-label">NIK</label>
                        <input type="text" name="nik" class="form-control" value="{{ old('nik', $karyawan->nik) }}" required>
                    </div>

                    {{-- NAMA DEPAN & BELAKANG --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="nama_depan">Nama Depan</label>
                            <input type="text" name="nama_depan" id="nama_depan" class="form-control" value="{{ old('nama_depan', $karyawan->nama_depan) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="nama_belakang">Nama Belakang</label>
                            <input type="text" name="nama_belakang" id="nama_belakang" class="form-control" value="{{ old('nama_belakang', $karyawan->nama_belakang) }}" required>
                        </div>
                    </div>

                    {{-- JENIS KELAMIN & STATUS --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="jenis_kelamin">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                                <option value="Laki-laki" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="status">Status Karyawan</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="Karyawan Tetap" {{ old('status', $karyawan->status) == 'Karyawan Tetap' ? 'selected' : '' }}>Karyawan Tetap</option>
                                <option value="Magang" {{ old('status', $karyawan->status) == 'Magang' ? 'selected' : '' }}>Magang</option>
                            </select>
                        </div>
                    </div>

                    {{-- EMAIL & NO HP --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $karyawan->email) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="no_hp">No. Handphone</label>
                            <input type="text" name="no_hp" id="no_hp" class="form-control" value="{{ old('no_hp', $karyawan->no_hp) }}" required>
                        </div>
                    </div>

                    {{-- ALAMAT --}}
                    <div class="mb-3">
                        <label class="form-label" for="alamat">Alamat Lengkap</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3" required>{{ old('alamat', $karyawan->alamat) }}</textarea>
                    </div>

                    {{-- FOTO --}}
                    <div class="mb-3">
                        <label class="form-label">Foto Profil <small class="text-muted">(Abaikan jika tidak ingin mengubah foto)</small></label>

                        <div id="oldFotoContainer" class="mb-2 mt-1">
                            @if($karyawan->foto)
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk(config('filesystems.default', 'public'))->url($karyawan->foto) }}" width="120" height="120" style="border-radius:50%; object-fit: cover; border: 2px solid #696cff;">
                            @endif
                        </div>

                        <div class="preview-container" id="previewContainer" style="display:none;">
                            <img id="hasilCrop">
                        </div>

                        <input type="file" id="fotoInput" name="foto" class="form-control mt-2" accept="image/png, image/jpeg, image/jpg">
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2"><i class="bx bx-save me-1"></i> Update Data</button>
                        <a href="{{ route('karyawan.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="cropModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Sesuaikan Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="img-container">
                    <img id="imageToCrop">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" id="btnCrop">Simpan & Gunakan</button>
            </div>

        </div>
    </div>
</div>

@endsection

@push('page-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
let fotoInput = document.getElementById('fotoInput');
let imageToCrop = document.getElementById('imageToCrop');
let cropModalElement = document.getElementById('cropModal');
let cropModal = bootstrap.Modal.getOrCreateInstance(cropModalElement);

let btnCrop = document.getElementById('btnCrop');
let previewContainer = document.getElementById('previewContainer');
let hasilCrop = document.getElementById('hasilCrop');
let oldFotoContainer = document.getElementById('oldFotoContainer');

let cropper;

// PILIH FOTO
fotoInput.addEventListener('change', function (e) {
    let file = e.target.files[0];
    if (!file) return;

    let url = URL.createObjectURL(file);
    imageToCrop.src = url;

    cropModal.show();
});

// INIT CROPPER
cropModalElement.addEventListener('shown.bs.modal', function () {
    if (cropper) cropper.destroy();

    cropper = new Cropper(imageToCrop, {
        aspectRatio: 1,
        viewMode: 1,
        autoCropArea: 0.8,
        responsive: true
    });
});

// DESTROY
cropModalElement.addEventListener('hidden.bs.modal', function () {
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
});

// CROP
btnCrop.addEventListener('click', function () {

    if (!cropper) return;

    let canvas = cropper.getCroppedCanvas({
        width: 500,
        height: 500,
        fillColor: '#fff'
    });

    canvas.toBlob(function (blob) {

        let url = URL.createObjectURL(blob);
        hasilCrop.src = url;
        previewContainer.style.display = 'block';

        if (oldFotoContainer) oldFotoContainer.style.display = 'none';

        // replace file input (safe)
        try {
            let file = new File([blob], "foto.jpg", { type: "image/jpeg" });
            let dt = new DataTransfer();
            dt.items.add(file);
            fotoInput.files = dt.files;
        } catch (e) {
            console.warn("DataTransfer gagal", e);
        }

        // 🔥 ANTI STUCK
        setTimeout(() => {
            cropModal.hide();

            document.body.classList.remove('modal-open');
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());

        }, 100);

    }, 'image/jpeg', 0.9);
});
</script>
@endpush