@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Daftar Pengguna</h2>
            <div style="display:flex; gap:8px; align-items:flex-end;">
                <form method="GET" class="filter-bar">
                    <div class="form-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama/username..." value="{{ request('search') }}">
                    </div>
                    <div class="form-group">
                        <select name="role" class="form-control">
                            <option value="">Semua Role</option>
                            <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                            <option value="bendahara" @selected(request('role') === 'bendahara')>Bendahara</option>
                            <option value="petugas_inventory" @selected(request('role') === 'petugas_inventory')>Petugas Inventory</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-secondary">Cari</button>
                    @if (request()->anyFilled(['search', 'role']))
                        <a href="{{ route('pengguna.index') }}" class="btn btn-secondary">Reset</a>
                    @endif
                </form>
                <a href="{{ route('pengguna.create') }}" class="btn btn-primary">+ Tambah Pengguna</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="data">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>No Telepon</th>
                        <th style="width:160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengguna as $item)
                        <tr>
                            <td>{{ $pengguna->firstItem() + $loop->index }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->username }}</td>
                            <td>
                                @php
                                    $roleBadge = ['admin' => 'badge-red', 'bendahara' => 'badge-blue', 'petugas_inventory' => 'badge-green'][$item->role] ?? 'badge-gray';
                                @endphp
                                <span class="badge {{ $roleBadge }}">{{ $item->role_label }}</span>
                            </td>
                            <td>{{ $item->no_telp ?? '-' }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('pengguna.edit', $item) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    <form action="{{ route('pengguna.destroy', $item) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm"
                                                data-confirm="Hapus pengguna {{ $item->nama }}?">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <span class="emoji">&#128101;</span>
                                    Belum ada data pengguna.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pengguna->hasPages())
            <div class="pagination-wrap">{{ $pengguna->links() }}</div>
        @endif
    </div>
@endsection
