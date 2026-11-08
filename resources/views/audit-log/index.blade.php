@extends('layouts.app')

@section('title', 'Audit Log')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Log Aktivitas Pengguna</h2>
        </div>

        <div class="card-body" style="padding-bottom:0;">
            <form method="GET" class="filter-bar">
                <div class="form-group" style="flex:1;">
                    <label>Cari Aktivitas</label>
                    <input type="text" name="search" class="form-control" placeholder="Kata kunci aktivitas..." value="{{ request('search') }}">
                </div>
                <div class="form-group">
                    <label>Dari</label>
                    <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
                </div>
                <div class="form-group">
                    <label>Sampai</label>
                    <input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
                </div>
                <div class="form-group"><button type="submit" class="btn btn-primary">Filter</button></div>
                @if (request()->anyFilled(['search', 'dari', 'sampai']))
                    <div class="form-group"><a href="{{ route('audit-log.index') }}" class="btn btn-secondary">Reset</a></div>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table class="data">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Waktu</th>
                        <th>Pengguna</th>
                        <th>Aktivitas</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($auditLog as $log)
                        <tr>
                            <td>{{ $auditLog->firstItem() + $loop->index }}</td>
                            <td>{{ $log->waktu ? $log->waktu->format('d/m/Y H:i:s') : '-' }}</td>
                            <td>{{ $log->pengguna->nama ?? 'Sistem' }}</td>
                            <td class="wrap">{{ $log->aktivitas }}</td>
                            <td>{{ $log->ip_address ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <span class="emoji">&#128220;</span>
                                    Belum ada log aktivitas.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($auditLog->hasPages())
            <div class="pagination-wrap">{{ $auditLog->links() }}</div>
        @endif
    </div>
@endsection
