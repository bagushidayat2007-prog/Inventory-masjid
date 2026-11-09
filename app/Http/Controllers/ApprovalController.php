<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Keuangan;
use App\Models\PengajuanBarang;
use App\Models\PengajuanPengeluaran;
use App\Support\AuditLogger;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    // Menampilkan daftar pengajuan
    public function index()
    {
        $pengajuanBarang = PengajuanBarang::with('pengguna')
            ->latest()
            ->paginate(10, ['*'], 'barang_page');

        $pengajuanPengeluaran = PengajuanPengeluaran::with('pengguna')
            ->latest()
            ->paginate(10, ['*'], 'pengeluaran_page');

        return view('approval.index', compact(
            'pengajuanBarang',
            'pengajuanPengeluaran'
        ));
    }

    // Menyetujui pengajuan barang
    public function setujuiBarang($id)
    {
        DB::transaction(function () use ($id) {
            $pengajuan = PengajuanBarang::where(
                'id_pengajuan_barang',
                $id
            )->lockForUpdate()->firstOrFail();

            if ($pengajuan->status !== 'menunggu') {
                abort(422, 'Pengajuan barang sudah diproses.');
            }

            Barang::create([
                'id_kategori' => $pengajuan->id_kategori,
                'id_supplier' => $pengajuan->id_supplier,
                'nama_barang' => $pengajuan->nama_barang,
                'satuan' => $pengajuan->satuan,
                'stok' => $pengajuan->stok,
                'kondisi' => $pengajuan->kondisi,
            ]);

            $pengajuan->update([
                'status' => 'disetujui',
                'disetujui_oleh' => auth()->user()->id_pengguna,
                'diproses_pada' => now(),
            ]);
        });

        AuditLogger::log('Menyetujui pengajuan penambahan barang #' . $id);

        return back()->with('success', 'Pengajuan barang berhasil disetujui.');
    }

    // Menyetujui pengajuan pengeluaran
    public function setujuiPengeluaran($id)
    {
        DB::transaction(function () use ($id) {
            $pengajuan = PengajuanPengeluaran::where(
                'id_pengajuan_pengeluaran',
                $id
            )->lockForUpdate()->firstOrFail();

            if ($pengajuan->status !== 'menunggu') {
                abort(422, 'Pengajuan pengeluaran sudah diproses.');
            }

            Keuangan::create([
                'id_pengguna' => $pengajuan->id_pengguna,
                'tanggal' => $pengajuan->tanggal,
                'jenis' => 'pengeluaran',
                'jumlah' => $pengajuan->jumlah,
                'sumber' => null,
                'tujuan' => $pengajuan->tujuan,
                'keterangan' => $pengajuan->keterangan,
            ]);

            $pengajuan->update([
                'status' => 'disetujui',
                'disetujui_oleh' => auth()->user()->id_pengguna,
                'diproses_pada' => now(),
            ]);
        });

        AuditLogger::log('Menyetujui pengajuan pengeluaran #' . $id);

        return back()->with('success', 'Pengajuan pengeluaran berhasil disetujui.');
    }

    // Menolak pengajuan barang
    public function tolakBarang($id)
    {
        $pengajuan = PengajuanBarang::findOrFail($id);

        if ($pengajuan->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan barang sudah diproses.');
        }

        $pengajuan->update([
            'status' => 'ditolak',
            'disetujui_oleh' => auth()->user()->id_pengguna,
            'catatan_admin' => 'Pengajuan ditolak oleh admin.',
            'diproses_pada' => now(),
        ]);

        AuditLogger::log('Menolak pengajuan barang #' . $id);

        return back()->with('success', 'Pengajuan barang berhasil ditolak.');
    }

    // Menolak pengajuan pengeluaran
    public function tolakPengeluaran($id)
    {
        $pengajuan = PengajuanPengeluaran::findOrFail($id);

        if ($pengajuan->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan pengeluaran sudah diproses.');
        }

        $pengajuan->update([
            'status' => 'ditolak',
            'disetujui_oleh' => auth()->user()->id_pengguna,
            'catatan_admin' => 'Pengajuan ditolak oleh admin.',
            'diproses_pada' => now(),
        ]);

        AuditLogger::log('Menolak pengajuan pengeluaran #' . $id);

        return back()->with('success', 'Pengajuan pengeluaran berhasil ditolak.');
    }
}
