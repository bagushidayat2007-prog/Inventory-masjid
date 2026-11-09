<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanPengeluaran extends Model
{
    protected $table = 'pengajuan_pengeluaran';

    protected $primaryKey = 'id_pengajuan_pengeluaran';

    protected $fillable = [
        'id_pengguna',
        'tanggal',
        'jumlah',
        'tujuan',
        'keterangan',
        'status',
        'disetujui_oleh',
        'catatan_admin',
        'diproses_pada',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
        'diproses_pada' => 'datetime',
    ];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id_pengguna');
    }
}
