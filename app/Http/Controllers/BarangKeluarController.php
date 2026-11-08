<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\DetailBarangKeluar;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BarangKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangKeluar::with('pengguna')->withCount('detail');

        if ($dari = $request->input('dari')) {
            $query->whereDate('tanggal', '>=', $dari);
        }
        if ($sampai = $request->input('sampai')) {
            $query->whereDate('tanggal', '<=', $sampai);
        }

        $barangKeluar = $query->orderByDesc('tanggal')->orderByDesc('id_keluar')->paginate(10)->withQueryString();

        return view('barang-keluar.index', compact('barangKeluar'));
    }

    public function create()
    {
        $barangList = Barang::orderBy('nama_barang')->get();

        return view('barang-keluar.create', compact('barangList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'tujuan' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'detail' => ['required', 'array', 'min:1'],
            'detail.*.id_barang' => ['required', 'exists:barang,id_barang'],
            'detail.*.jumlah' => ['required', 'integer', 'min:1'],
        ], $this->messages());

        DB::transaction(function () use ($request, $data) {
            $barangKeluar = BarangKeluar::create([
                'id_pengguna' => $request->user()->id_pengguna,
                'tanggal' => $data['tanggal'],
                'tujuan' => $data['tujuan'] ?? null,
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            foreach ($data['detail'] as $item) {
                $barang = Barang::lockForUpdate()->find($item['id_barang']);

                if ($barang->stok < $item['jumlah']) {
                    throw ValidationException::withMessages([
                        'detail' => "Stok tidak mencukupi untuk barang \"{$barang->nama_barang}\". Stok tersedia: {$barang->stok}.",
                    ]);
                }

                DetailBarangKeluar::create([
                    'id_keluar' => $barangKeluar->id_keluar,
                    'id_barang' => $item['id_barang'],
                    'jumlah' => $item['jumlah'],
                ]);

                $barang->decrement('stok', $item['jumlah']);
            }

            AuditLogger::log('Menambahkan barang keluar #'.$barangKeluar->id_keluar);
        });

        return redirect()->route('barang-keluar.index')->with('success', 'Transaksi barang keluar berhasil disimpan dan stok berkurang.');
    }

    public function show(BarangKeluar $barangKeluar)
    {
        $barangKeluar->load(['pengguna', 'detail.barang.kategori']);

        return view('barang-keluar.show', compact('barangKeluar'));
    }

    public function edit(BarangKeluar $barangKeluar)
    {
        $barangKeluar->load('detail');
        $barangList = Barang::orderBy('nama_barang')->get();

        return view('barang-keluar.edit', compact('barangKeluar', 'barangList'));
    }

    public function update(Request $request, BarangKeluar $barangKeluar)
    {
        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'tujuan' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'detail' => ['required', 'array', 'min:1'],
            'detail.*.id_barang' => ['required', 'exists:barang,id_barang'],
            'detail.*.jumlah' => ['required', 'integer', 'min:1'],
        ], $this->messages());

        DB::transaction(function () use ($barangKeluar, $data) {
            // Kembalikan stok lama terlebih dahulu
            foreach ($barangKeluar->detail as $old) {
                Barang::where('id_barang', $old->id_barang)->increment('stok', $old->jumlah);
            }
            $barangKeluar->detail()->delete();

            $barangKeluar->update([
                'tanggal' => $data['tanggal'],
                'tujuan' => $data['tujuan'] ?? null,
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            foreach ($data['detail'] as $item) {
                $barang = Barang::lockForUpdate()->find($item['id_barang']);

                if ($barang->stok < $item['jumlah']) {
                    throw ValidationException::withMessages([
                        'detail' => "Stok tidak mencukupi untuk barang \"{$barang->nama_barang}\". Stok tersedia: {$barang->stok}.",
                    ]);
                }

                DetailBarangKeluar::create([
                    'id_keluar' => $barangKeluar->id_keluar,
                    'id_barang' => $item['id_barang'],
                    'jumlah' => $item['jumlah'],
                ]);

                $barang->decrement('stok', $item['jumlah']);
            }

            AuditLogger::log('Mengubah barang keluar #'.$barangKeluar->id_keluar);
        });

        return redirect()->route('barang-keluar.index')->with('success', 'Transaksi barang keluar berhasil diperbarui.');
    }

    public function destroy(BarangKeluar $barangKeluar)
    {
        DB::transaction(function () use ($barangKeluar) {
            foreach ($barangKeluar->detail as $detail) {
                Barang::where('id_barang', $detail->id_barang)->increment('stok', $detail->jumlah);
            }

            $id = $barangKeluar->id_keluar;
            $barangKeluar->delete();

            AuditLogger::log('Menghapus barang keluar #'.$id);
        });

        return redirect()->route('barang-keluar.index')->with('success', 'Transaksi barang keluar berhasil dihapus dan stok dikembalikan.');
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
            'detail.*.jumlah.min' => 'Jumlah barang keluar harus lebih dari 0.',
        ];
    }
}
