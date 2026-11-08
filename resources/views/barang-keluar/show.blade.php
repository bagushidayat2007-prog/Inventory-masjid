@extends('layouts.app')

@section('title', 'Detail Barang Keluar')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Detail Transaksi Barang Keluar</h2>
            <div class="btn-group">
                <a href="{{ route('barang-keluar.edit', $barangKeluar) }}" class="btn btn-secondary btn-sm">Edit</a>
                <a href="{{ route('barang-keluar.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
            </div>
        </div>
        <div class="card-body">
            <dl class="detail-list">
                <dt>No. Transaksi</dt>
                <dd>#{{ $barangKeluar->id_keluar }}</dd>
                <dt>Tanggal</dt>
                <dd>{{ $barangKeluar->tanggal ? $barangKeluar->tanggal->format('d/m/Y') : '-' }}</dd>
                <dt>Pengguna</dt>
                <dd>{{ $barangKeluar->pengguna->nama ?? '-' }}</dd>
                <dt>Tujuan</dt>
                <dd>{{ $barangKeluar->tujuan ?? '-' }}</dd>
                <dt>Keterangan</dt>
                <dd>{{ $barangKeluar->keterangan ?? '-' }}</dd>
            </dl>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h2>Daftar Barang</h2></div>
        <div class="table-responsive">
            <table class="data">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Barang</th>
                        <th>Kategori</th>
                        <th class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barangKeluar->detail as $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                            <td>{{ $detail->barang->kategori->nama_kategori ?? '-' }}</td>
                            <td class="text-right text-red">-{{ $detail->jumlah }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-right">Total</th>
                        <th class="text-right">{{ $barangKeluar->detail->sum('jumlah') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
