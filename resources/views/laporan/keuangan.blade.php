@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
    <div class="card no-print">
        <div class="card-header">
            <h2>Filter Laporan Keuangan</h2>
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
                    <label>Jenis</label>
                    <select name="jenis" class="form-control">
                        <option value="">Semua</option>
                        <option value="pemasukan" @selected(request('jenis') === 'pemasukan')>Pemasukan</option>
                        <option value="pengeluaran" @selected(request('jenis') === 'pengeluaran')>Pengeluaran</option>
                    </select>
                </div>
                <div class="form-group"><button type="submit" class="btn btn-primary">Filter</button></div>
                @if (request()->anyFilled(['dari', 'sampai', 'jenis']))
                    <div class="form-group"><a href="{{ route('laporan.keuangan') }}" class="btn btn-secondary">Reset</a></div>
                @endif
            </form>
        </div>
    </div>

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
            <h2>Rincian Transaksi Keuangan</h2>
            <span class="text-muted" style="font-size:12px;">Dicetak: {{ now()->format('d/m/Y H:i') }}</span>
        </div>
        <div class="table-responsive">
            <table class="data">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th class="text-right">Jumlah</th>
                        <th>Sumber</th>
                        <th>Tujuan</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($keuangan as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->tanggal ? $item->tanggal->format('d/m/Y') : '-' }}</td>
                            <td>{{ ucfirst($item->jenis) }}</td>
                            <td class="text-right">{{ rupiah($item->jumlah) }}</td>
                            <td>{{ $item->sumber ?? '-' }}</td>
                            <td>{{ $item->tujuan ?? '-' }}</td>
                            <td class="wrap">{{ $item->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">Tidak ada data keuangan.</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-right">Total Pemasukan</th>
                        <th class="text-right text-green">{{ rupiah($totalPemasukan) }}</th>
                        <th colspan="3"></th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-right">Total Pengeluaran</th>
                        <th class="text-right text-red">{{ rupiah($totalPengeluaran) }}</th>
                        <th colspan="3"></th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-right">Saldo</th>
                        <th class="text-right">{{ rupiah($saldo) }}</th>
                        <th colspan="3"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
