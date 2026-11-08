@extends('layouts.app')

@section('title', 'Detail Barang')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Detail Barang</h2>
            <div class="btn-group">
                <a href="{{ route('barang.edit', $barang) }}" class="btn btn-secondary btn-sm">Edit</a>
                <a href="{{ route('barang.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
            </div>
        </div>
        <div class="card-body">
            <dl class="detail-list">
                <dt>Nama Barang</dt>
                <dd>{{ $barang->nama_barang }}</dd>
                <dt>Kategori</dt>
                <dd>{{ $barang->kategori->nama_kategori ?? '-' }}</dd>
                <dt>Supplier</dt>
                <dd>{{ $barang->supplier->nama_supplier ?? '-' }}</dd>
                <dt>Satuan</dt>
                <dd>{{ $barang->satuan }}</dd>
                <dt>Stok Saat Ini</dt>
                <dd><span class="badge {{ $barang->stok <= 5 ? 'badge-red' : 'badge-green' }}">{{ $barang->stok }}</span></dd>
                <dt>Kondisi</dt>
                <dd>
                    @php
                        $kondisiBadge = ['baik' => 'badge-green', 'rusak_ringan' => 'badge-amber', 'rusak_berat' => 'badge-red'][$barang->kondisi] ?? 'badge-gray';
                    @endphp
                    <span class="badge {{ $kondisiBadge }}">{{ $barang->kondisi_label }}</span>
                </dd>
            </dl>
        </div>
    </div>

    <div class="grid-2">
        <div class="card">
            <div class="card-header"><h2>Riwayat Barang Masuk</h2></div>
            <div class="table-responsive">
                <table class="data">
                    <thead>
                        <tr><th>Tanggal</th><th class="text-right">Jumlah</th><th>Pengguna</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayatMasuk as $r)
                            <tr>
                                <td>{{ $r->barangMasuk->tanggal ? $r->barangMasuk->tanggal->format('d/m/Y') : '-' }}</td>
                                <td class="text-right text-green">+{{ $r->jumlah }}</td>
                                <td>{{ $r->barangMasuk->pengguna->nama ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">Belum ada riwayat masuk.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h2>Riwayat Barang Keluar</h2></div>
            <div class="table-responsive">
                <table class="data">
                    <thead>
                        <tr><th>Tanggal</th><th class="text-right">Jumlah</th><th>Pengguna</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayatKeluar as $r)
                            <tr>
                                <td>{{ $r->barangKeluar->tanggal ? $r->barangKeluar->tanggal->format('d/m/Y') : '-' }}</td>
                                <td class="text-right text-red">-{{ $r->jumlah }}</td>
                                <td>{{ $r->barangKeluar->pengguna->nama ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">Belum ada riwayat keluar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
