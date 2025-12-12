
@extends('layouts.master_frontend')

@section('title', 'Dashboard Customer')

@section('content')

<div class="container py-5">
    {{-- TAMBAHKAN CEK AUTH INI AGAR TIDAK ERROR --}}
    @if(Auth::check())
        <h1 class="text-white">Selamat Datang, {{ Auth::user()->namaLengkap }}!</h1>
        {{-- ... konten dashboard lainnya ... --}}
    @else
        <p>Anda belum login.</p>
    @endif
</div>

@endsection