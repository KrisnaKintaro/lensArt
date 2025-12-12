@extends('layouts.master_frontend')

@section('title', 'Tampilan Opening')

@section('styles')
    <style>
        /* Gaya dari HTML Asli Anda */
        body {
            /* Menonaktifkan padding-bottom dari master_frontend */
            padding-bottom: 0 !important;
        }

        /* Kontainer Utama */
        .splash-container { /* Ganti nama class agar tidak konflik */
            text-align: center;
            flex-grow: 1; 
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            /* Karena ada navbar, kita bisa sesuaikan margin top */
            min-height: calc(100vh - 80px); /* Adjusting for navbar height */
        }

        /* Area Logo */
        .logo-area {
            margin-bottom: 20px;
        }

        /* Styling untuk Gambar Logo */
        .logo-area img {
            width: 100px; 
            height: auto;
            max-width: 100%;
        }
        
        /* Tagline/Teks Fotografi */
        .tagline {
            font-family: 'Mr De Haviland', cursive; 
            font-size: 48px;
            font-weight: 400;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
            letter-spacing: 2px;
            margin-top: 10px;
        }

        /* Footer (Copyright) */
        .footer-note {
            width: 100%;
            text-align: center;
            padding: 20px 0;
            font-size: 14px;
            letter-spacing: 0.5px;
            opacity: 0.8;
            background-color: transparent;
        }
    </style>
@endsection

@section('content')

    <div class="splash-container">
        <div class="logo-area">
            {{-- Mengarahkan ke Portofolio saat diklik --}}
            <a href="{{ route('portofolio.event') }}"> 
                <img src="{{ asset('assetslensart/logo/Logo Lensart Putih.png') }}" alt="Logo Lensart Photography">
            </a>
        </div>
        <p class="tagline" id="lensart-tagline">Lensart Photography</p>
    </div>

    <div class="footer-note">
        Copyright @Lensart_Photography
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const taglineElement = document.getElementById('lensart-tagline');
            
            // Memberikan efek fade-in halus pada tagline
            setTimeout(() => {
                taglineElement.style.opacity = 1;
            }, 500);
        });
    </script>
@endsection