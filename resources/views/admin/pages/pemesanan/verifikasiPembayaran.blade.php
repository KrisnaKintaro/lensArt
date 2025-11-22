@extends('admin.masterAdmin')
@section('title', 'Verifikasi Pembayaran')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Validasi Pembayaran</h1>
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
                            <h3 class="card-title">Daftar Pembayaran Masuk</h3>
                        </div>
                        <div class="card-body">
                            <table id="tabelPembayaran" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Booking</th>
                                        <th>Tanggal Upload</th>
                                        <th>Customer</th>
                                        <th>Jumlah Transfer</th>
                                        <th>Metode</th>
                                        <th>Status Pembayaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pembayaran as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>

                                            <td>
                                                <strong class="text-primary">
                                                    #{{ $item->pemesanan->nomorBooking ?? '-' }}
                                                </strong>
                                            </td>

                                            <td>
                                                {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}<br>
                                                <small>{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}
                                                    WIB</small>
                                            </td>

                                            <td>
                                                {{ $item->pemesanan->user->namaLengkap ?? 'User Dihapus' }}
                                            </td>

                                            <td>
                                                Rp {{ number_format($item->jumlahBayar, 0, ',', '.') }}
                                            </td>

                                            <td>
                                                <span
                                                    class="badge badge-secondary">{{ strtoupper($item->metodePembayaran) }}</span>
                                            </td>

                                            <td class="text-center text-nowrap" data-search="{{ $item->statusPembayaran }}">
                                                <select class="form-control status-pembayaran-list"
                                                    data-id="{{ $item->idPembayaran }}"
                                                    style="width: auto; display: inline-block;">
                                                    <option value="menunggu"
                                                        {{ $item->statusPembayaran == 'menunggu' ? 'selected' : '' }}
                                                        class="bg-warning text-dark">
                                                        ⏳ Menunggu
                                                    </option>
                                                    <option value="dp"
                                                        {{ $item->statusPembayaran == 'dp' ? 'selected' : '' }}
                                                        class="bg-info text-white">
                                                        💸 DP (50%)
                                                    </option>
                                                    <option value="lunas"
                                                        {{ $item->statusPembayaran == 'lunas' ? 'selected' : '' }}
                                                        class="bg-success text-white">
                                                        ✅ Lunas
                                                    </option>
                                                    <option value="ditolak"
                                                        {{ $item->statusPembayaran == 'ditolak' ? 'selected' : '' }}
                                                        class="bg-danger text-white">
                                                        ❌ Ditolak
                                                    </option>

                                                </select>
                                            </td>

                                            <td>
                                                <button class="btn btn-sm btn-primary btn-lihat-bukti"
                                                    data-foto="{{ asset('gambarBuktiPembayaran/' . $item->buktiPembayaran) }}">
                                                    <i class="fas fa-eye"></i> Cek Bukti
                                                </button>
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
    <div class="modal fade" id="modalLihatBuktiPembayaran" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="width: fit-content;">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Foto Bukti Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body p-0">
                    <img id="modalImage" src="" alt="Bukti Pembayaran" class="img-fluid d-block mx-auto">
                </div>
            </div>
        </div>
    </div>
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

            $('#tabelPembayaran').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "ordering": false
            });

            $(document).on('change', '.status-pembayaran-list', function() {
                let idPembayaran = $(this).data('id');
                let statusPembayaranBaru = $(this).val()

                $.ajax({
                    url: "{{ route('booking.pembayaran.updateStatusPembayaran') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        idPembayaran: idPembayaran,
                        statusPembayaran: statusPembayaranBaru
                    },
                    success: function(response) {
                        if (response.success) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Status pembayaran berhasil diperbarui!'
                            });
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: 'Gagal update status pembayaran'
                        });
                    }
                });
            });

            $(document).on('click', '.btn-lihat-bukti', function() {
                let urlFoto = $(this).data('foto');

                $('#modalImage').attr('src', urlFoto);
                $('#modalLihatBuktiPembayaran').modal('show');

            });
        });
    </script>
@endsection
