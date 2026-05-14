<x-guest-layout>
    <div class="split-layout">

        <div class="split-left">
            <img src="{{ asset('images/Password.png') }}" alt="Reset Password Illustration">
        </div>

        <div class="split-right">
            <div class="bg-circles"></div>

            <div class="login-card">
                <h1>Reset Password</h1>
                <p class="subtitle">Masukkan password baru Anda di bawah ini</p>

                @if ($errors->any())
                    <div style="color: #ff3e1d; font-size: 13px; margin-bottom: 20px; text-align: left; padding: 10px; background: #fff5f5; border-radius: 8px;">
                        @foreach ($errors->all() as $error)
                            <div><i class='bx bx-error-circle'></i> {{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    {{-- Token --}}
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    {{-- Email --}}
                    <div class="input-group">
                        <i class='bx bx-envelope icon-left'></i>
                        <input
                            type="email"
                            name="email"
                            placeholder="Alamat Email"
                            value="{{ old('email', $request->email) }}"
                            required
                            autofocus
                            autocomplete="username"
                        />
                    </div>

                    {{-- Password Baru --}}
                    <div class="input-group">
                        <i class='bx bx-lock-alt icon-left'></i>
                        <input
                            type="password"
                            id="new_password"
                            name="password"
                            placeholder="Password Baru"
                            required
                            autocomplete="new-password"
                        />
                        <span id="toggleNewPassword" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #a1acb8;">
                            <i class='bx bx-hide'></i>
                        </span>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="input-group">
                        <i class='bx bx-lock icon-left'></i>
                        <input
                            type="password"
                            id="confirm_password"
                            name="password_confirmation"
                            placeholder="Konfirmasi Password Baru"
                            required
                            autocomplete="new-password"
                        />
                        <span id="toggleConfirmPassword" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #a1acb8;">
                            <i class='bx bx-hide'></i>
                        </span>
                    </div>

                    <button type="submit" class="btn-submit">
                        Simpan Password Baru
                    </button>

                    <div style="text-align: center;">
                        <a href="{{ route('login') }}" class="forgot-link">
                            Kembali ke Login
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        function toggleVisibility(toggleId, inputId) {
            const toggle = document.getElementById(toggleId);
            const input = document.getElementById(inputId);
            if (toggle && input) {
                toggle.addEventListener('click', function () {
                    const type = input.type === 'password' ? 'text' : 'password';
                    input.type = type;
                    const icon = this.querySelector('i');
                    icon.classList.toggle('bx-hide');
                    icon.classList.toggle('bx-show');
                });
            }
        }
        document.addEventListener('DOMContentLoaded', function () {
            toggleVisibility('toggleNewPassword', 'new_password');
            toggleVisibility('toggleConfirmPassword', 'confirm_password');
        });
    </script>
</x-guest-layout>
