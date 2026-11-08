<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Barang;
use App\Models\Keuangan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang = Barang::count();
        $totalStok = (int) Barang::sum('stok');
        $totalMasuk = (int) DB::table('detail_barang_masuk')->sum('jumlah');
        $totalKeluar = (int) DB::table('detail_barang_keluar')->sum('jumlah');

        $totalPemasukan = (float) Keuangan::where('jenis', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = (float) Keuangan::where('jenis', 'pengeluaran')->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;

        // Grafik keuangan 6 bulan terakhir
        $bulanLabels = [];
        $dataPemasukan = [];
        $dataPengeluaran = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $bulanLabels[] = $date->translatedFormat('M Y');
            $dataPemasukan[] = (float) Keuangan::where('jenis', 'pemasukan')
                ->whereYear('tanggal', $date->year)
                ->whereMonth('tanggal', $date->month)
                ->sum('jumlah');
            $dataPengeluaran[] = (float) Keuangan::where('jenis', 'pengeluaran')
                ->whereYear('tanggal', $date->year)
                ->whereMonth('tanggal', $date->month)
                ->sum('jumlah');
        }

        // Grafik stok per kategori
        $stokLabels = [];
        $stokData = [];
        foreach (DB::table('kategori')->get() as $kategori) {
            $stokLabels[] = $kategori->nama_kategori;
            $stokData[] = (int) Barang::where('id_kategori', $kategori->id_kategori)->sum('stok');
        }

        $barangStokRendah = Barang::with('kategori')->orderBy('stok')->take(5)->get();
        $aktivitasTerbaru = AuditLog::with('pengguna')->latest('waktu')->take(5)->get();
        $transaksiTerbaru = Keuangan::with('pengguna')->latest('tanggal')->latest('id_keuangan')->take(5)->get();

        return view('dashboard.index', compact(
            'totalBarang',
            'totalStok',
            'totalMasuk',
            'totalKeluar',
            'totalPemasukan',
            'totalPengeluaran',
            'saldo',
            'bulanLabels',
            'dataPemasukan',
            'dataPengeluaran',
            'stokLabels',
            'stokData',
            'barangStokRendah',
            'aktivitasTerbaru',
            'transaksiTerbaru'
        ));
    }
}
