@extends('layouts.master_frontend')

@section('title', 'Layanan - Lensart')

@section('content')

<div class="container py-5">
    <h2 class="text-center mb-4 fw-bold text-white">Jenis Layanan Kami</h2>

    <div class="d-flex justify-content-center flex-wrap mb-5">

        @foreach ($jenisLayanan as $jenis)
        <button class="btn btn-outline-warning mx-2 my-1 filter-btn"
            data-target="#kategori-{{ $jenis->idJenisLayanan }}">
            {{ strtoupper($jenis->nama_layanan) }}
        </button>
        @endforeach

    </div>
    <div id="kontenLayanan">
        @if ($jenisLayanan->count() > 0)

        @foreach ($jenisLayanan as $jenis)

        <div id="kategori-{{ $jenis->idJenisLayanan }}"
            class="kategori-content"
            @style([ 'display: block'=> $loop->first,
            'display: none' => ! $loop->first,
            ])
            <h3 class="fw-bold text-warning mb-3">{{ strtoupper($jenis->nama_layanan) }}</h3>

            <div class="bg-dark text-light p-4 border border-secondary rounded">

                @forelse ($jenis->paket as $paket)

                <div class="package-detail mb-3 p-3 bg-secondary bg-opacity-25 rounded d-flex justify-content-between align-items-center flex-wrap">

                    <div class="package-info">
                        <h5 class="mb-1 text-warning fw-bold">{{ $paket->namaPaket }}</h5>

                        <p class="mb-1 small text-white-50">
                            Durasi: **{{ $paket->durasiJam }} Jam** | File Edit: {{ $paket->jumlahFileEdit }}
                        </p>

                        <p class="mb-2 text-light">{{ $paket->deskripsi }}</p>

                        <h5 class="text-success fw-bold">
                            Harga: Rp. {{ number_format($paket->harga, 0, ',', '.') }}
                        </h5>
                    </div>

                    <a href="{{ route('tampilanBookingCustomer') }}"
                        class="btn btn-warning booking-button mt-2 mt-sm-0">
                        Booking Sekarang
                    </a>
                </div>

                @empty
                <p class="text-muted">Belum ada paket tersedia untuk layanan ini.</p>
                @endforelse

                @if ($jenis->deskripsi)
                <p class="mt-3 fst-italic text-white-50">
                    Catatan Kategori: {{ $jenis->deskripsi }}
                </p>
                @endif

            </div>
        </div>
        @endforeach

        @else
        <p class="alert alert-info">Belum ada jenis layanan yang terdaftar.</p>
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Sembunyikan semua konten kecuali yang pertama saat halaman dimuat
        $('.kategori-content').hide();
        $('#kontenLayanan .kategori-content:first').show();

        // Tambahkan kelas aktif pada tombol pertama
        $('.filter-btn:first').addClass('active');

        $('.filter-btn').on('click', function() {
            var target = $(this).data('target');

            // Sembunyikan semua konten
            $('.kategori-content').hide();

            // Tampilkan konten target
            $(target).show();

            // Hapus kelas aktif dari semua tombol dan tambahkan ke tombol yang diklik
            $('.filter-btn').removeClass('active');
            $(this).addClass('active');
        });
    });
</script>
@endsection
