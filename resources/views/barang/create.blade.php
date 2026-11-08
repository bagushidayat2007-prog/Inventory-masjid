@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Tambah Barang</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('barang.store') }}" method="POST" data-loading>
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label class="required" for="nama_barang">Nama Barang</label>
                        <input type="text" id="nama_barang" name="nama_barang"
                               class="form-control @error('nama_barang') is-invalid @enderror"
                               value="{{ old('nama_barang') }}" required>
                        @error('nama_barang')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="required" for="id_kategori">Kategori</label>
                        <select id="id_kategori" name="id_kategori" class="form-control @error('id_kategori') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategoris as $k)
                                <option value="{{ $k->id_kategori }}" @selected(old('id_kategori') == $k->id_kategori)>{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                        @error('id_kategori')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="id_supplier">Supplier</label>
                        <select id="id_supplier" name="id_supplier" class="form-control @error('id_supplier') is-invalid @enderror">
                            <option value="">-- Pilih Supplier (opsional) --</option>
                            @foreach ($suppliers as $s)
                                <option value="{{ $s->id_supplier }}" @selected(old('id_supplier') == $s->id_supplier)>{{ $s->nama_supplier }}</option>
                            @endforeach
                        </select>
                        @error('id_supplier')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="required" for="satuan">Satuan</label>
                        <input type="text" id="satuan" name="satuan"
                               class="form-control @error('satuan') is-invalid @enderror"
                               value="{{ old('satuan') }}" placeholder="Contoh: buah, lembar, dus" required>
                        @error('satuan')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="required" for="stok">Stok Awal</label>
                        <input type="number" id="stok" name="stok" min="0"
                               class="form-control @error('stok') is-invalid @enderror"
                               value="{{ old('stok', 0) }}" required>
                        @error('stok')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="required" for="kondisi">Kondisi</label>
                        <select id="kondisi" name="kondisi" class="form-control @error('kondisi') is-invalid @enderror" required>
                            <option value="baik" @selected(old('kondisi') === 'baik')>Baik</option>
                            <option value="rusak_ringan" @selected(old('kondisi') === 'rusak_ringan')>Rusak Ringan</option>
                            <option value="rusak_berat" @selected(old('kondisi') === 'rusak_berat')>Rusak Berat</option>
                        </select>
                        @error('kondisi')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
