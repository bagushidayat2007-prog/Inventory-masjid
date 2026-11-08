<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Support\AuditLogger;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $query = Kategori::withCount('barang');

        if ($search = $request->input('search')) {
            $query->where('nama_kategori', 'like', "%{$search}%");
        }

        $kategori = $query->orderBy('nama_kategori')->paginate(10)->withQueryString();

        return view('kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100'],
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
        ]);

        Kategori::create($data);

        AuditLogger::log('Menambahkan data kategori: '.$data['nama_kategori']);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $data = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100'],
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
        ]);

        $kategori->update($data);

        AuditLogger::log('Mengubah data kategori: '.$data['nama_kategori']);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->barang()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh data barang.');
        }

        $nama = $kategori->nama_kategori;
        $kategori->delete();

        AuditLogger::log('Menghapus data kategori: '.$nama);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
