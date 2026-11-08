@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="stats-grid">
        <div class="stat">
            <div class="stat-icon green">&#128230;</div>
            <div>
                <div class="stat-value">{{ number_format($totalBarang, 0, ',', '.') }}</div>
                <div class="stat-label">Total Barang</div>
            </div>
        </div>
        <div class="stat">
            <div class="stat-icon blue">&#128203;</div>
            <div>
                <div class="stat-value">{{ number_format($totalStok, 0, ',', '.') }}</div>
                <div class="stat-label">Total Stok</div>
            </div>
        </div>
        <div class="stat">
            <div class="stat-icon green">&#128229;</div>
            <div>
                <div class="stat-value">{{ number_format($totalMasuk, 0, ',', '.') }}</div>
                <div class="stat-label">Barang Masuk</div>
            </div>
        </div>
        <div class="stat">
            <div class="stat-icon amber">&#128228;</div>
            <div>
                <div class="stat-value">{{ number_format($totalKeluar, 0, ',', '.') }}</div>
                <div class="stat-label">Barang Keluar</div>
            </div>
        </div>
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

    <div class="grid-2">
        <div class="card">
            <div class="card-header">
                <h2>Grafik Pemasukan &amp; Pengeluaran</h2>
                <div>
                    <span class="badge badge-green">Pemasukan</span>
                    <span class="badge badge-red">Pengeluaran</span>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-box">
                    <canvas id="chartKeuangan"></canvas>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>Grafik Stok per Kategori</h2>
            </div>
            <div class="card-body">
                <div class="chart-box">
                    <canvas id="chartStok"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="grid-2">
        <div class="card">
            <div class="card-header">
                <h2>Barang Stok Rendah</h2>
                <a href="{{ route('barang.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="data">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th class="text-right">Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($barangStokRendah as $item)
                            <tr>
                                <td>{{ $item->nama_barang }}</td>
                                <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                                <td class="text-right">
                                    <span class="badge {{ $item->stok <= 5 ? 'badge-red' : 'badge-amber' }}">{{ $item->stok }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">Belum ada data barang.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>Aktivitas Terbaru</h2>
            </div>
            <div class="table-responsive">
                <table class="data">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Aktivitas</th>
                            <th>Pengguna</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($aktivitasTerbaru as $log)
                            <tr>
                                <td>{{ $log->waktu ? $log->waktu->format('d/m/Y H:i') : '-' }}</td>
                                <td class="wrap">{{ $log->aktivitas }}</td>
                                <td>{{ $log->pengguna->nama ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">Belum ada aktivitas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Transaksi Keuangan Terbaru</h2>
            <a href="{{ route('keuangan.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
        </div>
        <div class="table-responsive">
            <table class="data">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Sumber / Tujuan</th>
                        <th class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksiTerbaru as $trx)
                        <tr>
                            <td>{{ $trx->tanggal->format('d/m/Y') }}</td>
                            <td>
                                @if ($trx->jenis === 'pemasukan')
                                    <span class="badge badge-green">Pemasukan</span>
                                @else
                                    <span class="badge badge-red">Pengeluaran</span>
                                @endif
                            </td>
                            <td>{{ $trx->jenis === 'pemasukan' ? ($trx->sumber ?? '-') : ($trx->tujuan ?? '-') }}</td>
                            <td class="text-right">{{ rupiah($trx->jumlah) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script>
        App.drawBarChart(
            document.getElementById('chartKeuangan'),
            @json($bulanLabels),
            [
                { data: @json($dataPemasukan), color: '#27ae60' },
                { data: @json($dataPengeluaran), color: '#d64545' }
            ]
        );
        App.drawBarChart(
            document.getElementById('chartStok'),
            @json($stokLabels),
            [{ data: @json($stokData), color: '#2f80ed' }]
        );
    </script>
    @endpush
@endsection
