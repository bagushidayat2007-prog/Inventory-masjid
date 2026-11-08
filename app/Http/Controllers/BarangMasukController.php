<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\DetailBarangMasuk;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangMasuk::with('pengguna')->withCount('detail');

        if ($dari = $request->input('dari')) {
            $query->whereDate('tanggal', '>=', $dari);
        }
        if ($sampai = $request->input('sampai')) {
            $query->whereDate('tanggal', '<=', $sampai);
        }

        $barangMasuk = $query->orderByDesc('tanggal')->orderByDesc('id_masuk')->paginate(10)->withQueryString();

        return view('barang-masuk.index', compact('barangMasuk'));
    }

    public function create()
    {
        $barangList = Barang::orderBy('nama_barang')->get();

        return view('barang-masuk.create', compact('barangList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'detail' => ['required', 'array', 'min:1'],
            'detail.*.id_barang' => ['required', 'exists:barang,id_barang'],
            'detail.*.jumlah' => ['required', 'integer', 'min:1'],
        ], $this->messages());

        DB::transaction(function () use ($request, $data) {
            $barangMasuk = BarangMasuk::create([
                'id_pengguna' => $request->user()->id_pengguna,
                'tanggal' => $data['tanggal'],
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            foreach ($data['detail'] as $item) {
                DetailBarangMasuk::create([
                    'id_masuk' => $barangMasuk->id_masuk,
                    'id_barang' => $item['id_barang'],
                    'jumlah' => $item['jumlah'],
                ]);

                Barang::where('id_barang', $item['id_barang'])->increment('stok', $item['jumlah']);
            }

            AuditLogger::log('Menambahkan barang masuk #'.$barangMasuk->id_masuk);
        });

        return redirect()->route('barang-masuk.index')->with('success', 'Transaksi barang masuk berhasil disimpan dan stok bertambah.');
    }

    public function show(BarangMasuk $barangMasuk)
    {
        $barangMasuk->load(['pengguna', 'detail.barang.kategori']);

        return view('barang-masuk.show', compact('barangMasuk'));
    }

    public function edit(BarangMasuk $barangMasuk)
    {
        $barangMasuk->load('detail');
        $barangList = Barang::orderBy('nama_barang')->get();

        return view('barang-masuk.edit', compact('barangMasuk', 'barangList'));
    }

    public function update(Request $request, BarangMasuk $barangMasuk)
    {
        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'detail' => ['required', 'array', 'min:1'],
            'detail.*.id_barang' => ['required', 'exists:barang,id_barang'],
            'detail.*.jumlah' => ['required', 'integer', 'min:1'],
        ], $this->messages());

        DB::transaction(function () use ($barangMasuk, $data) {
            // Kembalikan stok lama terlebih dahulu
            foreach ($barangMasuk->detail as $old) {
                Barang::where('id_barang', $old->id_barang)->decrement('stok', $old->jumlah);
            }
            $barangMasuk->detail()->delete();

            $barangMasuk->update([
                'tanggal' => $data['tanggal'],
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            foreach ($data['detail'] as $item) {
                DetailBarangMasuk::create([
                    'id_masuk' => $barangMasuk->id_masuk,
                    'id_barang' => $item['id_barang'],
                    'jumlah' => $item['jumlah'],
                ]);

                Barang::where('id_barang', $item['id_barang'])->increment('stok', $item['jumlah']);
            }

            AuditLogger::log('Mengubah barang masuk #'.$barangMasuk->id_masuk);
        });

        return redirect()->route('barang-masuk.index')->with('success', 'Transaksi barang masuk berhasil diperbarui.');
    }

    public function destroy(BarangMasuk $barangMasuk)
    {
        DB::transaction(function () use ($barangMasuk) {
            foreach ($barangMasuk->detail as $detail) {
                Barang::where('id_barang', $detail->id_barang)->decrement('stok', $detail->jumlah);
            }

            $id = $barangMasuk->id_masuk;
            $barangMasuk->delete();

            AuditLogger::log('Menghapus barang masuk #'.$id);
        });

        return redirect()->route('barang-masuk.index')->with('success', 'Transaksi barang masuk berhasil dihapus dan stok dikembalikan.');
    }

    private function messages(): array
    {
        return [
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'detail.required' => 'Minimal satu barang harus ditambahkan.',
            'detail.min' => 'Minimal satu barang harus ditambahkan.',
            'detail.*.id_barang.required' => 'Barang wajib dipilih.',
            'detail.*.id_barang.exists' => 'Barang tidak valid.',
            'detail.*.jumlah.required' => 'Jumlah wajib diisi.',
            'detail.*.jumlah.integer' => 'Jumlah harus berupa angka.',
            'detail.*.jumlah.min' => 'Jumlah barang masuk harus lebih dari 0.',
        ];
    }
}
