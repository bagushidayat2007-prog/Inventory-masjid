<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    protected $table = 'barang_masuk';

    protected $primaryKey = 'id_masuk';

    public $timestamps = false;

    protected $fillable = [
        'id_pengguna',
        'tanggal',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id_pengguna');
    }

    public function detail()
    {
        return $this->hasMany(DetailBarangMasuk::class, 'id_masuk', 'id_masuk');
    }
}
