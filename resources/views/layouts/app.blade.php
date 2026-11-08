<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') &middot; {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
<div class="app">
    @include('layouts.partials.sidebar')

    <div class="main">
        @include('layouts.partials.navbar')

        <main class="content">
            @include('layouts.partials.alert')
            @yield('content')
        </main>

        @include('layouts.partials.footer')
    </div>
</div>

<div class="overlay" id="overlay"></div>

{{-- Modal konfirmasi (hapus / logout) --}}
<div class="modal-backdrop" id="confirmModal">
    <div class="modal">
        <h3 data-confirm-title>Konfirmasi</h3>
        <p data-confirm-message>Apakah Anda yakin?</p>
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary" data-confirm-cancel>Batal</button>
            <button type="button" class="btn btn-danger" data-confirm-ok>Ya</button>
        </div>
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
