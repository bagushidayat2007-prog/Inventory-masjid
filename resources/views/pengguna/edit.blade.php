@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
    <div class="card" style="max-width:700px;">
        <div class="card-header">
            <h2>Edit Pengguna</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('pengguna.update', $pengguna) }}" method="POST" data-loading>
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <div class="form-group">
                        <label class="required" for="nama">Nama</label>
                        <input type="text" id="nama" name="nama"
                               class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama', $pengguna->nama) }}" required>
                        @error('nama')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="required" for="username">Username</label>
                        <input type="text" id="username" name="username"
                               class="form-control @error('username') is-invalid @enderror"
                               value="{{ old('username', $pengguna->username) }}" required>
                        @error('username')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <input type="password" id="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Kosongkan jika tidak diubah">
                        @error('password')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="required" for="role">Role</label>
                        <select id="role" name="role" class="form-control @error('role') is-invalid @enderror" required>
                            <option value="admin" @selected(old('role', $pengguna->role) === 'admin')>Admin</option>
                            <option value="bendahara" @selected(old('role', $pengguna->role) === 'bendahara')>Bendahara</option>
                            <option value="petugas_inventory" @selected(old('role', $pengguna->role) === 'petugas_inventory')>Petugas Inventory</option>
                        </select>
                        @error('role')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="no_telp">No Telepon</label>
                        <input type="text" id="no_telp" name="no_telp"
                               class="form-control @error('no_telp') is-invalid @enderror"
                               value="{{ old('no_telp', $pengguna->no_telp) }}">
                        @error('no_telp')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                    <a href="{{ route('pengguna.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
