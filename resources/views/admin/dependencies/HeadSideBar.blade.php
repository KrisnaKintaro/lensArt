<!-- Preloader -->
<div class="preloader flex-column justify-content-center align-items-center" style="background-color: #343A40;">
    <img class="animation__wobble" src="{{ asset('assetslensart/logo/Logo Lensart Putih.png') }}" alt="logoLensArt"
        height="120" width="120">
</div>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('logout') }}" class="nav-link">
                <i class="fas fa-sign-out-alt"></i> LogOut
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search"
                            aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{ asset('assetslensart/logo/Logo Lensart Putih.png') }}" alt="Logo LensArt"
            class="brand-image elevation-3" style="opacity: .8;">
        <span class="brand-text font-weight-light">LensArt</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            {{-- <div class="image">
                <img src="" class="img-circle elevation-2" alt="User Image">
            </div> --}}
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->namaLengkap }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <li class="nav-item {{ Route::is('booking.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Route::is('booking.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p>
                            Kelola Booking
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ Route('booking.daftarPemesanan') }}"
                                class="nav-link {{ Route::is('booking.daftarPemesanan') ? 'active' : '' }}">
                                <i class="fas fa-clipboard-list nav-icon"></i>
                                <p>Daftar Pemesanan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ Route('booking.dataPembayaran') }}"
                                class="nav-link {{ Route::is('booking.dataPembayaran') ? 'active' : '' }}">
                                <i class="fas fa-file-invoice-dollar nav-icon"></i>
                                <p>Validasi Pembayaran</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('kalenderJadwal') }}"
                        class="nav-link {{ Route::is('kalenderJadwal') ? 'active' : '' }}">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>
                            Kalender Jadwal Kerja
                        </p>
                    </a>
                </li>
                <li
                    class="nav-item {{ Route::is('jenisLayanan.*') || Route::is('paketLayanan.*') ? 'menu-open' : '' }}">

                    {{-- Parent Menu --}}
                    <a href="#"
                        class="nav-link {{ Route::is('jenisLayanan.*') || Route::is('paketLayanan.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>
                            Layanan & Harga
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        {{-- Kelola Jenis Layanan --}}
                        <li class="nav-item">
                            <a href="{{ route('jenisLayanan.index') }}"
                                class="nav-link {{ Route::is('jenisLayanan.*') ? 'active' : '' }}">
                                <i class="fas fa-concierge-bell nav-icon"></i>
                                <p>Kelola Jenis Layanan</p>
                            </a>
                        </li>

                        {{-- Kelola Daftar Paket --}}
                        <li class="nav-item">
                            <a href="{{ route('paketLayanan.index') }}"
                                class="nav-link {{ Route::is('paketLayanan.*') ? 'active' : '' }}">
                                <i class="fas fa-box-open nav-icon"></i>
                                <p>Kelola Daftar Paket</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('portofolio.index') }}"
                        class="nav-link nav-link {{ Route::is('portofolio.index') || Route::is('portofolio.edit') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-images"></i>
                        <p>
                            Kelola Portofolio
                        </p>
                    </a>
                </li>

                <li class="nav-item {{ Route::is('laporan.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Route::is('laporan.*') ? '' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>
                            Kelola Laporan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('laporan.pendapatan') }}"
                                class="nav-link {{ Route::is('laporan.pendapatan') ? 'active' : '' }}">
                                <i class="fas fa-money-bill-wave nav-icon"></i>
                                <p>Laporan Pendapatan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('laporan.permintaan') }}"
                                class="nav-link {{ Route::is('laporan.permintaan') ? 'active' : '' }}">
                                <i class="fas fa-chart-bar nav-icon"></i>
                                <p>Laporan Permintaan</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('kelolaAkunCustomer') }}"
                        class="nav-link {{ Route::is('kelolaAkunCustomer') || Route::is('kelolaAkunCustomer.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>
                            Kelola Akun Customer
                        </p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
