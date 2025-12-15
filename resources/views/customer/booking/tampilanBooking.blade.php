@extends('layouts.master_frontend')

@section('title', 'Formulir Pemesanan')

@section('styles')
<link rel="stylesheet" href="{{ asset('adminLte/plugins/fullcalendar/main.css') }}">
<link rel="stylesheet" href="{{ asset('adminLte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">

<link rel="stylesheet" href="{{ asset('adminLte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
<style>
    .swal2-container {
        z-index: 2060 !important;
        /* Bootstrap Modal itu 1055, jadi kita kasih 2000an biar menang */
    }

    /* Styling khusus FullCalendar */
    .fc-header-toolbar {
        margin-bottom: 20px !important;
    }

    .fc-toolbar-title {
        font-size: 1.5rem !important;
        font-weight: bold;
    }

    .fc-daygrid-day {
        cursor: pointer;
        transition: background 0.2s;
    }

    .fc-daygrid-day:hover {
        background-color: #f0f0f0;
    }

    /* Legend Warna */
    .day-low {
        background-color: #9dfca5ff !important;
    }

    .day-med {
        background-color: #fbec7cff !important;
    }

    .day-high {
        background-color: #fd5347ff !important;
        position: relative;
    }

    .day-high::after {
        content: 'FULL';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #c62828;
        font-weight: bold;
        opacity: 0.5;
        font-size: 0.8rem;
    }

    .slot-booked {
        background-color: #f35968 !important;
        pointer-events: none;
        color: white !important;
        border: none;
    }

    /* Container Putih untuk Kalender */
    .booking-card {
        background: rgba(255, 255, 255, 0.95);
        /* Putih agak transparan dikit */
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        color: #333;
        /* Text jadi hitam biar kebaca di background putih */
        margin-top: 50px;
        /* Jarak dari Navbar */
        margin-bottom: 50px;
    }

    /* Fix Bootstrap 5 Input Group */
    .input-group-text {
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="booking-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold"><i class="fas fa-calendar-alt text-primary me-2"></i>Jadwal Booking</h2>
                <p class="text-muted mb-0">Silakan pilih tanggal dan jam yang tersedia.</p>
            </div>

            <div class="d-flex" style="max-width: 300px;">
                <input type="date" id="inputLoncatTanggal" class="form-control me-2">
                <button class="btn btn-primary text-nowrap" id="btnGoTanggal">
                    <i class="fas fa-search"></i> Cek
                </button>
            </div>
        </div>

        <hr>

        <div id="calendarJadwal" style="min-height: 650px;"></div>
    </div>
</div>

<div class="modal fade" id="modalBooking" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content text-dark">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Form Booking Jadwal</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="formBooking">
                    <div class="row g-3">
                        <div class="col-md-6 border-end">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal</label>
                                <input type="text" class="form-control bg-light" id="inputTanggal" readonly>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Jam Mulai</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control bg-light" id="inputJamMulai" readonly>
                                            <span class="input-group-text"><i class="far fa-clock"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Jam Selesai</label>
                                        <div class="input-group date" id="pickerJamSelesai" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" id="inputJamSelesai" data-target="#pickerJamSelesai" readonly />
                                            <div class="input-group-text" data-target="#pickerJamSelesai" data-toggle="datetimepicker">
                                                <i class="far fa-clock"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info d-flex align-items-center small mt-2" role="alert">
                                <i class="fas fa-info-circle me-2"></i>
                                <div>Sistem otomatis mengecek bentrok jadwal.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status Pembayaran</label>
                                <select class="form-select" id="pilihStatusPembayaran" required>
                                    <option value="" disabled selected>-- Pilih Status --</option>
                                    <option value="dp">DP (50 %)</option>
                                    <option value="lunas">Lunas</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Metode Pembayaran</label>
                                <select class="form-select" id="pilihMetodePembayaran" required>
                                    <option value="" disabled selected>-- Pilih Metode --</option>
                                    <option value="transferBank">Transfer Bank</option>
                                    <option value="eWallet">E-Wallet (OVO/Dana/Gopay)</option>
                                    <option value="tunai">Tunai (Cash)</option>
                                </select>
                            </div>

                            <div class="mb-3" id="divUploadBukti" style="display: none;">
                                <label class="form-label fw-bold">Upload Bukti Pembayaran</label>
                                <input type="file" class="form-control" id="fileBuktiBayar" accept="image/*">
                                <div class="form-text text-danger">*Wajib untuk Transfer/E-Wallet</div>

                                <div class="mt-2 text-center">
                                    <img id="previewBukti" src="#" alt="Preview Gambar"
                                        style="display: none; max-width: 100%; max-height: 200px; border-radius: 8px; border: 1px solid #ddd; padding: 5px;">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Pilih Jenis Layanan</label>
                                <select class="form-select" id="pilihJenisLayanan" required>
                                    <option value="" disabled selected>-- Pilih Layanan --</option>
                                    @foreach ($jenisLayanan as $jl)
                                    <option value="{{ $jl->idJenisLayanan }}">{{ $jl->namaLayanan }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Pilih Paket</label>
                                <select class="form-select" id="pilihPaket" disabled required>
                                    <option value="" disabled selected>-- Pilih Layanan Dulu --</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Lokasi & Catatan</label>
                                <textarea class="form-control mb-2" id="inputLokasi" rows="2" placeholder="Lokasi acara (Wajib)..."></textarea>
                                <textarea class="form-control" id="inputCatatan" rows="2" placeholder="Catatan tambahan..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Total Harga</label>
                                <input type="hidden" id="totalHarga">
                                <input type="text" class="form-control fs-4 fw-bold text-success bg-white border-0 ps-0" id="inputTotalHarga" readonly placeholder="Rp 0">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="btnSimpanBooking">Booking Sekarang</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('adminLte/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('adminLte/plugins/fullcalendar/main.js') }}"></script>
<script src="{{ asset('adminLte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<script src="{{ asset('adminLte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
    function updateWarnaBackground(heatmapData) {
        $('.fc-daygrid-day').removeClass('day-low day-med day-high');

        if (heatmapData && heatmapData.length > 0) {
            heatmapData.forEach(function(data) {
                // Cari elemen tanggal di DOM (jQuery selector)
                let $dayEl = $(`.fc-daygrid-day[data-date="${data.tanggal}"]`);
                if ($dayEl.length) {
                    // Tambahkan class yang sudah dihitung di Controller
                    $dayEl.addClass(data.className);
                }
            });
        }
    }

    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
    $(function() {
        // Inisialisasi Tempus Dominus
        // Format 'HH:mm' (H besar) artinya 24 Jam.
        // Kalau 'hh:mm' (h kecil) artinya 12 Jam (AM/PM).
        $('#pickerJamSelesai').datetimepicker({
            format: 'HH:mm', // Format 24 Jam
            useCurrent: false,
            allowInputToggle: true,
            ignoreReadonly: true,
        });
        // Inisialisai FullCalendar
        var wadahKalender = document.getElementById('calendarJadwal');

        var calendar = new FullCalendar.Calendar(wadahKalender, {
            themeSystem: 'bootstrap',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridDay',
            },
            initialView: 'dayGridMonth',
            locale: 'id',
            selectable: true,
            selectOverlap: false,

            dateClick: function(info) {
                var kotakTanggalYangDiClick = info.dayEl;
                // Jika sebagai admin ini dicomment saja
                if (kotakTanggalYangDiClick.classList.contains('day-high')) {
                    Swal.fire('Penuh!', 'Tanggal ini sudah full booking.', 'error');
                    return;
                }
                calendar.changeView('timeGridDay', info.dateStr)
            },
            // Untuk nampilin event di tiap jam dan tanggalnya
            // otomatis kirim parameter ?start=...&end=...
            events: "{{ route('bookingCustomer.ambilDataSlotJadwal') }}",

            displayEventEnd: true,
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                meridiem: false,
                hour12: false
            },

            datesSet: function(info) {
                // Ambil tanggal mulai dan akhir dari tampilan kalender saat ini
                let startDate = info.startStr.split('T')[0];
                let endDate = info.endStr.split('T')[0];

                // Panggil API baru menggunakan AJAX
                $.ajax({
                    url: "{{ route('bookingCustomer.getDataPresentaseHarian') }}",
                    type: 'GET',
                    data: {
                        start: startDate,
                        end: endDate
                    },
                    success: function(response) {
                        console.log(response)
                        updateWarnaBackground(response);
                    },
                    error: function() {
                        console.error('Gagal memuat data heatmap.');
                    }
                });
            },

            // Munculin modal buat form booking saat slot jam di klik
            select: function(info) {
                if (info.view.type !== 'timeGridDay') return;

                var waktuMulai = moment(info.startStr);
                var waktuSelesai = waktuMulai.clone().add(1, 'hours');
                // Reset Form
                $('#formBooking')[0].reset();
                $('#pilihPaket').prop('disabled', true).html(
                    '<option>-- Pilih Layanan Dulu --</option>');
                $('#inputTotalHarga').val('');

                // Set Data
                $('#inputTanggal').val(waktuMulai.format('YYYY-MM-DD'));

                $('#inputJamMulai').val(waktuMulai.format('HH:mm'));
                $('#inputJamSelesai').val(waktuSelesai.format('HH:mm'));

                $('#modalBooking').modal('show');
            }
        });
        calendar.render()
        // Fitur loncat tanggal di pojok kanan atas
        $('#btnGoTanggal').on('click', function() {
            var tanggalDipilih = $('#inputLoncatTanggal').val();
            if (tanggalDipilih) {
                calendar.gotoDate(tanggalDipilih);
                calendar.changeView('timeGridDay', tanggalDipilih)
            } else {
                Toast.fire({
                    icon: 'warning',
                    title: 'Pilih tanggal dulu dong'
                });
            }
        });
        // Ambil data paket saat jenis layanan di pilih
        $('#pilihJenisLayanan').on('change', function() {
            var idJenisLayananYangDipilih = $(this).val();
            var dropDownPaket = $('#pilihPaket');
            dropDownPaket.html('<option>Loading...</option>').prop('disabled', true);
            if (idJenisLayananYangDipilih) {
                $.ajax({
                    url: "{{ route('bookingCustomer.ambilDataPaket') }}",
                    type: "GET",
                    data: {
                        idJenisLayanan: idJenisLayananYangDipilih
                    },
                    success: function(hasilPaket) {
                        dropDownPaket.empty();
                        dropDownPaket.append(
                            '<option disabled selected>-- Pilih Paket --</option>');
                        $.each(hasilPaket, function(index, paket) {
                            dropDownPaket.append('<option value="' + paket
                                .idPaketLayanan + '" data-harga="' + paket
                                .harga + '">' + paket.namaPaket +
                                '</option>');
                        });
                        dropDownPaket.prop('disabled', false);
                    }
                });
            }
        });
        $('#pilihPaket').on('change', function() {
            var opsiYangDipilih = $(this).find(':selected');
            var harga = opsiYangDipilih.data('harga');
            if (harga) {
                var formatRupiah = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(harga);
                $('#totalHarga').val(harga);
                $('#inputTotalHarga').val(formatRupiah);
            } else {
                $('#totalHarga').val('');
                $('#inputTotalHarga').val('');
            }
        });
        // Validasi Input jam selesai ( kurang dari jam mulai dan antisipasi tabrakan)
        $('#pickerJamSelesai').on('change.datetimepicker', function(e) {
            // if (!e.date) return;
            // Ambil value
            let tanggal = $('#inputTanggal').val();
            let jamMulai = $('#inputJamMulai').val();
            let jamSelesai = $('#inputJamSelesai').val();

            if (!jamMulai || !jamSelesai) return;

            // Logic Validasi Waktu
            let jamStart = moment(tanggal + 'T' + jamMulai);
            let jamEnd = moment(tanggal + 'T' + jamSelesai);

            var waktuSelesai = jamStart.clone().add(1, 'hours');

            // Cek 1: Jam Selesai < Jam Mulai
            if (jamEnd.isSameOrBefore(jamStart)) {
                Swal.fire('Jam Error', 'Jam selesai harus lebih akhir dari jam mulai!', 'warning');
                $('#inputJamSelesai').val(waktuSelesai.format('HH:mm'));
                return;
            }

            // Cek 2: Tabrakan dengan Event Lain
            let events = calendar.getEvents();
            let isTabrakan = false;

            events.forEach(function(evt) {
                if (evt.extendedProps.status === 'terpesan') { // Cuma cek yang merah
                    let evtStart = moment(evt.start);
                    let evtEnd = moment(evt.end);

                    // Rumus Tabrakan: (StartBaru < EndLama) DAN (EndBaru > StartLama)
                    if (jamStart.isBefore(evtEnd) && jamEnd.isAfter(evtStart)) {
                        isTabrakan = true;
                    }
                }
            });
            if (isTabrakan) {
                Swal.fire({
                    icon: 'error',
                    title: 'Jadwal Bentrok!',
                    text: 'Jam selesai yang kamu pilih menabrak jadwal lain yang sudah dibooking (Merah). Silakan pilih jam kosong lainnya.',
                    confirmButtonText: 'Oke, Saya Ganti'
                });
                $('#inputJamSelesai').val(waktuSelesai.format('HH:mm'));
                return;
            }
        });

        $('#pilihMetodePembayaran').on('change', function() {
            let metode = $(this).val();
            if (metode === 'tunai') {
                $('#divUploadBukti').hide(); // Sembunyikan kalau Tunai
                $('#fileBuktiBayar').val(''); // Reset input file biar bersih
            } else {
                $('#divUploadBukti').show(); // Munculkan kalau Transfer/E-Wallet
            }
        });

        $('#fileBuktiBayar').on('change', function() {
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    $('#previewBukti')
                        .attr('src', event.target.result) // Masukin data gambar ke src
                        .show(); // Munculin gambarnya
                }
                reader.readAsDataURL(file);
            } else {
                // Kalau user cancel pilih file
                $('#previewBukti').hide();
            }
        });

        // Simpan ke db
        $('#btnSimpanBooking').on('click', function() {
            let tanggal = $('#inputTanggal').val();
            let jamMulai = $('#inputJamMulai').val();
            let jamSelesai = $('#inputJamSelesai').val();
            let idJenisLayanan = $('#pilihJenisLayanan').val();
            let idPaketLayanan = $('#pilihPaket').val();
            let lokasi = $('#inputLokasi').val();
            let catatan = $('#inputCatatan').val();
            let harga = $('#totalHarga').val();
            let statusPembayaran = $('#pilihStatusPembayaran').val();
            let metodePembayaran = $('#pilihMetodePembayaran').val();
            let fileBukti = $('#fileBuktiBayar')[0].files[0];

            let btn = $(this);
            if (!jamSelesai || !idJenisLayanan || !idPaketLayanan || !statusPembayaran || !metodePembayaran) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Lengkapi semua data form!'
                });
                return;
            }
            if (metodePembayaran !== 'tunai' && !fileBukti) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Upload bukti pembayaran dulu cuy!'
                });
                return;
            }

            btn.text('Menyimpan...').prop('disabled', true);

            let formData = new FormData();
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('tanggal', tanggal);
            formData.append('jamMulai', jamMulai);
            formData.append('jamSelesai', jamSelesai);
            formData.append('idJenisLayanan', idJenisLayanan);
            formData.append('idPaketLayanan', idPaketLayanan);
            formData.append('lokasiAcara', lokasi);
            formData.append('catatan', catatan);
            formData.append('totalHarga', harga);
            formData.append('statusPembayaran', statusPembayaran);
            formData.append('metodePembayaran', metodePembayaran);
            if (fileBukti) {
                formData.append('buktiPembayaran', fileBukti);
            }

            $.ajax({
                url: "{{ route('bookingCustomer.simpanBooking') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#modalBooking').modal('hide');
                    Swal.fire('Berhasil!', response.message, 'success');
                    // Refresh Kalender biar kotak merahnya muncul
                    calendar.refetchEvents();
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON ? xhr.responseJSON.message :
                        'Terjadi kesalahan sistem';
                    Swal.fire('Gagal!', msg, 'error');
                },
                complete: function() {
                    $('#btnSimpanBooking').text('Booking Sekarang').prop('disabled', false);
                }
            });
        });
    })
</script>
@endsection
