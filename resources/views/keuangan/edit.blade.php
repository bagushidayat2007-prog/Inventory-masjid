@extends('layouts.app')

@section('title', 'Edit Transaksi Keuangan')

@section('content')
    <div class="card" style="max-width:760px;">
        <div class="card-header">
            <h2>Edit Transaksi Keuangan</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('keuangan.update', $keuangan) }}" method="POST" data-loading>
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <div class="form-group">
                        <label class="required" for="tanggal">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal"
                               class="form-control @error('tanggal') is-invalid @enderror"
                               value="{{ old('tanggal', $keuangan->tanggal->format('Y-m-d')) }}" required>
                        @error('tanggal')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="required" for="jenis">Jenis</label>
                        <select id="jenis" name="jenis" class="form-control @error('jenis') is-invalid @enderror" required>
                            <option value="pemasukan" @selected(old('jenis', $keuangan->jenis) === 'pemasukan')>Pemasukan</option>
                            <option value="pengeluaran" @selected(old('jenis', $keuangan->jenis) === 'pengeluaran')>Pengeluaran</option>
                        </select>
                        @error('jenis')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="required" for="jumlah">Jumlah (Rp)</label>
                        <input type="number" id="jumlah" name="jumlah" min="1" step="0.01"
                               class="form-control @error('jumlah') is-invalid @enderror"
                               value="{{ old('jumlah', (int) $keuangan->jumlah) }}" required>
                        @error('jumlah')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="sumber">Sumber</label>
                        <input type="text" id="sumber" name="sumber"
                               class="form-control @error('sumber') is-invalid @enderror"
                               value="{{ old('sumber', $keuangan->sumber) }}">
                        @error('sumber')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="tujuan">Tujuan</label>
                        <input type="text" id="tujuan" name="tujuan"
                               class="form-control @error('tujuan') is-invalid @enderror"
                               value="{{ old('tujuan', $keuangan->tujuan) }}">
                        @error('tujuan')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                              class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $keuangan->keterangan) }}</textarea>
                    @error('keterangan')<span class="error-text">{{ $message }}</span>@enderror
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                    <a href="{{ route('keuangan.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
