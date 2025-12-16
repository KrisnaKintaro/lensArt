<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lensart Photography | @yield('title', 'Portofolio')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Mr+De+Haviland&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <style>
        /* ... Gaya CSS Anda tetap sama ... */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-image: linear-gradient(to bottom, #000000, #33312b);
            color: white;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            padding-bottom: 50px;
        }

        /* NAVBAR */
        .navbar {
            background-color: rgba(0, 0, 0, 0.2) !important;
            z-index: 1050;
        }

        .brand-area {
            display: flex;
            align-items: center;
        }

        .brand-area img {
            width: 40px;
            margin-right: 15px;
        }

        .brand-text {
            font-size: 24px;
            font-weight: bold;
        }

        /* BOOKING BUTTON */
        .btn-booking {
            background-color: white;
            color: black;
            border: 1px solid white;
            padding: 5px 15px;
            font-weight: bold;
            transition: 0.3s ease;
        }

        .btn-booking:hover {
            background-color: #f0f0f0;
            color: #333;
        }

        /* GALLERY GRID DEFAULT (bisa dioverride di halaman anak) */
        .content-container {
            width: 95%;
            max-width: 1200px;
            margin: 30px auto;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: 1fr 1.5fr 1fr;
            grid-template-rows: repeat(3, minmax(150px, auto));
            gap: 15px;
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            background-color: #333;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .gallery-item img:hover {
            transform: scale(1.05);
        }

        /* GRID POSITIONS */
        .item-1 {
            grid-row: 1 / span 2;
        }

        .item-2 {
            grid-column: 2 / span 2;
        }

        .item-4 {
            grid-row: 2 / 3;
            grid-column: 2 / 3;
        }

        .item-5 {
            grid-row: 2 / 3;
            grid-column: 3 / 4;
        }

        .item-kiri-bawah-1 {
            grid-row: 3 / 4;
            grid-column: 1 / 2;
        }

        .item-tengah-bawah-2 {
            grid-row: 3 / 4;
            grid-column: 2 / 3;
        }

        .item-6 {
            grid-row: 3 / 4;
            grid-column: 3 / 4;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .item-6 .signature {
            font-family: 'Mr De Haviland', cursive;
            font-size: 40px;
            color: black;
            background: white;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* NAVIGASI GALLERY */
        .gallery-nav-button {
            position: fixed;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 15px;
            font-size: 30px;
            cursor: pointer;
            z-index: 100;
            transition: background 0.3s;
        }

        .gallery-nav-button:hover {
            background: rgba(0, 0, 0, 0.8);
        }

        .nav-left {
            left: 0;
            border-radius: 0 5px 5px 0;
        }

        .nav-right {
            right: 0;
            border-radius: 5px 0 0 5px;
        }
    </style>

    @yield('styles')
    @yield('header')
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark p-3">
        <div class="container-fluid">

            <a class="navbar-brand brand-area" href="{{ route('tampilan_opening') }}">
                <img src="{{ asset('assetslensart/logo/Logo Lensart Putih.png') }}" alt="Lensart Logo">
                <div class="brand-text"></div>
            </a>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center"> {{-- ms-auto untuk kanan, align-items-lg-center biar tombol rata tengah --}}

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('layanan.index') }}">Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('portofolio.event') }}">Portofolio</a>
                    </li>

                    {{-- MENU KHUSUS CUSTOMER (DIBENAHI) --}}
                    @auth
                    @if(Auth::user()->role == 'customer')
                    <li class="nav-item mx-lg-2">
                        <a class="nav-link text-info fw-bold" href="{{ route('customer.riwayat.booking') }}">
                            <i class="fas fa-camera me-1"></i> Riwayat Booking
                        </a>
                    </li>
                    <li class="nav-item mx-lg-2">
                        <a class="nav-link text-success fw-bold" href="{{ route('customer.riwayat.pembayaran') }}">
                            <i class="fas fa-check-circle me-1"></i> Verifikasi Pembayaran
                        </a>
                    </li>
                    @endif
                    @endauth

                    {{-- TOMBOL BOOKING (Ditaruh di tengah/kiri navbar untuk visibilitas) --}}
                    <li class="nav-item mx-2 order-lg-last"> {{-- order-lg-last agar tombol Booking muncul paling kanan di desktop --}}
                        <a class="btn btn-warning booking-button rounded-pill" href="{{ route('layanan.index') }}">
                            Booking Sekarang
                        </a>
                    </li>

                    {{-- AUTH STATUS (Login/Register/Akun) --}}
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                    @endguest

                    @auth
                    <li class="nav-item dropdown ms-lg-2">
                        <a class="nav-link dropdown-toggle text-white fw-bold" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->namaLengkap ?? 'Akun' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="navbarDropdown">
                            {{-- Jika Admin, Arahkan ke dashboard admin --}}
                            @if(Auth::user()->role == 'admin')
                                <li><a class="dropdown-item text-warning" href="{{ route('kalenderJadwal') }}">Dashboard Admin</a></li>
                                <li><hr class="dropdown-divider"></li>
                            @else
                                <li><a class="dropdown-item" href="{{ route('customer.profil') }}">Profil Saya</a></li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}">
                                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    <main>
        @yield('content')
    </main>

    @yield('gallery_navigation')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')

</body>

</html>
