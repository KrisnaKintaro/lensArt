@extends('layouts.master_frontend')

@section('title', 'Model')

@section('styles')
<style>
    /* Hanya sertakan CSS yang SPESIFIK untuk tampilan grid Halaman Model.
    Body, reset, dan navbar style diwarisi dari master_frontend.
    */
    
    /* Container */
    .content-container {
        width: 95%;
        max-width: 1200px;
        margin: 30px auto;
    }

    /* GRID 3 Kolom Rasio 4:3 */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-auto-rows: minmax(200px, auto);
        gap: 15px;
    }

    .gallery-item {
        position: relative;
        overflow: hidden;
        background-color: #333;
        aspect-ratio: 4 / 3; /* Rasio seragam untuk foto Model */
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
</style>
@endsection


@section('content')

<div class="content-container">
    <div class="gallery-grid">

        {{-- ITEM 1 --}}
        <div class="gallery-item">
            <img src="{{ asset('assetslensart/portofolio/model/model_1.jpg') }}" alt="Model 1">
        </div>

        {{-- ITEM 2 --}}
        <div class="gallery-item">
            <img src="{{ asset('assetslensart/portofolio/model/model_2.jpg') }}" alt="Model 2">
        </div>

        {{-- ITEM 3 --}}
        <div class="gallery-item">
            <img src="{{ asset('assetslensart/portofolio/model/model_3.jpg') }}" alt="Model 3">
        </div>

        {{-- ITEM 4 --}}
        <div class="gallery-item">
            <img src="{{ asset('assetslensart/portofolio/model/model_4.jpg') }}" alt="Model 4">
        </div>

        {{-- ITEM 5 --}}
        <div class="gallery-item">
            <img src="{{ asset('assetslensart/portofolio/model/model_5.jpg') }}" alt="Model 5">
        </div>

        {{-- ITEM 6 --}}
        <div class="gallery-item">
            <img src="{{ asset('assetslensart/portofolio/model/model_6.jpg') }}" alt="Model 6">
        </div>

    </div>
</div>

@endsection


@section('gallery_navigation')
    {{-- Tombol Kiri: kembali ke Event (Halaman sebelumnya) --}}
    <button class="gallery-nav-button nav-left"
        onclick="window.location.href = '{{ route('portofolio.event') }}'">
        &lt;
    </button>

    {{-- Tombol Kanan: lanjut ke Wedding 1 (Halaman berikutnya) --}}
    <button class="gallery-nav-button nav-right"
        onclick="window.location.href = '{{ route('portofolio.wedding1') }}'">
        &gt;
    </button>
@endsection