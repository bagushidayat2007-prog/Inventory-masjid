<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Profile') - Sistem Inventory Masjid</title>

    {{-- Font Awesome --}}
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    {{-- CSS utama --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('styles')
</head>

@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="card" style="max-width:700px;">
        <div class="card-header">
            <h2>Edit Profil</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST" data-loading>
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
                        <label for="no_telp">No Telepon</label>
                        <input type="text" id="no_telp" name="no_telp"
                               class="form-control @error('no_telp') is-invalid @enderror"
                               value="{{ old('no_telp', $pengguna->no_telp) }}">
                        @error('no_telp')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <input type="text" class="form-control" value="{{ $pengguna->role_label }}" disabled>
                    </div>
                </div>

                <hr style="border:none; border-top:1px solid var(--gray-100); margin:20px 0;">

                <h3 style="font-size:14px; margin:0 0 12px;">Ganti Password</h3>

                <div class="form-grid">

                    {{-- Password Lama --}}
                    <div class="form-group">
                        <label for="password_lama">Password Lama</label>

                        <div class="password-wrapper">
                            <input type="password" id="password_lama" name="password_lama"
                                class="form-control @error('password_lama') is-invalid @enderror">

                            <button type="button" class="toggle-password"
                                onclick="togglePassword('password_lama', 'eye_lama')">
                                <i class="fa-solid fa-eye" id="eye_lama"></i>
                            </button>
                        </div>

                        @error('password_lama')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Password Baru --}}
                    <div class="form-group">
                        <label for="password">Password Baru</label>

                        <div class="password-wrapper">
                            <input type="password" id="password" name="password"
                                class="form-control @error('password') is-invalid @enderror">

                            <button type="button" class="toggle-password"
                                onclick="togglePassword('password', 'eye_baru')">
                                <i class="fa-solid fa-eye" id="eye_baru"></i>
                            </button>
                            
                        </div>
                        <small style="color: #777; font-size: 12px;">
                            Password minimal 8 karakter (huruf besar, huruf kecil, angka, dan simbol).
                        </small>
                        
                        @error('password')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password Baru</label>

                        <div class="password-wrapper">
                            <input type="password" id="password_confirmation"
                                name="password_confirmation" class="form-control">

                            <button type="button" class="toggle-password"
                                onclick="togglePassword('password_confirmation', 'eye_konfirmasi')">
                                <i class="fa-solid fa-eye" id="eye_konfirmasi"></i>
                            </button>
                        </div>
                    </div>

                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
<script>
    function togglePassword(inputId, iconId) {
        const password = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (password.type === "password") {
            password.type = "text";

            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            password.type = "password";

            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
</script>
@endsection
