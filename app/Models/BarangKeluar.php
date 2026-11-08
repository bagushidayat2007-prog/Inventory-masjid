<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    protected $table = 'barang_keluar';

    protected $primaryKey = 'id_keluar';

    public $timestamps = false;

    protected $fillable = [
        'id_pengguna',
        'tanggal',
        'tujuan',
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
        return $this->hasMany(DetailBarangKeluar::class, 'id_keluar', 'id_keluar');
    }
}
