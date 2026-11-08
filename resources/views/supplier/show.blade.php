@extends('layouts.app')

@section('title', 'Detail Supplier')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Detail Supplier</h2>
            <div class="btn-group">
                <a href="{{ route('supplier.edit', $supplier) }}" class="btn btn-secondary btn-sm">Edit</a>
                <a href="{{ route('supplier.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
            </div>
        </div>
        <div class="card-body">
            <dl class="detail-list">
                <dt>Nama Supplier</dt>
                <dd>{{ $supplier->nama_supplier }}</dd>
                <dt>Alamat</dt>
                <dd>{{ $supplier->alamat ?? '-' }}</dd>
                <dt>No Telepon</dt>
                <dd>{{ $supplier->no_telp ?? '-' }}</dd>
            </dl>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Barang dari Supplier Ini</h2>
        </div>
        <div class="table-responsive">
            <table class="data">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th class="text-right">Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($supplier->barang as $barang)
                        <tr>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                            <td>{{ $barang->satuan }}</td>
                            <td class="text-right">{{ $barang->stok }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">Belum ada barang dari supplier ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
