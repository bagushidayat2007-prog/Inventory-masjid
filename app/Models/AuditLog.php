<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_log';

    protected $primaryKey = 'id_log';

    public $timestamps = false;

    protected $fillable = [
        'id_pengguna',
        'aktivitas',
        'waktu',
        'ip_address',
    ];

    protected $casts = [
        'waktu' => 'datetime',
    ];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id_pengguna');
    }
}
