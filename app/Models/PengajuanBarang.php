<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanBarang extends Model
{
    protected $table = 'pengajuan_barang';

    protected $primaryKey = 'id_pengajuan_barang';

    protected $fillable = [
        'id_pengguna',
        'id_kategori',
        'id_supplier',
        'nama_barang',
        'satuan',
        'stok',
        'kondisi',
        'status',
        'disetujui_oleh',
        'catatan_admin',
        'diproses_pada',
    ];

    protected $casts = [
        'diproses_pada' => 'datetime',
    ];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id_pengguna');
    }
}
