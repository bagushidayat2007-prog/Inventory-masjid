<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Support\AuditLogger;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::withCount('barang');

        if ($search = $request->input('search')) {
            $query->where('nama_supplier', 'like', "%{$search}%")
                ->orWhere('no_telp', 'like', "%{$search}%");
        }

        $supplier = $query->orderBy('nama_supplier')->paginate(10)->withQueryString();

        return view('supplier.index', compact('supplier'));
    }

    public function create()
    {
        return view('supplier.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_supplier' => ['required', 'string', 'max:100'],
            'alamat' => ['nullable', 'string'],
            'no_telp' => ['nullable', 'string', 'max:20'],
        ], [
            'nama_supplier.required' => 'Nama supplier wajib diisi.',
        ]);

        Supplier::create($data);

        AuditLogger::log('Menambahkan data supplier: '.$data['nama_supplier']);

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load(['barang.kategori']);

        return view('supplier.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('supplier.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'nama_supplier' => ['required', 'string', 'max:100'],
            'alamat' => ['nullable', 'string'],
            'no_telp' => ['nullable', 'string', 'max:20'],
        ], [
            'nama_supplier.required' => 'Nama supplier wajib diisi.',
        ]);

        $supplier->update($data);

        AuditLogger::log('Mengubah data supplier: '.$data['nama_supplier']);

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->barang()->exists()) {
            return back()->with('error', 'Supplier tidak dapat dihapus karena masih digunakan oleh data barang.');
        }

        $nama = $supplier->nama_supplier;
        $supplier->delete();

        AuditLogger::log('Menghapus data supplier: '.$nama);

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil dihapus.');
    }
}
