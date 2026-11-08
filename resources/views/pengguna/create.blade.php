@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
    <div class="card" style="max-width:700px;">
        <div class="card-header">
            <h2>Tambah Pengguna</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('pengguna.store') }}" method="POST" data-loading>
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label class="required" for="nama">Nama</label>
                        <input type="text" id="nama" name="nama"
                               class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama') }}" required>
                        @error('nama')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="required" for="username">Username</label>
                        <input type="text" id="username" name="username"
                               class="form-control @error('username') is-invalid @enderror"
                               value="{{ old('username') }}" required>
                        @error('username')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="required" for="password">Password</label>
                        <input type="password" id="password" name="password"
                               class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="required" for="role">Role</label>
                        <select id="role" name="role" class="form-control @error('role') is-invalid @enderror" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                            <option value="bendahara" @selected(old('role') === 'bendahara')>Bendahara</option>
                            <option value="petugas_inventory" @selected(old('role') === 'petugas_inventory')>Petugas Inventory</option>
                        </select>
                        @error('role')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="no_telp">No Telepon</label>
                        <input type="text" id="no_telp" name="no_telp"
                               class="form-control @error('no_telp') is-invalid @enderror"
                               value="{{ old('no_telp') }}">
                        @error('no_telp')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('pengguna.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
