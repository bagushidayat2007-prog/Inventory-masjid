<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'pengguna';

    protected $primaryKey = 'id_pengguna';

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'username',
        'password',
        'role',
        'no_telp',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
        'created_at' => 'datetime',
    ];

    public const ROLE_ADMIN = 'admin';

    public const ROLE_BENDAHARA = 'bendahara';

    public const ROLE_PETUGAS = 'petugas_inventory';

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isBendahara(): bool
    {
        return $this->role === self::ROLE_BENDAHARA;
    }

    public function isPetugas(): bool
    {
        return $this->role === self::ROLE_PETUGAS;
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_BENDAHARA => 'Bendahara',
            self::ROLE_PETUGAS => 'Petugas Inventory',
            default => $this->role,
        };
    }

    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class, 'id_pengguna', 'id_pengguna');
    }

    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluar::class, 'id_pengguna', 'id_pengguna');
    }

    public function keuangan()
    {
        return $this->hasMany(Keuangan::class, 'id_pengguna', 'id_pengguna');
    }

    public function auditLog()
    {
        return $this->hasMany(AuditLog::class, 'id_pengguna', 'id_pengguna');
    }
}
