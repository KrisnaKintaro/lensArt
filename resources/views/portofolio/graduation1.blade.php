@extends('layouts.master_frontend')

@section('title', 'Graduation 1')

@section('styles')
    <style>
        /* CSS Gallery Grid untuk 3 Kolom Sama Rata */
        .gallery-grid {
            display: grid;
            /* Tiga kolom dengan lebar yang sama */
            grid-template-columns: repeat(3, 1fr); 
            gap: 15px; 
            /* Menjaga rasio tinggi agar foto terlihat bagus */
            grid-auto-rows: minmax(500px, auto); 
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
            display: block;
            transition: transform 0.3s ease;
        }

        .gallery-item img:hover {
            transform: scale(1.05);
        }
        
        /* Pastikan tidak ada konflik dengan item-1, item-2, dst dari CSS Event jika Anda lupa menghapusnya di master */
    </style>
@endsection

@section('content')
    <div class="content-container">
        <div class="gallery-grid">
            
            {{--
                ==========================================================
                ISI FOTO 3 KOLOM UTAMA (GRADUATION 1)
                ==========================================================
            --}}
            
            {{-- Foto Kiri --}}
            <div class="gallery-item">
                <img src="{{ asset('assetslensart/portofolio/graduation_1/graduation_1.jpg') }}" alt="Foto Wisuda Kiri">
            </div>

            {{-- Foto Tengah --}}
            <div class="gallery-item">
                 <img src="{{ asset('assetslensart/portofolio/graduation_1/graduation_2.jpg') }}" alt="Foto Wisuda Tengah">
            </div>

            {{-- Foto Kanan --}}
            <div class="gallery-item">
               <img src="{{ asset('assetslensart/portofolio/graduation_1/graduation_3.jpg') }}" alt="Foto Wisuda Kanan">
            </div>
            
        </div>
    </div>
@endsection

@section('gallery_navigation')
    {{-- Tombol Kiri (<): Kembali ke WEDDING 2 (Halaman 4) --}}
    <button class="gallery-nav-button nav-left" onclick="window.location.href = '{{ route('portofolio.wedding2') }}'">
        &lt;
    </button>
    
    {{-- Tombol Kanan (>): Lanjut ke GRADUATION 2 (Halaman 6) --}}
    <button class="gallery-nav-button nav-right" onclick="window.location.href = '{{ route('portofolio.graduation2') }}'">
        &gt;
    </button>
@endsection