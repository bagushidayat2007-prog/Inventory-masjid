<?php

namespace App\Support;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    public static function log(string $aktivitas): void
    {
        AuditLog::create([
            'id_pengguna' => Auth::id(),
            'aktivitas' => $aktivitas,
            'waktu' => now(),
            'ip_address' => request()->ip(),
        ]);
    }
}
