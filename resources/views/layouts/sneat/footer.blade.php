<footer class="content-footer footer bg-footer-theme border-top">
    <div class="container-xxl d-flex flex-wrap justify-content-between py-3 flex-md-row flex-column">
        <div class="mb-2 mb-md-0 text-muted">
            <span class="fw-medium">© <script>document.write(new Date().getFullYear());</script></span>
            <span class="mx-1">|</span> 
            <span class="fw-bold text-primary">RBTV</span>
            <span class="d-none d-inline-block ms-1"> — Managed by <a href="#" target="_blank" class="footer-link fw-bolder">RBTV Team</a></span>
        </div>

        {{-- Sisi Kanan: Links & Status --}}
        <div class="d-none d-lg-inline-block">
            <a href="javascript:void(0)" class="footer-link me-4 text-muted small"><i class="bx bx-check-circle me-1 text-success"></i>System Online</a>
        </div>
    </div>
</footer>

<style>
    /* Tambahan style agar footer terlihat menyatu dengan layout */
    .content-footer {
        background-color: transparent !important; /* Agar warna mengikuti background dashboard */
    }
    .footer-link {
        font-size: 0.85rem;
        transition: color 0.2s ease;
    }
    .footer-link:hover {
        color: #696cff !important;
    }
</style>