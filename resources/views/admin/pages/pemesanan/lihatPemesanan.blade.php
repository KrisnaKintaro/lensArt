@extends('admin.masterAdmin')
@section('title', 'Data Pemesanan')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Kelola Pemesanan</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Pesanan Masuk</h3>
                        </div>
                        <div class="card-body">
                            <table id="tabelPemesanan" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Booking</th>
                                        <th>Customer</th>
                                        <th>Paket Layanan</th>
                                        <th>Waktu & Lokasi</th>
                                        <th>Status Booking</th>
                                        <th>Status Pembayaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($pemesanan as $item)
                                        <tr>
                                            <td>
                                                <strong>
                                                    <?= $no++ ?>
                                                </strong>
                                            </td>
                                            <td>
                                                <strong class="text-primary">#{{ $item->nomorBooking }}</strong><br>
                                                <small class="text-muted">
                                                    Tanggal Booking :
                                                    {{ \Carbon\Carbon::parse($item->tanggalPemesanan)->format('d M Y') }}
                                                </small>
                                            </td>

                                            <td>
                                                <strong>{{ $item->user->namaLengkap ?? 'User Terhapus' }}</strong><br>
                                                <small>{{ $item->user->noTelp ?? '-' }}</small><br>
                                                <small>{{ $item->user->email ?? '-' }}</small>
                                            </td>

                                            <td>
                                                @if ($item->slotJadwal && $item->slotJadwal->jenisLayanan)
                                                    <span class="badge badge-info">
                                                        {{ $item->slotJadwal->jenisLayanan->namaLayanan }}
                                                    </span>
                                                @endif
                                                <br>
                                                {{ $item->slotJadwal->paketLayanan->namaPaket ?? '-' }}
                                            </td>

                                            <td>
                                                @if ($item->slotJadwal)
                                                    {{ \Carbon\Carbon::parse($item->slotJadwal->tanggal)->format('d M Y') }}
                                                    <br>
                                                    <small>
                                                        {{ \Carbon\Carbon::parse($item->slotJadwal->jamMulai)->format('H:i') }}
                                                        -
                                                        {{ $item->slotJadwal->jamSelesai ? \Carbon\Carbon::parse($item->slotJadwal->jamSelesai)->format('H:i') : 'Selesai' }}
                                                    </small><br>
                                                    <small class="text-muted">
                                                        <Strong>{{ $item->lokasiAcara }}</Strong>
                                                    </small>
                                                @else
                                                    <span class="text-danger">Jadwal Dihapus</span>
                                                @endif
                                            </td>
                                            <td class="text-center" data-search="{{ $item->statusPemesanan }}">
                                                <select class="form-control status-booking"
                                                    style="width: auto; display: inline-block;"
                                                    data-id="{{ $item->idPemesanan }}">
                                                    <option value="pending" class="bg-warning text-dark"
                                                        {{ $item->statusPemesanan == 'pending' ? 'selected' : '' }}>
                                                        ⏳ Pending
                                                    </option>
                                                    <option value="disetujui" class="bg-primary text-white"
                                                        {{ $item->statusPemesanan == 'disetujui' ? 'selected' : '' }}>
                                                        👍 Disetujui
                                                    </option>
                                                    <option value="selesai" class="bg-success text-white"
                                                        {{ $item->statusPemesanan == 'selesai' ? 'selected' : '' }}>
                                                        ✅ Selesai
                                                    </option>
                                                    <option value="dibatalkan" class="bg-danger text-white"
                                                        {{ $item->statusPemesanan == 'dibatalkan' ? 'selected' : '' }}>
                                                        ❌ Batal
                                                    </option>
                                                </select>
                                            </td>

                                            <td class="text-center" data-search="{{ $item->statusPembayaran }}">
                                                <select class="form-control status-pembayaran"
                                                    style="width: auto; display: inline-block;"
                                                    data-id="{{ $item->idPemesanan }}">
                                                    <option value="menunggu" class="bg-warning text-white"
                                                        {{ $item->statusPembayaran == 'menunggu' ? 'selected' : '' }}>
                                                        ⏳ Menunggu
                                                    </option>
                                                    <option value="dp" class="bg-info text-white"
                                                        {{ $item->statusPembayaran == 'dp' ? 'selected' : '' }}>
                                                        💸 DP (50%)
                                                    </option>
                                                    <option value="lunas" class="bg-success text-white"
                                                        {{ $item->statusPembayaran == 'lunas' ? 'selected' : '' }}>
                                                        ✅ Lunas
                                                    </option>
                                                    <option value="ditolak" class="bg-danger text-white"
                                                        {{ $item->statusPembayaran == 'ditolak' ? 'selected' : '' }}>
                                                        ❌ Ditolak
                                                    </option>
                                                </select><br>
                                                <small class="d-block text-left text-muted">
                                                    <strong>Total Harga : </strong> Rp
                                                    {{ number_format($item->totalHarga, 0, ',', '.') }}
                                                </small>
                                                <small class="d-block text-left text-muted">
                                                    <strong>Terakhir Bayar : </strong>
                                                    {{ \Carbon\Carbon::parse($item->tanggalPembayaran)->format('d M Y') }}
                                                </small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        $(function() {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            $('#tabelPemesanan').DataTable({
                "responsive": true,
                "lengthChange": false, // Ilangin pilihan 'Show 10 entries' biar bersih
                "autoWidth": false,
                "paging": true, // Aktifin Pagination (Halaman 1, 2, 3)
                "searching": true, // Aktifin Search Box
                "ordering": false // Matiin sort default biar urutan Controller gak keganggu
            });

            $(document).on('change', '.status-booking', function() {
                let idPemesanan = $(this).data('id');
                let statusBookingBaru = $(this).val();

                $.ajax({
                    url: "{{ route('booking.pemesanan.updateStatusBooking') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        idPemesanan: idPemesanan,
                        statusPemesanan: statusBookingBaru,
                    },
                    success: function(response) {
                        if (response.success) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Status pemesanan berhasil diperbarui!'
                            });
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: 'Gagal update status pemesanan! Coba lagi nanti.'
                        });
                    }
                });
            });

            $(document).on('change', '.status-pembayaran', function() {
                let idPemesanan = $(this).data('id');
                let statusPembayaranBaru = $(this).val();

                $.ajax({
                    url: "{{ route('booking.pemesanan.updateStatusPembayaran') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        idPemesanan: idPemesanan,
                        statusPembayaran: statusPembayaranBaru,
                    },
                    success: function(response) {
                        if (response.success) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Status Pembayaran Berhasil Diubah!'
                            });
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: 'Gagal mengubah status pembayaran'
                        });
                    }
                });
            });
        });
    </script>
@endsection
