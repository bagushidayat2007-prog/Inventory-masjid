@extends('layouts.app')

@section('title', 'Laporan Inventory')

@section('content')
    <div class="card no-print">
        <div class="card-header">
            <h2>Filter Laporan Inventory</h2>
            <button type="button" class="btn btn-secondary" onclick="window.print()">Cetak Laporan</button>
        </div>
        <div class="card-body">
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
                    <label>Kategori</label>
                    <select name="id_kategori" class="form-control">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategoris as $k)
                            <option value="{{ $k->id_kategori }}" @selected(request('id_kategori') == $k->id_kategori)>{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Barang</label>
                    <select name="id_barang" class="form-control">
                        <option value="">Semua Barang</option>
                        @foreach ($barangList as $b)
                            <option value="{{ $b->id_barang }}" @selected(request('id_barang') == $b->id_barang)>{{ $b->nama_barang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group"><button type="submit" class="btn btn-primary">Filter</button></div>
                @if (request()->anyFilled(['dari', 'sampai', 'id_kategori', 'id_barang']))
                    <div class="form-group"><a href="{{ route('laporan.inventory') }}" class="btn btn-secondary">Reset</a></div>
                @endif
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h2>Data Barang &amp; Stok</h2></div>
        <div class="table-responsive">
            <table class="data">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Supplier</th>
                        <th>Satuan</th>
                        <th class="text-right">Stok</th>
                        <th>Kondisi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($barang as $b)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $b->nama_barang }}</td>
                            <td>{{ $b->kategori->nama_kategori ?? '-' }}</td>
                            <td>{{ $b->supplier->nama_supplier ?? '-' }}</td>
                            <td>{{ $b->satuan }}</td>
                            <td class="text-right">{{ $b->stok }}</td>
                            <td>{{ $b->kondisi_label }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">Tidak ada data barang.</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-right">Total Stok</th>
                        <th class="text-right">{{ $barang->sum('stok') }}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="grid-2">
        <div class="card">
            <div class="card-header"><h2>Barang Masuk</h2></div>
            <div class="table-responsive">
                <table class="data">
                    <thead>
                        <tr><th>Tanggal</th><th>Pengguna</th><th>Jumlah Item</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($barangMasuk as $m)
                            <tr>
                                <td>{{ $m->tanggal ? $m->tanggal->format('d/m/Y') : '-' }}</td>
                                <td>{{ $m->pengguna->nama ?? '-' }}</td>
                                <td>{{ $m->detail()->sum('jumlah') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">Tidak ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h2>Barang Keluar</h2></div>
            <div class="table-responsive">
                <table class="data">
                    <thead>
                        <tr><th>Tanggal</th><th>Pengguna</th><th>Jumlah Item</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($barangKeluar as $k)
                            <tr>
                                <td>{{ $k->tanggal ? $k->tanggal->format('d/m/Y') : '-' }}</td>
                                <td>{{ $k->pengguna->nama ?? '-' }}</td>
                                <td>{{ $k->detail()->sum('jumlah') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">Tidak ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
