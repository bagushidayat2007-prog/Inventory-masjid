<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Kategori;
use App\Models\Keuangan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function inventory(Request $request)
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        $barangQuery = Barang::with(['kategori', 'supplier']);
        if ($kategori = $request->input('id_kategori')) {
            $barangQuery->where('id_kategori', $kategori);
        }
        if ($idBarang = $request->input('id_barang')) {
            $barangQuery->where('id_barang', $idBarang);
        }
        $barang = $barangQuery->orderBy('nama_barang')->get();
        $barangList = Barang::orderBy('nama_barang')->get();

        $masukQuery = BarangMasuk::with('pengguna');
        if ($dari = $request->input('dari')) {
            $masukQuery->whereDate('tanggal', '>=', $dari);
        }
        if ($sampai = $request->input('sampai')) {
            $masukQuery->whereDate('tanggal', '<=', $sampai);
        }
        if ($idBarang) {
            $masukQuery->whereHas('detail', fn ($q) => $q->where('id_barang', $idBarang));
        }
        $barangMasuk = $masukQuery->orderByDesc('tanggal')->get();

        $keluarQuery = BarangKeluar::with('pengguna');
        if ($dari = $request->input('dari')) {
            $keluarQuery->whereDate('tanggal', '>=', $dari);
        }
        if ($sampai = $request->input('sampai')) {
            $keluarQuery->whereDate('tanggal', '<=', $sampai);
        }
        if ($idBarang) {
            $keluarQuery->whereHas('detail', fn ($q) => $q->where('id_barang', $idBarang));
        }
        $barangKeluar = $keluarQuery->orderByDesc('tanggal')->get();

        return view('laporan.inventory', compact(
            'kategoris',
            'barangList',
            'barang',
            'barangMasuk',
            'barangKeluar'
        ));
    }

    public function keuangan(Request $request)
    {
        $query = Keuangan::with('pengguna');

        if ($jenis = $request->input('jenis')) {
            $query->where('jenis', $jenis);
        }
        if ($dari = $request->input('dari')) {
            $query->whereDate('tanggal', '>=', $dari);
        }
        if ($sampai = $request->input('sampai')) {
            $query->whereDate('tanggal', '<=', $sampai);
        }

        $totalPemasukan = (clone $query)->where('jenis', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = (clone $query)->where('jenis', 'pengeluaran')->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;

        $keuangan = $query->orderByDesc('tanggal')->get();

        return view('laporan.keuangan', compact('keuangan', 'totalPemasukan', 'totalPengeluaran', 'saldo'));
    }
}
