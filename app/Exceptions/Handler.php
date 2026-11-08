<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Tangani CSRF token kedaluwarsa (419) dengan pesan yang ramah
        // alih-alih halaman "Page Expired". Umumnya terjadi saat pengguna
        // me-refresh/back halaman logout atau token sudah kadaluarsa.
        // Catatan: Laravel mengubah TokenMismatchException menjadi
        // HttpException(419) sebelum callback ini dijalankan, sehingga
        // kita harus memeriksa status 419.
        $this->renderable(function (HttpException $e, $request) {
            if ($e->getStatusCode() === 419) {
                return redirect()
                    ->route('login')
                    ->with('error', 'Sesi Anda telah berakhir atau Anda sudah keluar. Silakan login kembali.');
            }

            return null;
        });
    }
}
