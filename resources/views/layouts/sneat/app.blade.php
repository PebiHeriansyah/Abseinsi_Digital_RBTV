<!DOCTYPE html>
<html lang="id" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    
    <title>@yield('title') | Absensi RBTV</title>
    
    <meta name="description" content="Sistem Informasi Absensi Karyawan RBTV" />
    <link rel="icon" type="image/x-icon" href="{{ asset('sneat/assets/img/favicon/favicon.ico') }}" />

    <script>
        const savedTheme = localStorage.getItem('templateCustomizer-vertical-menu-template--Style') || 'light';
        const htmlElement = document.documentElement;
        
        if (savedTheme === 'dark') {
            htmlElement.classList.remove('light-style');
            htmlElement.classList.add('dark-style');
            htmlElement.setAttribute('data-theme', 'theme-dark');
        } else {
            htmlElement.classList.remove('dark-style');
            htmlElement.classList.add('light-style');
            htmlElement.setAttribute('data-theme', 'theme-default');
        }
    </script>

    @include('layouts.sneat.styles')
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Z-Index & Efek Blur ala iOS */
        .swal2-container { z-index: 10000 !important; }
        .swal2-backdrop-show { 
            background: rgba(15, 23, 42, 0.7) !important; 
            backdrop-filter: blur(5px); /* Efek blur pada background */
        }
        
        /* Kotak Popup yang Lebih Halus */
        .swal2-popup { 
            border-radius: 1.25rem !important; 
            padding: 2rem 1rem !important; 
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        }

        /* Styling Custom untuk Input Password */
        .custom-swal-pass {
            width: 100%;
            padding: 0.8rem 1rem;
            font-size: 1.5rem;
            letter-spacing: 0.3rem;
            text-align: center;
            border: 2px solid #d9dee3;
            border-radius: 0.75rem;
            transition: all 0.2s ease-in-out;
            outline: none;
        }
        .custom-swal-pass:focus {
            border-color: #696cff;
            box-shadow: 0 0 0 4px rgba(105, 108, 255, 0.15);
        }
        .custom-swal-pass::placeholder {
            font-size: 1rem;
            letter-spacing: normal;
            color: #a1acb8;
        }
        
        /* Dukungan Dark Mode untuk Input */
        .dark-style .custom-swal-pass {
            background-color: #2b2c40;
            border-color: #444564;
            color: #fff;
        }
        .dark-style .custom-swal-pass:focus {
            border-color: #696cff;
        }
    </style>
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('layouts.sneat.sidebar')
            <div class="layout-page">
                @include('layouts.sneat.navbar')
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                    @include('layouts.sneat.footer')
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    
    @include('layouts.sneat.scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            const isDark = htmlElement.classList.contains('dark-style');
            const bgColor = isDark ? '#2b2c40' : '#ffffff';
            const textColor = isDark ? '#a3a4cc' : '#566a7f';

            // =================================================================
            // 1. ALERT BERHASIL (SUCCESS)
            // =================================================================
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    background: bgColor,
                    color: textColor,
                    showConfirmButton: true,
                    confirmButtonText: '<i class="bx bx-check-circle me-1"></i> Oke, Mengerti',
                    customClass: {
                        confirmButton: 'btn btn-success px-4 py-2 rounded-pill shadow-sm fw-semibold'
                    },
                    buttonsStyling: false
                });
            @endif

            // =================================================================
            // 2. ALERT GAGAL / PASSWORD SALAH (ERROR)
            // =================================================================
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Akses Ditolak!',
                    html: '<p class="text-danger fw-medium mb-0">{{ session('error') }}</p>',
                    background: bgColor,
                    color: textColor,
                    showConfirmButton: true,
                    confirmButtonText: '<i class="bx bx-refresh me-1"></i> Coba Lagi',
                    customClass: {
                        confirmButton: 'btn btn-danger px-4 py-2 rounded-pill shadow-sm fw-semibold'
                    },
                    buttonsStyling: false,
                    // Tambahkan warna merah transparan di belakangnya
                    backdrop: `rgba(220, 53, 69, 0.15) backdrop-filter: blur(5px);` 
                });
            @endif

            // =================================================================
            // 3. ALERT KONFIRMASI PASSWORD DENGAN DESAIN PREMIUM
            // =================================================================
            document.body.addEventListener('click', function(e) {
                const btnHapus = e.target.closest('.btn-hapus');
                
                if (btnHapus) {
                    e.preventDefault(); 
                    const form = btnHapus.closest('form');
                    const namaData = btnHapus.getAttribute('data-nama') || 'data ini';

                    Swal.fire({
                        title: 'Otorisasi Diperlukan',
                        html: `
                            <div class="px-2 mt-3">
                                <div class="mb-3">
                                    <i class="bx bx-shield-quarter text-warning" style="font-size: 4rem;"></i>
                                </div>
                                <p class="mb-4 text-wrap">Untuk menghapus data <strong>${namaData}</strong> secara permanen, silakan verifikasi identitas Anda.</p>
                                <input type="password" id="swal-input-password" class="custom-swal-pass" placeholder="••••••••" autocomplete="off">
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'Hapus Data',
                        cancelButtonText: 'Batal',
                        background: bgColor,
                        color: textColor,
                        customClass: {
                            confirmButton: 'btn btn-danger btn-lg me-2 shadow-sm px-4',
                            cancelButton: 'btn btn-label-secondary btn-lg px-4' 
                        },
                        buttonsStyling: false,
                        didOpen: () => {
                            // Fokus otomatis ke input agar user langsung bisa ngetik
                            document.getElementById('swal-input-password').focus();
                        },
                        preConfirm: () => {
                            const password = document.getElementById('swal-input-password').value;
                            if (!password) {
                                Swal.showValidationMessage('<i class="bx bx-info-circle me-1"></i> Password harus diisi untuk melanjutkan!');
                                return false; 
                            }
                            return password;
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            
                            // Kirim password ke Backend
                            let passwordInput = form.querySelector('input[name="password_konfirmasi"]');
                            if (!passwordInput) {
                                passwordInput = document.createElement('input');
                                passwordInput.type = 'hidden';
                                passwordInput.name = 'password_konfirmasi';
                                form.appendChild(passwordInput);
                            }
                            passwordInput.value = result.value;

                            // Loading State
                            Swal.fire({
                                title: 'Memproses...',
                                text: 'Memverifikasi keamanan data.',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                background: bgColor,
                                color: textColor,
                                willOpen: () => { Swal.showLoading() }
                            });
                            
                            form.submit();
                        }
                    });
                }
            });
            
        });
    </script>
</body>
</html>