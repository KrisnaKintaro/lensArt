@extends('layouts.master_frontend')

@section('title', 'Riwayat Booking')

@section('content')
<div class="container" style="margin-top: 100px; margin-bottom: 50px;">

    {{-- Notifikasi Sukses/Gagal pas Batalin --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow border-0">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Riwayat Booking Saya</h5>
        </div>
        <div class="card-body">

            {{-- FILTER BUTTONS --}}
            <div class="mb-4">
                <small class="text-muted d-block mb-2">Filter Status:</small>
                <a href="{{ route('customer.riwayat.booking', ['status' => 'semua']) }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">Semua</a>
                <a href="{{ route('customer.riwayat.booking', ['status' => 'pending']) }}" class="btn btn-warning btn-sm rounded-pill px-3">Pending</a>
                <a href="{{ route('customer.riwayat.booking', ['status' => 'disetujui']) }}" class="btn btn-info btn-sm rounded-pill px-3 text-white">Disetujui</a>
                <a href="{{ route('customer.riwayat.booking', ['status' => 'selesai']) }}" class="btn btn-success btn-sm rounded-pill px-3">Selesai</a>
                <a href="{{ route('customer.riwayat.booking', ['status' => 'dibatalkan']) }}" class="btn btn-danger btn-sm rounded-pill px-3">Dibatalkan</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No Booking</th>
                            <th>Jadwal Foto</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $book)
                            <tr>
                                <td class="fw-bold">{{ $book->nomorBooking }}</td>
                                <td>
                                    {{-- Mengambil data tanggal dari relasi slotJadwal --}}
                                    <i class="fa fa-calendar me-1"></i> {{ $book->slotJadwal->tanggal ?? '-' }} <br>
                                    <i class="fa fa-clock me-1"></i> {{ $book->slotJadwal->jamMulai ?? '-' }} - {{ $book->slotJadwal->jamSelesai ?? '-' }}
                                </td>
                                <td>Rp {{ number_format($book->totalHarga, 0, ',', '.') }}</td>
                                <td>
                                    @if($book->statusPemesanan == 'pending')
                                        <span class="badge bg-warning text-dark">PENDING</span>
                                    @elseif($book->statusPemesanan == 'disetujui')
                                        <span class="badge bg-info">DISETUJUI</span>
                                    @elseif($book->statusPemesanan == 'selesai')
                                        <span class="badge bg-success">SELESAI</span>
                                    @else
                                        <span class="badge bg-danger">DIBATALKAN</span>
                                    @endif
                                </td>
                                <td>
                                    {{-- Tombol Batal CUMA muncul kalau status PENDING --}}
                                    @if($book->statusPemesanan == 'pending')
                                        <form action="{{ route('customer.booking.cancel', $book->idPemesanan) }}" method="POST" onsubmit="return confirm('Yakin batal booking?');">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">Batal</button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary btn-sm" disabled>Selesai</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4">Belum ada booking.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
