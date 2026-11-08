<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    protected $table = 'keuangan';

    protected $primaryKey = 'id_keuangan';

    public $timestamps = false;

    protected $fillable = [
        'id_pengguna',
        'tanggal',
        'jenis',
        'jumlah',
        'sumber',
        'tujuan',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id_pengguna');
    }
}
