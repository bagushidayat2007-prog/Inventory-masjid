@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Daftar Barang</h2>
            @if (auth()->user()->role !== 'admin')
                <a href="{{ route('barang.create') }}" class="btn btn-primary">
                    + Tambah Barang
                </a>
            @endif
        </div>

        <div class="card-body" style="padding-bottom:0;">
            <form method="GET" class="filter-bar">
                <div class="form-group" style="flex:1;">
                    <label>Cari Barang</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama barang..." value="{{ request('search') }}">
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="id_kategori" class="form-control">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategoris as $k)
                            <option value="{{ $k->id_kategori }}" @selected(request('id_kategori') == $k->id_kategori)>{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Kondisi</label>
                    <select name="kondisi" class="form-control">
                        <option value="">Semua Kondisi</option>
                        <option value="baik" @selected(request('kondisi') === 'baik')>Baik</option>
                        <option value="rusak_ringan" @selected(request('kondisi') === 'rusak_ringan')>Rusak Ringan</option>
                        <option value="rusak_berat" @selected(request('kondisi') === 'rusak_berat')>Rusak Berat</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
                @if (request()->anyFilled(['search', 'id_kategori', 'kondisi']))
                    <div class="form-group">
                        <a href="{{ route('barang.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table class="data">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'nama_barang', 'direction' => request('sort') === 'nama_barang' && request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                                Nama Barang @if(request('sort') === 'nama_barang' || !request('sort')) <small>{{ request('direction') === 'desc' ? '▼' : '▲' }}</small> @endif
                            </a>
                        </th>
                        <th>Kategori</th>
                        <th>Supplier</th>
                        <th>Satuan</th>
                        <th class="text-right">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'stok', 'direction' => request('sort') === 'stok' && request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                                Stok @if(request('sort') === 'stok') <small>{{ request('direction') === 'desc' ? '▼' : '▲' }}</small> @endif
                            </a>
                        </th>
                        <th>Kondisi</th>
                        <th style="width:220px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($barang as $item)
                        <tr>
                            <td>{{ $barang->firstItem() + $loop->index }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                            <td>{{ $item->supplier->nama_supplier ?? '-' }}</td>
                            <td>{{ $item->satuan }}</td>
                            <td class="text-right">
                                <span class="badge {{ $item->stok <= 5 ? 'badge-red' : 'badge-green' }}">{{ $item->stok }}</span>
                            </td>
                            <td>
                                @php
                                    $kondisiBadge = ['baik' => 'badge-green', 'rusak_ringan' => 'badge-amber', 'rusak_berat' => 'badge-red'][$item->kondisi] ?? 'badge-gray';
                                @endphp
                                <span class="badge {{ $kondisiBadge }}">{{ $item->kondisi_label }}</span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('barang.show', $item) }}" class="btn btn-secondary btn-sm">Detail</a>
                                    
                                    <form action="{{ route('barang.destroy', $item) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        @if (auth()->user()->role !== 'admin')
                                            <a href="{{ route('barang.edit', $item) }}" class="btn btn-secondary btn-sm">Edit</a>
                                            <form action="{{ route('barang.destroy', $item) }}"
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
                            <td colspan="8">
                                <div class="empty-state">
                                    <span class="emoji">&#128230;</span>
                                    Belum ada data barang.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($barang->hasPages())
            <div class="pagination-wrap">{{ $barang->links() }}</div>
        @endif
    </div>
@endsection
