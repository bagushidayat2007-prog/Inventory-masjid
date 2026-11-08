@extends('layouts.app')

@section('title', 'Tambah Supplier')

@section('content')
    <div class="card" style="max-width:700px;">
        <div class="card-header">
            <h2>Tambah Supplier</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('supplier.store') }}" method="POST" data-loading>
                @csrf
                <div class="form-group">
                    <label class="required" for="nama_supplier">Nama Supplier</label>
                    <input type="text" id="nama_supplier" name="nama_supplier"
                           class="form-control @error('nama_supplier') is-invalid @enderror"
                           value="{{ old('nama_supplier') }}" required>
                    @error('nama_supplier')<span class="error-text">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat') }}</textarea>
                    @error('alamat')<span class="error-text">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="no_telp">No Telepon</label>
                    <input type="text" id="no_telp" name="no_telp"
                           class="form-control @error('no_telp') is-invalid @enderror"
                           value="{{ old('no_telp') }}">
                    @error('no_telp')<span class="error-text">{{ $message }}</span>@enderror
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('supplier.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
