@extends('layouts.app')

@section('title', 'Transaksi Keuangan')

@section('content')
    <div class="stats-grid">
        <div class="stat">
            <div class="stat-icon green">&#128176;</div>
            <div>
                <div class="stat-value">{{ rupiah($totalPemasukan) }}</div>
                <div class="stat-label">Total Pemasukan</div>
            </div>
        </div>
        <div class="stat">
            <div class="stat-icon red">&#128184;</div>
            <div>
                <div class="stat-value">{{ rupiah($totalPengeluaran) }}</div>
                <div class="stat-label">Total Pengeluaran</div>
            </div>
        </div>
        <div class="stat">
            <div class="stat-icon blue">&#128179;</div>
            <div>
                <div class="stat-value">{{ rupiah($saldo) }}</div>
                <div class="stat-label">Saldo</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Data Keuangan</h2>
            <div class="btn-group">
                @if (auth()->user()->role !== 'admin')
                    <a href="{{ route('keuangan.create', ['jenis' => 'pemasukan']) }}" class="btn btn-primary">+ Pemasukan</a>
                    <a href="{{ route('keuangan.create', ['jenis' => 'pengeluaran']) }}" class="btn btn-danger">+ Pengeluaran</a>
                @endif
            </div>
        </div>

        <div class="card-body" style="padding-bottom:0;">
            <form method="GET" class="filter-bar">
                <div class="form-group" style="flex:1;">
                    <label>Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="Sumber, tujuan, keterangan..." value="{{ request('search') }}">
                </div>
                <div class="form-group">
                    <label>Jenis</label>
                    <select name="jenis" class="form-control">
                        <option value="">Semua</option>
                        <option value="pemasukan" @selected(request('jenis') === 'pemasukan')>Pemasukan</option>
                        <option value="pengeluaran" @selected(request('jenis') === 'pengeluaran')>Pengeluaran</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Dari</label>
                    <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
                </div>
                <div class="form-group">
                    <label>Sampai</label>
                    <input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
                </div>
                <div class="form-group"><button type="submit" class="btn btn-primary">Filter</button></div>
                @if (request()->anyFilled(['search', 'jenis', 'dari', 'sampai']))
                    <div class="form-group"><a href="{{ route('keuangan.index') }}" class="btn btn-secondary">Reset</a></div>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table class="data">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th class="text-right">Jumlah</th>
                        <th>Sumber</th>
                        <th>Tujuan</th>
                        <th>Keterangan</th>
                        <th>Petugas</th>
                        <th style="width:160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($keuangan as $item)
                        <tr>
                            <td>{{ $keuangan->firstItem() + $loop->index }}</td>
                            <td>{{ $item->tanggal ? $item->tanggal->format('d/m/Y') : '-' }}</td>
                            <td>
                                @if ($item->jenis === 'pemasukan')
                                    <span class="badge badge-green">Pemasukan</span>
                                @else
                                    <span class="badge badge-red">Pengeluaran</span>
                                @endif
                            </td>
                            <td class="text-right">{{ rupiah($item->jumlah) }}</td>
                            <td>{{ $item->sumber ?? '-' }}</td>
                            <td>{{ $item->tujuan ?? '-' }}</td>
                            <td class="wrap">{{ $item->keterangan ?? '-' }}</td>
                            <td>{{ $item->pengguna->nama ?? '-' }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('keuangan.edit', $item) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    <form action="{{ route('keuangan.destroy', $item) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm"
                                                data-confirm="Hapus transaksi keuangan ini?">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <span class="emoji">&#128176;</span>
                                    Belum ada data keuangan.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($keuangan->hasPages())
            <div class="pagination-wrap">{{ $keuangan->links() }}</div>
        @endif
    </div>
@endsection
