@extends('layouts.app')

@section('title', 'Persetujuan Pengajuan')

@section('content')

<div class="card">
    <div class="card-header">
        <h2>Persetujuan Pengajuan Barang</h2>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered approval-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pengaju</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($pengajuanBarang as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->pengguna->nama ?? '-' }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->stok }}</td>
                            <td>{{ $item->satuan }}</td>
                            <td>{{ ucfirst($item->status) }}</td>
                            <td>
                                @if($item->status == 'menunggu')
                                    <form action="{{ route('approval.barang.setujui', $item->id_pengajuan_barang) }}"
                                          method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">
                                            Setujui
                                        </button>
                                    </form>

                                    <form action="{{ route('approval.barang.tolak', $item->id_pengajuan_barang) }}"
                                          method="POST" style="display:inline;"
                                          onsubmit="return confirm('Yakin ingin menolak pengajuan ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Tolak
                                        </button>
                                    </form>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                Belum ada pengajuan barang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $pengajuanBarang->links() }}
    </div>
</div>


<div class="card mt-4">
    <div class="card-header">
        <h2>Persetujuan Pengajuan Pengeluaran</h2>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered approval-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pengaju</th>
                        <th>Tanggal</th>
                        <th>Jumlah</th>
                        <th>Tujuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($pengajuanPengeluaran as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->pengguna->nama ?? '-' }}</td>
                            <td>{{ $item->tanggal->format('d-m-Y') }}</td>
                            <td>Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                            <td>{{ $item->tujuan ?? '-' }}</td>
                            <td>{{ ucfirst($item->status) }}</td>
                            <td>
                                @if($item->status == 'menunggu')
                                    <form action="{{ route('approval.pengeluaran.setujui', $item->id_pengajuan_pengeluaran) }}"
                                          method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">
                                            Setujui
                                        </button>
                                    </form>

                                    <form action="{{ route('approval.pengeluaran.tolak', $item->id_pengajuan_pengeluaran) }}"
                                          method="POST" style="display:inline;"
                                          onsubmit="return confirm('Yakin ingin menolak pengajuan ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Tolak
                                        </button>
                                    </form>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                Belum ada pengajuan pengeluaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $pengajuanPengeluaran->links() }}
    </div>
</div>
<style>
    .approval-table {
        width: 100%;
        min-width: 850px;
        border-collapse: collapse;
        table-layout: auto;
    }

    .approval-table th,
    .approval-table td {
        padding: 14px 18px !important;
        text-align: left;
        vertical-align: middle;
        white-space: nowrap;
    }

    .approval-table th {
        font-weight: 600;
    }

    .approval-table td form {
        display: inline-block;
        margin-right: 6px;
    }

    .approval-table .btn {
        padding: 8px 14px;
    }
</style>
@endsection