<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="logo">&#127964;</div>
        <div>
            <div class="title">Sistem Inventory</div>
            <div class="subtitle">&amp; Keuangan Masjid</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="ico">&#128202;</span> Dashboard
        </a>

        @if (has_role('admin', 'petugas_inventory'))
            <div class="section-title">Inventory</div>
            <a href="{{ route('barang.index') }}" class="{{ request()->routeIs('barang.*') ? 'active' : '' }}">
                <span class="ico">&#128230;</span> Data Barang
            </a>
            <a href="{{ route('kategori.index') }}" class="{{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                <span class="ico">&#127991;</span> Kategori
            </a>
            <a href="{{ route('supplier.index') }}" class="{{ request()->routeIs('supplier.*') ? 'active' : '' }}">
                <span class="ico">&#127981;</span> Supplier
            </a>
            <a href="{{ route('barang-masuk.index') }}" class="{{ request()->routeIs('barang-masuk.*') ? 'active' : '' }}">
                <span class="ico">&#128229;</span> Barang Masuk
            </a>
            <a href="{{ route('barang-keluar.index') }}" class="{{ request()->routeIs('barang-keluar.*') ? 'active' : '' }}">
                <span class="ico">&#128228;</span> Barang Keluar
            </a>
        @endif

        @if (has_role('admin', 'bendahara'))
            <div class="section-title">Keuangan</div>
            <a href="{{ route('keuangan.index') }}" class="{{ request()->routeIs('keuangan.*') ? 'active' : '' }}">
                <span class="ico">&#128176;</span> Transaksi Keuangan
            </a>
            <a href="{{ route('laporan.keuangan') }}" class="{{ request()->routeIs('laporan.keuangan') ? 'active' : '' }}">
                <span class="ico">&#128203;</span> Laporan Keuangan
            </a>
        @endif

        @if (has_role('admin', 'petugas_inventory'))
            <div class="section-title">Laporan</div>
            <a href="{{ route('laporan.inventory') }}" class="{{ request()->routeIs('laporan.inventory') ? 'active' : '' }}">
                <span class="ico">&#128200;</span> Laporan Inventory
            </a>
        @endif

        @if (has_role('admin'))
            <div class="section-title">Administrasi</div>
            <a href="{{ route('pengguna.index') }}" class="{{ request()->routeIs('pengguna.*') ? 'active' : '' }}">
                <span class="ico">&#128101;</span> Pengguna
            </a>
            <a href="{{ route('audit-log.index') }}" class="{{ request()->routeIs('audit-log.*') ? 'active' : '' }}">
                <span class="ico">&#128220;</span> Audit Log
            </a>
        @endif

        <div class="section-title">Akun</div>
        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <span class="ico">&#9881;</span> Profile
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <a href="#"
               data-confirm="Anda yakin ingin keluar dari sistem?"
               data-confirm-title="Konfirmasi Logout"
               data-confirm-ok="Ya, Logout"
               data-confirm-variant="btn-primary">
                <span class="ico">&#128682;</span> Logout
            </a>
        </form>
    </nav>
</aside>
