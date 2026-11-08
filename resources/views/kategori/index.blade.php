@extends('layouts.app')

@section('title', 'Data Kategori')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Daftar Kategori</h2>
            <div style="display:flex; gap:8px; align-items:flex-end;">
                <form method="GET" class="filter-bar">
                    <div class="form-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari kategori..." value="{{ request('search') }}">
                    </div>
                    <button type="submit" class="btn btn-secondary">Cari</button>
                    @if (request('search'))
                        <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Reset</a>
                    @endif
                </form>
                
                @if (auth()->user()->role !== 'admin')
                    <a href="{{ route('kategori.create') }}" class="btn btn-primary">
                        + Tambah Kategori
                    </a>
                @endif
            </div>
        </div>

        <div class="table-responsive">
            <table class="data">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Nama Kategori</th>
                        <th>Jumlah Barang</th>
                        <th style="width:160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kategori as $item)
                        <tr>
                            <td>{{ $kategori->firstItem() + $loop->index }}</td>
                            <td>{{ $item->nama_kategori }}</td>
                            <td><span class="badge badge-blue">{{ $item->barang_count }} barang</span></td>
                            <td>
                                <div class="btn-group">
                                    @if (auth()->user()->role !== 'admin')
                                        <a href="{{ route('kategori.edit', $item) }}" class="btn btn-secondary btn-sm">
                                            Edit
                                        </a>
                                    @endif
                                    
                                    <form action="{{ route('kategori.destroy', $item) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        @if (auth()->user()->role !== 'admin')
                                            <form action="{{ route('kategori.destroy', $item) }}"
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
                            <td colspan="4">
                                <div class="empty-state">
                                    <span class="emoji">&#127991;</span>
                                    Belum ada data kategori.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($kategori->hasPages())
            <div class="pagination-wrap">{{ $kategori->links() }}</div>
        @endif
    </div>
@endsection
