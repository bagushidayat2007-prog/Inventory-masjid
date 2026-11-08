@extends('layouts.app')

@section('title', 'Barang Keluar')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Transaksi Barang Keluar</h2>
            @if (auth()->user()->role !== 'admin')
                <a href="{{ route('barang-keluar.create') }}" class="btn btn-primary">
                    + Tambah Barang Keluar
                </a>
            @endif
        </div>

        <div class="card-body" style="padding-bottom:0;">
            <form method="GET" class="filter-bar">
                <div class="form-group">
                    <label>Dari Tanggal</label>
                    <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
                </div>
                <div class="form-group">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
                @if (request()->anyFilled(['dari', 'sampai']))
                    <div class="form-group">
                        <a href="{{ route('barang-keluar.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table class="data">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Tanggal</th>
                        <th>Pengguna</th>
                        <th>Tujuan</th>
                        <th>Jumlah Item</th>
                        <th style="width:220px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($barangKeluar as $item)
                        <tr>
                            <td>{{ $barangKeluar->firstItem() + $loop->index }}</td>
                            <td>{{ $item->tanggal ? $item->tanggal->format('d/m/Y') : '-' }}</td>
                            <td>{{ $item->pengguna->nama ?? '-' }}</td>
                            <td class="wrap">{{ $item->tujuan ?? '-' }}</td>
                            <td><span class="badge badge-amber">{{ $item->detail_count }} item</span></td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('barang-keluar.show', $item) }}" class="btn btn-secondary btn-sm">Detail</a>
                                    
                                    @if (auth()->user()->role !== 'admin')
                                        <a href="{{ route('barang-keluar.edit', $item) }}" class="btn btn-secondary btn-sm">
                                            Edit
                                        </a>
                                    @endif
                                    <form action="{{ route('barang-keluar.destroy', $item) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        @if (auth()->user()->role !== 'admin')

                                            <form action="{{ route('barang-keluar.destroy', $item) }}"
                                                method="POST"
                                                style="display:inline;"
                                                onsubmit="return confirm('Yakin ingin menghapus transaksi barang keluar ini?')">

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
                                    <span class="emoji">&#128228;</span>
                                    Belum ada transaksi barang keluar.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($barangKeluar->hasPages())
            <div class="pagination-wrap">{{ $barangKeluar->links() }}</div>
        @endif
    </div>
@endsection
