<?php

namespace App\Http\Controllers;

use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

   
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Membuat kunci berdasarkan username dan IP pengguna
        $key = 'login:' . Str::lower($request->username)
            . '|' . $request->ip();

        // Cek apakah sudah mencapai 3 kali percobaan gagal
        if (RateLimiter::tooManyAttempts($key, 3)) {

            $seconds = RateLimiter::availableIn($key);

            return back()
                ->withInput($request->only('username'))
                ->withErrors([
                    'username' => "Terlalu banyak percobaan login. Silakan tunggu {$seconds} detik."
                ]);
        }

                // Validasi CAPTCHA
        $request->validate([
            'g-recaptcha-response' => ['required'],
        ], [
            'g-recaptcha-response.required' => 'Silakan centang CAPTCHA terlebih dahulu.',
        ]);

        // Verifikasi CAPTCHA ke Google
        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ]
        );

        // Jika CAPTCHA gagal
        if (!$response->successful() || !$response->json('success')) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors([
                    'captcha' => 'Verifikasi CAPTCHA gagal. Silakan coba lagi.'
                ]);
        }

        // Proses autentikasi
        if (Auth::attempt($credentials)) {

            // Reset hitungan jika login berhasil
            RateLimiter::clear($key);

            $request->session()->regenerate();

            AuditLogger::log('Login ke sistem');

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang, ' . Auth::user()->nama . '!');
        }

        // Tambahkan hitungan jika password salah
        RateLimiter::hit($key, 60);

        // Cek apakah sudah salah 3 kali
        if (RateLimiter::tooManyAttempts($key, 3)) {

            return back()
                ->withInput($request->only('username'))
                ->withErrors([
                    'username' => 'Login gagal 3 kali. Silakan tunggu 1 menit sebelum mencoba kembali.'
                ]);
        }

        $sisa = 3 - RateLimiter::attempts($key);

        return back()
            ->withInput($request->only('username'))
            ->withErrors([
                'username' => "Username atau password salah. Sisa percobaan: {$sisa} kali."
            ]);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            AuditLogger::log('Logout');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}
