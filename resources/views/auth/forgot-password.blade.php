<x-guest-layout>
    <div class="split-layout">
        
        <div class="split-left">
            <img src="{{ asset('images/Password.png') }}" alt="Forgot Password Illustration">
        </div>

        <div class="split-right">
            <div class="bg-circles"></div>

            <div class="login-card">
                <h1>Lupa Password?</h1>
                <p class="subtitle">Masukkan email Anda untuk atur ulang kata sandi</p>

                @if (session('status'))
                    <div style="background: #e7e7ff; color: #696cff; padding: 12px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; border: 1px solid rgba(105, 108, 255, 0.2); text-align: left;">
                        <i class='bx bx-send'></i> {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div style="color: #ff3e1d; font-size: 13px; margin-bottom: 20px; text-align: left; padding: 10px; background: #fff5f5; border-radius: 8px;">
                        @foreach ($errors->all() as $error)
                            <div><i class='bx bx-error-circle'></i> {{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="input-group">
                        <i class='bx bx-envelope icon-left'></i>
                        <input type="email" name="email" placeholder="Alamat Email" value="{{ old('email') }}" required autofocus />
                    </div>

                    <button type="submit" class="btn-submit">
                        Kirim Link Reset
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
</x-guest-layout>