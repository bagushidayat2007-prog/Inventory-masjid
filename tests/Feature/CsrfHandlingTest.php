<?php

namespace Tests\Feature;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class CsrfHandlingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Catatan: VerifyCsrfToken di-bypass otomatis saat pengujian
     * (runningUnitTests), sehingga tidak bisa diuji lewat request POST biasa.
     * Di sini kita uji langsung Exception Handler-nya.
     */
    public function test_http_419_redirects_to_login_with_error_message(): void
    {
        $response = $this->app->make(ExceptionHandler::class)->render(
            request(),
            new HttpException(419, 'CSRF token mismatch.')
        );

        $this->assertTrue($response->isRedirect(route('login')));
        $this->assertSame(
            'Sesi Anda telah berakhir atau Anda sudah keluar. Silakan login kembali.',
            session('error')
        );
    }

    public function test_other_http_errors_are_not_redirected(): void
    {
        $response = $this->app->make(ExceptionHandler::class)->render(
            request(),
            new NotFoundHttpException('Not found.')
        );

        $this->assertFalse($response->isRedirect(route('login')));
    }
}
