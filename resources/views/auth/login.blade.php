<x-guest-layout>
    <div class="split-layout">
        
        <div class="split-left">
            <img src="{{ asset('images/Login.png') }}" alt="Visual Login">
        </div>

        <div class="split-right">
            <div class="bg-circles"></div>

            <div class="login-card">
                <h1>Halo!</h1>
                <p class="subtitle">Silakan Masuk untuk Memulai</p>

                @if ($errors->any())
                    <div style="color: #ff3e1d; font-size: 13px; margin-bottom: 20px; text-align: left;">
                        @foreach ($errors->all() as $error)
                            <div><i class='bx bx-error-circle'></i> {{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="input-group">
                        <i class='bx bx-envelope icon-left'></i>
                        <input type="email" name="email" placeholder="Alamat Email" value="{{ old('email') }}" required autofocus />
                    </div>

                    <div class="input-group">
                        <i class='bx bx-lock-alt icon-left'></i>
                        <input type="password" id="password" name="password" placeholder="Kata Sandi" required />
                        <span id="togglePassword" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #a1acb8;">
                            <i class='bx bx-hide'></i>
                        </span>
                    </div>

                    <button type="submit" class="btn-submit">
                        Masuk
                    </button>

                    <div>
                        <a href="{{ route('password.request') }}" class="forgot-link">Lupa Kata Sandi?</a>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.getElementById('togglePassword');
            const passwordField = document.getElementById('password');

            if(togglePassword && passwordField) {
                togglePassword.addEventListener('click', function () {
                    const type = passwordField.type === 'password' ? 'text' : 'password';
                    passwordField.type = type;
                    const icon = this.querySelector('i');
                    icon.classList.toggle('bx-hide');
                    icon.classList.toggle('bx-show');
                });
            }
        });
    </script>
</x-guest-layout>