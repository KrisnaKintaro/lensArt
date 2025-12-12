@extends('layouts.master_frontend')

@section('title', 'Wedding 2')

@section('styles')
    <style>
        /* Container */
        .content-container {
            width: 95%;
            max-width: 1000px; /* Ukuran maksimal untuk foto tunggal */
            margin: 30px auto;
        }
        
        /* Container untuk membungkus satu gambar dan membuatnya responsif */
        .single-image-wrapper {
            position: relative;
            width: 100%;
            padding-top: 66.67%; /* Rasio aspek (misalnya 3:2) */
            overflow: hidden;
            background-color: #333; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5); 
        }
        
        .single-image-wrapper img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover; 
            display: block;
        }

        /* Teks Logo di Sudut Bawah Gambar */
        .image-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            color: white;
            z-index: 5;
            text-shadow: 0 0 5px rgba(0, 0, 0, 0.8);
            background-image: linear-gradient(to top, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0));
        }

        .image-overlay .small-logo {
            display: flex;
            align-items: center;
            font-size: 14px;
        }

        .image-overlay .small-logo img {
            width: 20px;
            height: 20px;
            margin-right: 5px;
            position: static;
        }

        .image-overlay .signature-text {
            font-family: Arial, sans-serif;
            font-size: 16px;
            font-style: italic;
        }
    </style>
@endsection


@section('content')

<div class="content-container">
    
    <div class="single-image-wrapper">
        
        {{-- Foto Wedding Utama (Ganti dengan foto Wedding 2 Anda) --}}
        <img src="{{ asset('assetslensart/portofolio/wedding_2/wedding_2.jpg') }}" alt="Foto Wedding Utama Halaman 2">
        
        {{-- Overlay Logo dan Teks di Bawah Gambar --}}
        <div class="image-overlay">
            <div class="small-logo">
                {{-- Logo kecil di sudut kiri bawah --}}
                <img src="{{ asset('assetslensart/logo/Logo Lensart Putih.png') }}" alt="Small Lensart Logo">
                <span>Lensart Photo</span>
            </div>
            <div class="signature-text">
                Wedding Collection II
            </div>
        </div>
        
    </div>
     
</div>

@endsection


@section('gallery_navigation')
    {{-- Tombol Kiri (<): Kembali ke WEDDING 1 (Halaman 3) --}}
    <button class="gallery-nav-button nav-left" onclick="window.location.href = '{{ route('portofolio.wedding1') }}'">
        &lt;
    </button>

    {{-- Tombol Kanan (>): Lanjut ke GRADUATION 1 (Halaman 5) --}}
    <button class="gallery-nav-button nav-right" onclick="window.location.href = '{{ route('portofolio.graduation1') }}'">
        &gt;
    </button>
@endsection