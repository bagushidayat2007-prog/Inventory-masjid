<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Supplier;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with(['kategori', 'supplier']);

        if ($search = $request->input('search')) {
            $query->where('nama_barang', 'like', "%{$search}%");
        }

        if ($kategori = $request->input('id_kategori')) {
            $query->where('id_kategori', $kategori);
        }

        if ($kondisi = $request->input('kondisi')) {
            $query->where('kondisi', $kondisi);
        }

        $sort = $request->input('sort', 'nama_barang');
        $direction = $request->input('direction', 'asc') === 'desc' ? 'desc' : 'asc';
        $allowed = ['nama_barang', 'stok', 'kondisi'];
        if (! in_array($sort, $allowed, true)) {
            $sort = 'nama_barang';
        }

        $barang = $query->orderBy($sort, $direction)->paginate(10)->withQueryString();
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('barang.index', compact('barang', 'kategoris'));
    }

    public function create()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $suppliers = Supplier::orderBy('nama_supplier')->get();

        return view('barang.create', compact('kategoris', 'suppliers'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        Barang::create($data);

        AuditLogger::log('Menambahkan data barang: '.$data['nama_barang']);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Barang $barang)
    {
        $barang->load(['kategori', 'supplier']);

        $riwayatMasuk = $barang->detailBarangMasuk()
            ->with('barangMasuk.pengguna')
            ->latest('id_detail')
            ->take(10)
            ->get();

        $riwayatKeluar = $barang->detailBarangKeluar()
            ->with('barangKeluar.pengguna')
            ->latest('id_detail')
            ->take(10)
            ->get();

        return view('barang.show', compact('barang', 'riwayatMasuk', 'riwayatKeluar'));
    }

    public function edit(Barang $barang)
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $suppliers = Supplier::orderBy('nama_supplier')->get();

        return view('barang.edit', compact('barang', 'kategoris', 'suppliers'));
    }

    public function update(Request $request, Barang $barang)
    {
        $data = $this->validateData($request, $barang);

        $barang->update($data);

        AuditLogger::log('Mengubah data barang: '.$data['nama_barang']);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->detailBarangMasuk()->exists() || $barang->detailBarangKeluar()->exists()) {
            return back()->with('error', 'Barang tidak dapat dihapus karena sudah memiliki riwayat transaksi. Hapus transaksinya terlebih dahulu.');
        }

        $nama = $barang->nama_barang;
        $barang->delete();

        AuditLogger::log('Menghapus data barang: '.$nama);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }

    private function validateData(Request $request, ?Barang $barang = null): array
    {
        return $request->validate([
            'nama_barang' => ['required', 'string', 'max:100'],
            'id_kategori' => ['required', 'exists:kategori,id_kategori'],
            'id_supplier' => ['nullable', 'exists:supplier,id_supplier'],
            'satuan' => ['required', 'string', 'max:30'],
            'stok' => ['required', 'integer', 'min:0'],
            'kondisi' => ['required', Rule::in(['baik', 'rusak_ringan', 'rusak_berat'])],
        ], [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'id_kategori.required' => 'Kategori wajib dipilih.',
            'id_kategori.exists' => 'Kategori tidak valid.',
            'id_supplier.exists' => 'Supplier tidak valid.',
            'satuan.required' => 'Satuan wajib diisi.',
            'stok.required' => 'Stok wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka.',
            'stok.min' => 'Stok tidak boleh negatif.',
            'kondisi.required' => 'Kondisi wajib dipilih.',
        ]);
    }
}
