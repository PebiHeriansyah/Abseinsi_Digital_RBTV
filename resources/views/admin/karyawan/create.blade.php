@extends('layouts.sneat.app')

@section('title', 'Tambah Karyawan')

@push('page-style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <style>
        .cropper-view-box, .cropper-face {
            border-radius: 50%;
        }
        #imageToCrop {
            display: block;
            max-width: 100%;
        }
        .preview-container {
            margin-top: 10px;
            display: none; 
        }
        .preview-container img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #696cff; /* Warna primary Sneat */
        }
    </style>
@endpush

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Karyawan /</span> Tambah Data
</h4>

<div class="row justify-content-center">
    <div class="col-md-8">
        
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
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Formulir Tambah Karyawan</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('karyawan.store') }}" enctype="multipart/form-data" id="formKaryawan">
                    @csrf

                    {{-- NIK --}}
                    <div class="mb-3">
                        <label class="form-label" for="nik">NIK</label>
                        <input type="text" name="nik" id="nik" class="form-control" value="{{ old('nik') }}" placeholder="Masukkan 16 Digit NIK" required>
                    </div>

                    {{-- NAMA DEPAN & BELAKANG --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="nama_depan">Nama Depan</label>
                            <input type="text" name="nama_depan" id="nama_depan" class="form-control" value="{{ old('nama_depan') }}" placeholder="Nama depan" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="nama_belakang">Nama Belakang</label>
                            <input type="text" name="nama_belakang" id="nama_belakang" class="form-control" value="{{ old('nama_belakang') }}" placeholder="Nama belakang" required>
                        </div>
                    </div>

                    {{-- JENIS KELAMIN & STATUS --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="jenis_kelamin">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                                <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="status">Status Karyawan</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="" disabled {{ old('status') ? '' : 'selected' }}>-- Pilih Status --</option>
                                <option value="Karyawan Tetap" {{ old('status') == 'Karyawan Tetap' ? 'selected' : '' }}>Karyawan Tetap</option>
                                <option value="Magang" {{ old('status') == 'Magang' ? 'selected' : '' }}>Magang</option>
                            </select>
                        </div>
                    </div>

                    {{-- EMAIL & NO HP --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="email@gmail.com" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="no_hp">No. Handphone</label>
                            <input type="text" name="no_hp" id="no_hp" class="form-control" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx" required>
                        </div>
                    </div>

                    {{-- ALAMAT --}}
                    <div class="mb-3">
                        <label class="form-label" for="alamat">Alamat Lengkap</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap" required>{{ old('alamat') }}</textarea>
                    </div>

                    {{-- FOTO --}}
                    <div class="mb-3">
                        <label class="form-label" for="fotoInput">Foto Profil</label>
                        <input type="file" name="foto" id="fotoInput" class="form-control" accept="image/png, image/jpeg, image/jpg" required>
                        
                        <div class="preview-container" id="previewContainer">
                            <p class="text-muted mb-1 mt-2"><small>Hasil potongan:</small></p>
                            <img id="hasilCrop" src="" alt="Preview">
                        </div>
                    </div>

                    {{-- BUTTON --}}
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2"><i class="bx bx-save me-1"></i> Simpan</button>
                        <a href="{{ route('karyawan.index') }}" class="btn btn-outline-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Cropper --}}
<div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cropModalLabel">Sesuaikan Foto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="img-container">
            <img id="imageToCrop" src="">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btnCrop">Potong & Gunakan</button>
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

    let cropper;

    // =========================
    // PILIH GAMBAR
    // =========================
    fotoInput.addEventListener('change', function (e) {
        let file = e.target.files[0];
        if (!file) return;

        let url = URL.createObjectURL(file);
        imageToCrop.src = url;

        cropModal.show();
    });

    // =========================
    // INIT CROPPER
    // =========================
    cropModalElement.addEventListener('shown.bs.modal', function () {
        cropper = new Cropper(imageToCrop, {
            aspectRatio: 1,
            viewMode: 1,
            autoCropArea: 0.8,
            dragMode: 'move',
        });
    });

    // =========================
    // DESTROY CROPPER
    // =========================
    cropModalElement.addEventListener('hidden.bs.modal', function () {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    });

    // =========================
    // TOMBOL CROP
    // =========================
    btnCrop.addEventListener('click', function () {
        if (!cropper) return;

        let originalText = btnCrop.innerHTML;
        btnCrop.innerHTML = "Memproses...";
        btnCrop.disabled = true;

        try {
            let canvas = cropper.getCroppedCanvas({
                width: 500,
                height: 500,
                fillColor: '#fff'
            });

            if (!canvas) throw new Error("Canvas gagal");

            // =========================
            // TO BLOB (ASYNC FIX)
            // =========================
            canvas.toBlob(function (blob) {
                if (!blob) {
                    alert("Gagal memproses gambar");
                    resetBtn();
                    return;
                }

                // Preview hasil
                let url = URL.createObjectURL(blob);
                hasilCrop.src = url;
                previewContainer.style.display = 'block';

                // =========================
                // OPTIONAL: SET FILE INPUT
                // =========================
                try {
                    let file = new File([blob], "foto.jpg", { type: "image/jpeg" });
                    let dt = new DataTransfer();
                    dt.items.add(file);
                    fotoInput.files = dt.files;
                } catch (e) {
                    console.warn("DataTransfer gagal, aman diabaikan", e);
                }

                // =========================
                // FORCE CLOSE MODAL (ANTI STUCK)
                // =========================
                setTimeout(() => {
                    cropModal.hide();

                    // bersihkan backdrop bug bootstrap
                    document.body.classList.remove('modal-open');
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());

                    resetBtn();
                }, 100);

            }, 'image/jpeg', 0.9);

        } catch (err) {
            console.error(err);
            alert("Terjadi error saat crop");
            resetBtn();
        }

        function resetBtn() {
            btnCrop.innerHTML = originalText;
            btnCrop.disabled = false;
        }
    });
</script>
@endpush