@extends('layouts.app')

@section('title', 'Barang Masuk')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Transaksi Barang Masuk</h2>
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
                        <a href="{{ route('barang-masuk.index') }}" class="btn btn-secondary">Reset</a>
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
                        <th>Jumlah Item</th>
                        <th>Keterangan</th>
                        <th style="width:220px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($barangMasuk as $item)
                        <tr>
                            <td>{{ $barangMasuk->firstItem() + $loop->index }}</td>
                            <td>{{ $item->tanggal ? $item->tanggal->format('d/m/Y') : '-' }}</td>
                            <td>{{ $item->pengguna->nama ?? '-' }}</td>
                            <td><span class="badge badge-green">{{ $item->detail_count }} item</span></td>
                            <td class="wrap">{{ $item->keterangan ?? '-' }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('barang-masuk.show', $item) }}" class="btn btn-secondary btn-sm">Detail</a>
                                    
                                    <form action="{{ route('barang-masuk.destroy', $item) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        @if (auth()->user()->role !== 'admin')
                                            <form action="{{ route('barang.destroy', $item) }}"
                                                method="POST"
                                                style="display: inline;"
                                                onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <a href="{{ route('barang-masuk.edit', $item) }}" class="btn btn-secondary btn-sm">Edit</a>
                                                
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
                                    <span class="emoji">&#128229;</span>
                                    Belum ada transaksi barang masuk.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($barangMasuk->hasPages())
            <div class="pagination-wrap">{{ $barangMasuk->links() }}</div>
        @endif
    </div>
@endsection
