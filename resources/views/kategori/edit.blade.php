@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
    <div class="card" style="max-width:600px;">
        <div class="card-header">
            <h2>Edit Kategori</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('kategori.update', $kategori) }}" method="POST" data-loading>
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="required" for="nama_kategori">Nama Kategori</label>
                    <input type="text" id="nama_kategori" name="nama_kategori"
                           class="form-control @error('nama_kategori') is-invalid @enderror"
                           value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
                    @error('nama_kategori')<span class="error-text">{{ $message }}</span>@enderror
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                    <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
