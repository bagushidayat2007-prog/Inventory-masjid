<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login &middot; {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
<div class="login-page">
    <div class="login-card">
        <div class="brand">
            <div class="logo">&#127964;</div>
            <h1>Sistem Inventory &amp; Keuangan Masjid</h1>
            <p>Silakan masuk untuk melanjutkan</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <span>&#10004;</span>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                <span>&#9888;</span>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <span>&#9888;</span>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <form action="{{ route('login.attempt') }}" method="POST" data-loading>
            @csrf
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control @error('username') is-invalid @enderror"
                       value="{{ old('username') }}" placeholder="Masukkan username" autofocus required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>

                <div class="password-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Masukkan password"
                        required
                    >
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <i class="fa-solid fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            {{-- Google reCAPTCHA --}}
            <div class="form-group" style="margin-top: 20px;">
                <div class="g-recaptcha"
                    data-sitekey="{{ config('services.recaptcha.site_key') }}">
                </div>

                @error('captcha')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

                @error('g-recaptcha-response')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:11px; margin-top: 30px;">
                Login
            </button>
        </form>
    </div>
</div>
<script>
    function togglePassword() {
        const password = document.getElementById("password");
        const eyeIcon = document.getElementById("eyeIcon");

        if (password.type === "password") {
            password.type = "text";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        } else {
            password.type = "password";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        }
    }
</script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
