<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Absensi Digital</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>

    <style>
        /* Animasi Garis Laser Scanner */
        @keyframes scan {
            0% { top: 5%; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 95%; opacity: 0; }
        }

        .laser-line {
            animation: scan 2.5s infinite linear;
            box-shadow: 0 0 15px 2px rgba(239, 68, 68, 0.8);
            display: none;
        }

        /* Estetika Background Melayang */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }

        #reader { width: 100%; height: 100%; overflow: hidden; border-radius: 1rem; }
        #reader video { object-fit: cover !important; width: 100% !important; height: 100% !important; border-radius: 1rem; }
    </style>
</head>
<body class="bg-[#EAF2FA] sm:min-h-screen flex items-center justify-center relative overflow-hidden font-sans m-0 p-0">

    <div class="hidden sm:block absolute top-10 left-1/4 w-72 h-72 bg-[#D1E3F8] rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob z-0"></div>
    <div class="hidden sm:block absolute top-20 right-1/4 w-72 h-72 bg-[#C2D9F3] rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob animation-delay-2000 z-0"></div>

    <div class="relative w-full h-[100dvh] sm:w-[380px] sm:h-[750px] bg-[#43526E] sm:rounded-[40px] sm:shadow-2xl sm:border-8 sm:border-[#344057] overflow-hidden flex flex-col justify-between z-10">
        
        <div class="pt-10 px-6 z-20 relative flex items-start justify-center">
            
            <button id="btn-flash" class="absolute left-6 top-6 w-10 h-10 flex items-center justify-center bg-[#344057] rounded-full text-white hover:bg-opacity-80 transition-colors shadow-md z-30 hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </button>

            <button id="btn-switch" class="absolute right-6 top-6 w-10 h-10 flex items-center justify-center bg-[#344057] rounded-full text-white hover:bg-opacity-80 transition-colors shadow-md z-30 hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </button>

            <div class="w-full text-center space-y-2 mt-2">
                <div class="w-20 h-1.5 bg-[#344057] rounded-full mx-auto mb-4 hidden sm:block"></div>
                <h1 class="text-white font-semibold text-2xl tracking-wide mt-1">Absensi Digital</h1>
                <p class="text-[#A2B2CE] text-sm font-medium px-6 leading-relaxed">Arahkan kamera dengan benar ke QR Code untuk verifikasi absen.</p>
            </div>
        </div>

        <div class="flex-1 flex flex-col items-center justify-center px-6 sm:px-8 relative mt-2 z-20">
            <div class="relative w-full aspect-square max-w-[280px] bg-gray-900 rounded-2xl shadow-inner flex items-center justify-center p-0 overflow-hidden ring-4 ring-[#344057] mx-auto">
                <div id="placeholder" class="text-[#A2B2CE] flex flex-col items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-xs font-semibold uppercase tracking-wider">Kamera Nonaktif</span>
                </div>
                <div id="reader" class="absolute inset-0 hidden"></div>
                <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-white rounded-tl-xl m-3 z-30 pointer-events-none"></div>
                <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-white rounded-tr-xl m-3 z-30 pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-white rounded-bl-xl m-3 z-30 pointer-events-none"></div>
                <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-white rounded-br-xl m-3 z-30 pointer-events-none"></div>
                <div id="laser" class="laser-line absolute left-0 w-full h-[3px] bg-gradient-to-r from-transparent via-red-500 to-transparent z-20 pointer-events-none"></div>
            </div>
            <div id="status-badge" class="absolute -bottom-6 w-[110%] bg-blue-500 text-white px-4 py-2.5 rounded-full text-xs font-semibold opacity-0 transition-all duration-300 transform translate-y-4 text-center shadow-lg whitespace-nowrap overflow-hidden text-ellipsis">
                Menunggu aksi...
            </div>
        </div>

        <div class="bg-[#344057] h-28 sm:rounded-t-3xl flex items-center justify-center px-4 pb-4 mt-12 relative z-20">
            <button id="btn-start" class="w-full mx-6 py-3.5 flex items-center justify-center rounded-2xl bg-white text-[#43526E] font-bold tracking-wide shadow-lg transform -translate-y-6 hover:scale-105 transition-transform border-4 border-[#344057]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                </svg>
                MULAI SCAN
            </button>
        </div>
    </div>

    <audio id="sound-success" src="{{ asset('sounds/berhasil.mp3') }}" preload="auto" muted></audio>
    <audio id="sound-error" src="{{ asset('sounds/gagal.mp3') }}" preload="auto" muted></audio>

    <script>
        let html5QrCode;
        let audioSuccess = document.getElementById("sound-success");
        let audioError = document.getElementById("sound-error");

        let sudahScan = false;
        let isScanningProcess = false; 
        let currentLat = null;
        let currentLng = null;
        let gpsWatcherId = null;
        
        let currentFacingMode = "environment"; 
        let isFlashOn = false;

        const statusBadge = document.getElementById('status-badge');
        const laser = document.getElementById('laser');
        const btnFlash = document.getElementById('btn-flash');
        const btnSwitch = document.getElementById('btn-switch');

        function showNotification(message, type = 'info') {
            statusBadge.classList.remove('opacity-0', 'translate-y-4', 'bg-blue-500', 'bg-green-500', 'bg-red-500');
            statusBadge.innerHTML = message;
            
            if(type === 'success') statusBadge.classList.add('bg-green-500', 'opacity-100', 'translate-y-0');
            else if(type === 'error') statusBadge.classList.add('bg-red-500', 'opacity-100', 'translate-y-0');
            else statusBadge.classList.add('bg-blue-500', 'opacity-100', 'translate-y-0');
        }

        function startScanningProcess() {
            isScanningProcess = true;
            
            html5QrCode.start(
                { facingMode: currentFacingMode },
                {
                    fps: 30,
                    qrbox: { width: 200, height: 200 }
                },
                onScanSuccess,
                (errorMessage) => { }
            ).then(() => {
                laser.style.display = 'block';
                btnFlash.classList.remove('hidden'); 
                btnSwitch.classList.remove('hidden'); 
                
                isFlashOn = false;
                btnFlash.classList.remove('text-yellow-400');
                btnFlash.classList.add('text-white');
            }).catch((err) => {
                showNotification("❌ Izin kamera ditolak / error.", "error");
                let btnStart = document.getElementById('btn-start');
                btnStart.innerHTML = "ULANGI SCAN";
                btnStart.classList.remove('cursor-not-allowed', 'opacity-90');
                isScanningProcess = false;
            });
        }

        document.getElementById('btn-start').addEventListener('click', function() {
            this.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-[#43526E]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> MEMINDAI...`;
            this.classList.add('cursor-not-allowed', 'opacity-90');

            document.getElementById('placeholder').style.display = 'none';
            document.getElementById('reader').style.display = 'block';
            showNotification("📍 Mencari akurasi lokasi GPS...", "info");

            if (navigator.geolocation) {
                gpsWatcherId = navigator.geolocation.watchPosition(
                    function(position) {
                        currentLat = position.coords.latitude;
                        currentLng = position.coords.longitude;
                        if (position.coords.accuracy <= 50) { 
                            showNotification("✅ GPS Terkunci. Arahkan QR Code.", "success");
                        }
                    }, 
                    function(error) {
                        showNotification("❌ Gagal mendapatkan lokasi GPS.", "error");
                    }, 
                    { enableHighAccuracy: true, maximumAge: 0, timeout: 10000 }
                );
            }

            html5QrCode = new Html5Qrcode("reader");
            startScanningProcess();
        });

        btnSwitch.addEventListener('click', function() {
            if (!isScanningProcess) return;
            showNotification("🔄 Memutar kamera...", "info");
            laser.style.display = 'none';

            html5QrCode.stop().then(() => {
                currentFacingMode = (currentFacingMode === "environment") ? "user" : "environment";
                startScanningProcess();
            }).catch(err => {
                showNotification("❌ Gagal menukar kamera.", "error");
            });
        });

        btnFlash.addEventListener('click', async function() {
            if (!isScanningProcess) return;
            if (currentFacingMode === "user") {
                showNotification("⚠️ Senter tidak tersedia di kamera depan.", "error");
                return;
            }
            try {
                isFlashOn = !isFlashOn;
                await html5QrCode.applyVideoConstraints({ advanced: [{ torch: isFlashOn }] });
                if (isFlashOn) {
                    btnFlash.classList.add('text-yellow-400');
                    btnFlash.classList.remove('text-white');
                } else {
                    btnFlash.classList.remove('text-yellow-400');
                    btnFlash.classList.add('text-white');
                }
            } catch (error) {
                showNotification("⚠️ Senter tidak didukung.", "error");
                isFlashOn = !isFlashOn; 
            }
        });

        function onScanSuccess(decodedText) {
            if (sudahScan) return; 
            if (!currentLat || !currentLng) {
                showNotification("⏳ Tunggu sebentar, mengunci GPS...", "error");
                return; 
            }

            sudahScan = true;
            laser.style.animationPlayState = 'paused';
            showNotification("⏳ Memverifikasi data ke server...", "info");

            let nik = decodedText;

            fetch("/absensi", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}" 
                },
                body: JSON.stringify({
                    nik: nik,
                    latitude: currentLat,
                    longitude: currentLng
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    showNotification("✅ " + data.message, "success");
                    
                    // Mainkan suara sukses hanya di sini
                    audioSuccess.muted = false;
                    audioSuccess.currentTime = 0;
                    audioSuccess.play().catch(e => console.log(e));

                    setTimeout(() => { 
                        sudahScan = false; 
                        laser.style.animationPlayState = 'running';
                        showNotification("✅ Siap! Arahkan QR karyawan berikutnya.", "info");
                    }, 3000);
                } else {
                    showNotification("❌ " + data.message, "error");
                    
                    // Mainkan suara gagal hanya di sini
                    audioError.muted = false;
                    audioError.currentTime = 0;
                    audioError.play().catch(e => console.log(e));

                    setTimeout(() => { 
                        sudahScan = false; 
                        laser.style.animationPlayState = 'running';
                        showNotification("⚠️ Arahkan QR Code kembali.", "info");
                    }, 3500); 
                }
            })
            .catch(err => {
                showNotification("❌ Terjadi kesalahan jaringan / server.", "error");
                sudahScan = false;
                laser.style.animationPlayState = 'running';
            });
        }
    </script>
</body>
</html>