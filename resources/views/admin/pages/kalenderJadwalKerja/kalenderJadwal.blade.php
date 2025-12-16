@extends('admin.masterAdmin')
@section('title', 'Booking Jadwal')

@section('css')
    <style>
        .fc-daygrid-day {
            cursor: pointer;
        }

        .day-low {
            background-color: #9dfca5ff !important;
        }

        .day-med {
            background-color: #fbec7cff !important;
        }

        .day-high {
            background-color: #fd5347ff !important;
            /* Kalau Admin ini di comment saja  */
            /* cursor: not-allowed !important; */
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
        }

        .slot-booked {
            background-color: #f35968 !important;
            pointer-events: none;
            color: white !important;
        }
    </style>
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Jadwal Booking</h1>
                </div>
                <div class="col-sm-6">
                    <div class="input-group float-sm-right" style="max-width: 300px;">
                        <input type="date" id="inputLoncatTanggal" class="form-control">
                        <div class="input-group-append">
                            <button class="btn btn-primary" id="btnGoTanggal">
                                <i class="fas fa-search"></i> Cek Tanggal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body p-0">
                            <div id="calendarJadwal" style="min-height: 650px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="modalBooking" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">Form Booking Jadwal</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="formBooking">
                        <div class="row">
                            {{-- KOLOM KIRI: Waktu & Pembayaran --}}
                            <div class="col-md-6 border-right">
                                {{-- Tanggal --}}
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="text" class="form-control font-weight-bold" id="inputTanggal" readonly
                                        style="background-color: #e9ecef;">
                                </div>

                                {{-- Jam --}}
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Jam Mulai</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control font-weight-bold"
                                                    id="inputJamMulai" readonly
                                                    style="background-color: #e9ecef; cursor: not-allowed;" />
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Jam Selesai</label>
                                            <div class="input-group date" id="pickerJamSelesai" data-target-input="nearest">
                                                <input type="text"
                                                    class="form-control font-weight-bold datetimepicker-input"
                                                    id="inputJamSelesai" data-target="#pickerJamSelesai"
                                                    data-toggle="datetimepicker" autocomplete="off" readonly />
                                                <div class="input-group-append" data-target="#pickerJamSelesai"
                                                    data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="far fa-clock"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-light text-sm border mt-1 mb-3">
                                    <i class="fas fa-info-circle text-info"></i> Sistem otomatis cek bentrok jadwal.
                                </div>

                                {{-- Status Pembayaran --}}
                                <div class="form-group">
                                    <label>Status Pembayaran</label>
                                    <select class="form-control" id="pilihStatusPembayaran" required>
                                        <option value="" disabled selected>-- Pilih Status --</option>
                                        <option value="dp">DP (50 %)</option>
                                        <option value="lunas">Lunas</option>
                                    </select>
                                </div>

                                {{-- Metode Pembayaran --}}
                                <div class="form-group">
                                    <label>Metode Pembayaran</label>
                                    <select class="form-control" id="pilihMetodePembayaran" required>
                                        <option value="" disabled selected>-- Pilih Metode --</option>
                                        <option value="transferBank">Transfer Bank</option>
                                        <option value="eWallet">E-Wallet (OVO/Dana/Gopay)</option>
                                        <option value="tunai">Tunai (Cash)</option>
                                    </select>
                                </div>

                                {{-- Upload Bukti (Hidden by default) --}}
                                <div class="form-group" id="divUploadBukti" style="display: none;">
                                    <label>Upload Bukti Pembayaran</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="fileBuktiBayar"
                                            accept="image/*">
                                        <label class="custom-file-label" for="fileBuktiBayar">Pilih file...</label>
                                    </div>
                                    <small class="text-danger">*Wajib untuk Transfer/E-Wallet</small>

                                    <div class="mt-2 text-center">
                                        <img id="previewBukti" src="#" alt="Preview Gambar"
                                            style="display: none; max-width: 100%; max-height: 150px; border-radius: 5px; border: 1px solid #ddd; padding: 3px;">
                                    </div>
                                </div>
                            </div>

                            {{-- KOLOM KANAN: Layanan & Detail --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Pilih Jenis Layanan</label>
                                    <select class="form-control" id="pilihJenisLayanan" required>
                                        <option value="" disabled selected>-- Pilih Layanan --</option>
                                        @foreach ($jenisLayanan as $jl)
                                            <option value="{{ $jl->idJenisLayanan }}">{{ $jl->namaLayanan }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Pilih Paket</label>
                                    <select class="form-control" id="pilihPaket" disabled required>
                                        <option value="" disabled selected>-- Pilih Layanan Dulu --</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Lokasi & Catatan</label>
                                    <textarea class="form-control" id="inputLokasi" rows="2" placeholder="Lokasi acara (wajib)..."></textarea>
                                    <textarea class="form-control mt-2" id="inputCatatan" rows="2" placeholder="Catatan tambahan (opsional)..."></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Total Harga</label>
                                    <input type="hidden" id="totalHarga">
                                    <input type="text" class="form-control font-weight-bold text-success"
                                        id="inputTotalHarga" readonly style="font-size: 1.2rem;">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="btnSimpanBooking">Booking Sekarang</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('adminLte/plugins/fullcalendar/main.js') }}"></script>

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
                    // if (kotakTanggalYangDiClick.classList.contains('day-high')) {
                    //     Swal.fire('Penuh!', 'Tanggal ini sudah full booking.', 'error');
                    //     return;
                    // }
                    calendar.changeView('timeGridDay', info.dateStr)
                },
                // Untuk nampilin event di tiap jam dan tanggalnya
                // otomatis kirim parameter ?start=...&end=...
                events: "{{ route('kalenderJadwal.ambilDataSlotJadwal') }}",

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
                        url: "{{ route('kalenderJadwal.getDataPresentaseHarian') }}",
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

                    // 1. Reset Form Input (Standar)
                    $('#formBooking')[0].reset();

                    // ============================================================
                    // TAMBAHAN: Reset Manual Tampilan Preview & Container Upload
                    // ============================================================
                    $('#previewBukti').attr('src', '#').hide(); // Hapus src gambar & sembunyikan
                    $('#divUploadBukti')
                .hide(); // Sembunyikan container upload (karena select box kembali ke default)
                    $('#fileBuktiBayar').val(
                    ''); // Pastikan input file kosong (opsional, karena reset() sudah handle ini)
                    // ============================================================

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
                        url: "{{ route('kalenderJadwal.ambilDataPaket') }}",
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
                    $('#divUploadBukti').hide();
                    $('#fileBuktiBayar').val('');
                    $('.custom-file-label').text('Pilih file...'); // Reset label bootstrap 4
                    $('#previewBukti').hide();
                } else {
                    $('#divUploadBukti').show();
                }
            });

            $('#fileBuktiBayar').on('change', function() {
                const file = this.files[0];
                if (file) {
                    // Update label nama file
                    $(this).next('.custom-file-label').html(file.name);

                    // Preview gambar
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $('#previewBukti').attr('src', event.target.result).show();
                    }
                    reader.readAsDataURL(file);
                } else {
                    $(this).next('.custom-file-label').html('Pilih file...');
                    $('#previewBukti').hide();
                }
            });

            // Simpan ke db
            $('#btnSimpanBooking').on('click', function() {
                // 1. Ambil semua value
                let tanggal = $('#inputTanggal').val();
                let jamMulai = $('#inputJamMulai').val();
                let jamSelesai = $('#inputJamSelesai').val();
                let idJenisLayanan = $('#pilihJenisLayanan').val();
                let idPaketLayanan = $('#pilihPaket').val();
                let lokasi = $('#inputLokasi').val();
                let catatan = $('#inputCatatan').val();
                let harga = $('#totalHarga').val();

                // Value tambahan
                let statusPembayaran = $('#pilihStatusPembayaran').val();
                let metodePembayaran = $('#pilihMetodePembayaran').val();
                let fileBukti = $('#fileBuktiBayar')[0].files[0];

                let btn = $(this);

                // 2. Validasi Form
                if (!jamSelesai || !idJenisLayanan || !idPaketLayanan || !statusPembayaran || !
                    metodePembayaran) {
                    Toast.fire({
                        icon: 'warning',
                        title: 'Lengkapi semua data form!'
                    });
                    return;
                }

                // Validasi khusus: kalau bukan Tunai, wajib upload bukti
                if (metodePembayaran !== 'tunai' && !fileBukti) {
                    Toast.fire({
                        icon: 'warning',
                        title: 'Upload bukti pembayaran dulu cuy!'
                    });
                    return;
                }

                btn.text('Menyimpan...').prop('disabled', true);

                // 3. Bungkus Data pake FormData (biar bisa upload file)
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
                // Append data baru
                formData.append('statusPembayaran', statusPembayaran);
                formData.append('metodePembayaran', metodePembayaran);
                if (fileBukti) {
                    formData.append('buktiPembayaran', fileBukti);
                }

                // 4. Kirim AJAX
                $.ajax({
                    url: "{{ route('kalenderJadwal.simpanBooking') }}", // Pastikan route ini mengarah ke Controller Admin
                    type: "POST",
                    data: formData,
                    contentType: false, // Wajib false buat upload file
                    processData: false, // Wajib false buat upload file
                    success: function(response) {
                        $('#modalBooking').modal('hide');
                        Swal.fire('Berhasil!', response.message, 'success');

                        // Reset Form Manual
                        $('#formBooking')[0].reset();
                        $('#previewBukti').hide();
                        $('.custom-file-label').text('Pilih file...');

                        // Refresh Kalender
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
