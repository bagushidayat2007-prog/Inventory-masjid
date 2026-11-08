@extends('layouts.app')

@section('title', ucfirst($jenis))

@section('content')
    <div class="card" style="max-width:760px;">
        <div class="card-header">
            <h2>Tambah {{ $jenis === 'pemasukan' ? 'Pemasukan' : 'Pengeluaran' }}</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('keuangan.store') }}" method="POST" data-loading>
                @csrf
                <input type="hidden" name="jenis" value="{{ $jenis }}">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="required" for="tanggal">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal"
                               class="form-control @error('tanggal') is-invalid @enderror"
                               value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        @error('tanggal')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="required" for="jumlah">Jumlah (Rp)</label>
                        <input type="number" id="jumlah" name="jumlah" min="1" step="0.01"
                               class="form-control @error('jumlah') is-invalid @enderror"
                               value="{{ old('jumlah') }}" placeholder="Contoh: 1000000" required>
                        @error('jumlah')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    @if ($jenis === 'pemasukan')
                        <div class="form-group">
                            <label for="sumber">Sumber</label>
                            <input type="text" id="sumber" name="sumber"
                                   class="form-control @error('sumber') is-invalid @enderror"
                                   value="{{ old('sumber') }}" placeholder="Contoh: Kotak amal Jumat">
                            @error('sumber')<span class="error-text">{{ $message }}</span>@enderror
                        </div>
                    @else
                        <div class="form-group">
                            <label for="tujuan">Tujuan</label>
                            <input type="text" id="tujuan" name="tujuan"
                                   class="form-control @error('tujuan') is-invalid @enderror"
                                   value="{{ old('tujuan') }}" placeholder="Contoh: Perbaikan sound system">
                            @error('tujuan')<span class="error-text">{{ $message }}</span>@enderror
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                              class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan') }}</textarea>
                    @error('keterangan')<span class="error-text">{{ $message }}</span>@enderror
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('keuangan.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
