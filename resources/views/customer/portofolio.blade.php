@extends('layouts.master_frontend')

@section('title', 'Portofolio - Lensart')

@section('header')
<!-- SWIPER CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<!-- MASONRY -->
<script src="https://cdn.jsdelivr.net/npm/masonry-layout@4/dist/masonry.pkgd.min.js"></script>

<!-- FANCYBOX -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />

<!-- JQUERY -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<style>
    .filter-menu button {
        margin: 6px;
        border-radius: 20px;
        padding: 6px 18px;
        transition: 0.25s ease;
    }

    .filter-menu .btn-outline-dark {
        color: #444 !important;
        border-color: #777 !important;
    }

    .filter-menu .btn-outline-dark:hover {
        color: white !important;
        background-color: #555 !important;
    }

    .filter-menu .btn-dark {
        background-color: #000 !important;
        color: white !important;
    }
</style>
@endsection

@section('content')

<div class="container py-5">

    <h2 class="fw-bold text-center mb-4">My Portofolio</h2>

    <!-- FILTER MENU -->
    <div class="text-center mb-4 filter-menu">
        <button class="btn btn-dark active" data-filter="all">Semua</button>

        @foreach ($jenisLayanans as $j)
        <button class="btn btn-outline-dark" data-filter="{{ $j->idJenisLayanan }}">
            {{ $j->namaLayanan }}
        </button>
        @endforeach
    </div>

    <!-- GALLERY -->
    <div class="row g-4" id="gallery">

        @foreach ($groupedPortofolios as $jenisId => $items)
        @foreach ($items as $porto)
        <div class="col-sm-6 col-md-4 gallery-item" data-category="{{ $jenisId }}">
            <a data-fancybox="gallery-{{ $jenisId }}"
                href="{{ asset('assetslensart/portofolio/' . $porto->urlPorto) }}"
                data-caption="{{ $porto->namaPortofolio }}">
                <img src="{{ asset('assetslensart/portofolio/' . $porto->urlPorto) }}"
                    class="img-fluid rounded shadow-sm" style="width:100%; height:260px; object-fit:cover;">
            </a>

            <p class="mt-2 text-center fw-semibold">{{ $porto->namaPortofolio }}</p>
        </div>
        @endforeach
        @endforeach

    </div>

</div>

@endsection

@section('scripts')

<!-- FANCYBOX JS -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

<script>
    $(document).ready(function() {
        $(".filter-menu button").on("click", function() {
            let filter = $(this).data("filter");
            let baseUrl = "{{ url('/portofolio/filter') }}";
            let url = baseUrl + "/" + filter;

            // UI Button Logic
            $(".filter-menu button").removeClass("btn-dark active").addClass("btn-outline-dark");
            $(this).removeClass("btn-outline-dark").addClass("btn-dark active");

            // LOGIKA LAMA YANG SALAH (Hapus bagian ini):
            /* if (filter === "all") {
                $(".gallery-item").show();
                return;
            }
            */

            // LOGIKA BARU: Semuanya lewat AJAX (Konsisten)
            // Pastikan Controller kamu menangani jika ID = 'all' maka return semua data JSON

            $("#gallery").html('<div class="text-center w-100 py-5">Loading...</div>'); // Kasih loading effect

            $.get(url, function(data) {
                $("#gallery").empty(); // Bersihkan gallery

                if (data.length === 0) {
                    $("#gallery").html('<p class="text-center">Tidak ada data.</p>');
                }

                $.each(data, function(i, item) {
                    // Pastikan struktur HTML di sini SAMA PERSIS dengan loop PHP di atas
                    $("#gallery").append(`
                        <div class="col-sm-6 col-md-4 gallery-item" data-category="${item.idJenisLayanan}">
                            <a data-fancybox="gallery-${item.idJenisLayanan}"
                               href="/assetslensart/portofolio/${item.urlPorto}"
                               data-caption="${item.namaPortofolio}">
                                <img src="/assetslensart/portofolio/${item.urlPorto}"
                                    class="img-fluid rounded shadow-sm"
                                    style="width:100%; height:260px; object-fit:cover;">
                            </a>
                            <p class="mt-2 text-center fw-semibold">${item.namaPortofolio}</p>
                        </div>
                    `);
                });

                // Re-bind Fancybox (Penting!)
                Fancybox.bind("[data-fancybox]");

            }).fail(function() {
                alert("Gagal memuat data. Cek Console (F12) untuk detail error.");
            });
        });
    });
</script>

@endsection
