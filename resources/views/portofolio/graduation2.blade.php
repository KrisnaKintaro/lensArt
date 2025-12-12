@extends('layouts.master_frontend')

@section('title', 'Graduation 2')

@section('styles')
<style>
    /* CSS KHUSUS HALAMAN GRADUATION 2 */

    .content-container {
        width: 95%;
        max-width: 1200px;
        margin: 30px auto;
    }

    /* Grid 3 Kolom Sederhana */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr); 
        gap: 15px; 
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
</style>
@endsection

@section('content')

<div class="content-container">
    <div class="gallery-grid">

        <div class="gallery-item">
            <img src="{{ asset('assetslensart/portofolio/graduation_2/graduation_1.jpg') }}" alt="Graduation Foto 1">
        </div>

        <div class="gallery-item">
            <img src="{{ asset('assetslensart/portofolio/graduation_2/graduation_2.jpg') }}" alt="Graduation Foto 2">
        </div>

        <div class="gallery-item">
            <img src="{{ asset('assetslensart/portofolio/graduation_2/graduation_3.jpg') }}" alt="Graduation Foto 3">
        </div>

    </div>
</div>

@endsection

@section('gallery_navigation')
    {{-- Tombol Kiri (<): Kembali ke Graduation 1 --}}
    <button class="gallery-nav-button nav-left"
        onclick="window.location.href = '{{ route('portofolio.graduation1') }}'">
        &lt;
    </button>

    {{-- Tombol Kanan (>): Lanjut ke Event (Atau halaman pertama portofolio berikutnya) --}}
    <button class="gallery-nav-button nav-right"
        onclick="window.location.href = '{{ route('portofolio.event') }}'">
        &gt;
    </button>
@endsection