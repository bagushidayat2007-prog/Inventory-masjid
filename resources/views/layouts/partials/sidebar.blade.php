```blade
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="logo">
            <i class="fa-solid fa-mosque"></i>
        </div>
        <div>
            <div class="title">Sistem Inventory</div>
            <div class="subtitle">&amp; Keuangan Masjid</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="ico"><i class="fa-solid fa-gauge-high"></i></span>
            Dashboard
        </a>

        {{-- Inventory --}}
        @if (has_role('admin', 'petugas_inventory'))
            <div class="section-title">Inventory</div>

            <a href="{{ route('barang.index') }}"
               class="{{ request()->routeIs('barang.*') ? 'active' : '' }}">
                <span class="ico"><i class="fa-solid fa-box"></i></span>
                Data Barang
            </a>

            <a href="{{ route('kategori.index') }}"
               class="{{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                <span class="ico"><i class="fa-solid fa-layer-group"></i></span>
                Kategori
            </a>

            <a href="{{ route('supplier.index') }}"
               class="{{ request()->routeIs('supplier.*') ? 'active' : '' }}">
                <span class="ico"><i class="fa-solid fa-truck"></i></span>
                Supplier
            </a>

            <a href="{{ route('barang-masuk.index') }}"
               class="{{ request()->routeIs('barang-masuk.*') ? 'active' : '' }}">
                <span class="ico"><i class="fa-solid fa-boxes-stacked"></i></span>
                Barang Masuk
            </a>

            <a href="{{ route('barang-keluar.index') }}"
               class="{{ request()->routeIs('barang-keluar.*') ? 'active' : '' }}">
                <span class="ico"><i class="fa-solid fa-dolly"></i></span>
                Barang Keluar
            </a>
        @endif

        {{-- Keuangan --}}
        @if (has_role('admin', 'bendahara'))
            <div class="section-title">Keuangan</div>

            <a href="{{ route('keuangan.index') }}"
               class="{{ request()->routeIs('keuangan.*') ? 'active' : '' }}">
                <span class="ico"><i class="fa-solid fa-wallet"></i></span>
                Transaksi Keuangan
            </a>

            <a href="{{ route('laporan.keuangan') }}"
               class="{{ request()->routeIs('laporan.keuangan') ? 'active' : '' }}">
                <span class="ico"><i class="fa-solid fa-file-invoice-dollar"></i></span>
                Laporan Keuangan
            </a>
        @endif

        {{-- Laporan --}}
        @if (has_role('admin', 'petugas_inventory'))
            <div class="section-title">Laporan</div>

            <a href="{{ route('laporan.inventory') }}"
               class="{{ request()->routeIs('laporan.inventory') ? 'active' : '' }}">
                <span class="ico"><i class="fa-solid fa-chart-column"></i></span>
                Laporan Inventory
            </a>
        @endif

        {{-- Administrasi --}}
        @if (has_role('admin'))
            <div class="section-title">Administrasi</div>

            <a href="{{ route('pengguna.index') }}"
               class="{{ request()->routeIs('pengguna.*') ? 'active' : '' }}">
                <span class="ico"><i class="fa-solid fa-users"></i></span>
                Pengguna
            </a>

            <a href="{{ route('audit-log.index') }}"
               class="{{ request()->routeIs('audit-log.*') ? 'active' : '' }}">
                <span class="ico"><i class="fa-solid fa-clipboard-list"></i></span>
                Audit Log
            </a>

            <a href="{{ route('approval.index') }}"
               class="{{ request()->routeIs('approval.*') ? 'active' : '' }}">
                <span class="ico"><i class="fa-solid fa-square-check"></i></span>
                Persetujuan Pengajuan
            </a>
        @endif

        {{-- Akun --}}
        <div class="section-title">Akun</div>

        <a href="{{ route('profile.edit') }}"
           class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <span class="ico"><i class="fa-solid fa-user-circle"></i></span>
            Profile
        </a>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <a href="#"
               data-confirm="Anda yakin ingin keluar dari sistem?"
               data-confirm-title="Konfirmasi Logout"
               data-confirm-ok="Ya, Logout"
               data-confirm-variant="btn-primary">
                <span class="ico"><i class="fa-solid fa-right-from-bracket"></i></span>
                Logout
            </a>
        </form>
    </nav>
</aside>
