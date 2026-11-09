@extends('layouts.app')

@section('title', 'Data Supplier')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Daftar Supplier</h2>
            <div style="display:flex; gap:8px; align-items:flex-end;">
                <form method="GET" class="filter-bar">
                    <div class="form-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari supplier..." value="{{ request('search') }}">
                    </div>
                    <button type="submit" class="btn btn-secondary">Cari</button>
                    @if (request('search'))
                        <a href="{{ route('supplier.index') }}" class="btn btn-secondary">Reset</a>
                    @endif
                </form>

                @if (auth()->user()->role !== 'petugas_inventory')
                    <a href="{{ route('supplier.create') }}" class="btn btn-primary">
                        + Tambah Supplier
                    </a>
                @endif
                
            </div>
        </div>

        <div class="table-responsive">
            <table class="data">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Nama Supplier</th>
                        <th>Alamat</th>
                        <th>No Telepon</th>
                        <th>Jumlah Barang</th>
                        <th style="width:200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($supplier as $item)
                        <tr>
                            <td>{{ $supplier->firstItem() + $loop->index }}</td>
                            <td>{{ $item->nama_supplier }}</td>
                            <td class="wrap">{{ $item->alamat ?? '-' }}</td>
                            <td>{{ $item->no_telp ?? '-' }}</td>
                            <td><span class="badge badge-blue">{{ $item->barang_count }} barang</span></td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('supplier.show', $item) }}" class="btn btn-secondary btn-sm">Detail</a>
                                    
                                    @if (auth()->user()->role !== 'petugas_inventory')
                                        <a href="{{ route('supplier.edit', $item) }}" class="btn btn-secondary btn-sm">
                                            Edit
                                        </a>
                                    @endif
                                    <form action="{{ route('supplier.destroy', $item) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        @if (auth()->user()->role !== 'petugas_inventory')
                                            <form action="{{ route('supplier.destroy', $item) }}"
                                                method="POST"
                                                style="display: inline;"
                                                onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <span class="emoji">&#127981;</span>
                                    Belum ada data supplier.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($supplier->hasPages())
            <div class="pagination-wrap">{{ $supplier->links() }}</div>
        @endif
    </div>
@endsection
