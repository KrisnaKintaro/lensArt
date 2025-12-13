@extends('layouts.master_frontend')

@section('title', 'Status Pembayaran')

@section('content')
<div class="container" style="margin-top: 100px; margin-bottom: 50px;">
    
    {{-- 1. NOTIFIKASI SUKSES/GAGAL --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Validasi Error Upload --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow border-0">
        {{-- Judul --}}
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Riwayat Verifikasi Pembayaran</h5>
        </div>

        <div class="card-body">
            
            {{-- BAGIAN 2: TOMBOL FILTER --}}
            <div class="mb-4">
                <span class="d-block mb-2 text-muted">Filter Status:</span>
                
                <a href="{{ route('customer.riwayat.pembayaran', ['status' => 'semua']) }}" 
                   class="btn btn-outline-dark btn-sm rounded-pill px-3">Semua</a>
                   
                <a href="{{ route('customer.riwayat.pembayaran', ['status' => 'menunggu']) }}" 
                   class="btn btn-secondary btn-sm rounded-pill px-3">Menunggu</a>
                   
                <a href="{{ route('customer.riwayat.pembayaran', ['status' => 'lunas']) }}" 
                   class="btn btn-success btn-sm rounded-pill px-3">Diterima</a>
                   
                <a href="{{ route('customer.riwayat.pembayaran', ['status' => 'ditolak']) }}" 
                   class="btn btn-danger btn-sm rounded-pill px-3">Ditolak</a>
            </div>

            {{-- BAGIAN 3: TABEL DATA --}}
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Ref Booking</th>
                            <th>Tanggal Bayar</th>
                            <th>Jumlah</th>
                            <th>Bukti Transfer</th>
                            <th>Status & Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembayarans as $bayar)
                            {{-- LOGIKA WARNA BARIS --}}
                            @php
                                $warnaBaris = '';
                                if($bayar->statusPembayaran == 'lunas') {
                                    $warnaBaris = 'table-success'; // Hijau
                                } elseif($bayar->statusPembayaran == 'ditolak') {
                                    $warnaBaris = 'table-danger';  // Merah
                                } else {
                                    $warnaBaris = ''; // Putih (Default) biar bersih
                                }
                            @endphp

                            <tr class="{{ $warnaBaris }}">
                                <td>
                                    <strong>{{ $bayar->pemesanan->nomorBooking ?? '-' }}</strong>
                                </td>
                                <td>{{ $bayar->tanggalPembayaran }}</td>
                                <td>Rp {{ number_format($bayar->jumlahBayar, 0, ',', '.') }}</td>
                                
                                {{-- Kolom Bukti Gambar --}}
                                <td>
                                    @if($bayar->buktiPembayaran)
                                        <a href="{{ asset('gambarBuktiPembayaran/' . $bayar->buktiPembayaran) }}" 
                                           target="_blank" 
                                           class="btn btn-light btn-sm border">
                                            <i class="fa fa-image"></i> Lihat
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- Kolom Status & TOMBOL AKSI --}}
                                <td>
                                    {{-- Badge Status --}}
                                    <span class="badge 
                                        @if($bayar->statusPembayaran == 'lunas') bg-success 
                                        @elseif($bayar->statusPembayaran == 'ditolak') bg-danger 
                                        @else bg-secondary @endif">
                                        {{ strtoupper($bayar->statusPembayaran) }}
                                    </span>

                                    {{-- === LOGIKA TOMBOL UPLOAD ULANG === --}}
                                    @if($bayar->statusPembayaran == 'ditolak')
                                        <div class="mt-2">
                                            <small class="text-danger d-block mb-1 fw-bold">Bukti Ditolak!</small>
                                            
                                            {{-- Tombol Trigger Modal --}}
                                            <button type="button" class="btn btn-warning btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#modalUpload{{ $bayar->idPembayaran }}">
                                                <i class="fa fa-upload"></i> Upload Ulang
                                            </button>
                                        </div>

                                        {{-- === MODAL UPLOAD (POPUP) === --}}
                                        {{-- ID Modal harus unik pake ID Pembayaran --}}
                                        <div class="modal fade" id="modalUpload{{ $bayar->idPembayaran }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-warning text-dark">
                                                        <h5 class="modal-title">Perbaiki Bukti Pembayaran</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    
                                                    {{-- Form Upload --}}
                                                    <form action="{{ route('customer.pembayaran.updateBukti', $bayar->idPembayaran) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body text-dark text-start">
                                                            <div class="alert alert-info py-2" style="font-size: 0.9rem;">
                                                                <i class="fa fa-info-circle"></i> Silakan upload foto bukti transfer yang baru dan jelas.
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Pilih Foto Baru:</label>
                                                                <input type="file" name="buktiPembayaran" class="form-control" accept="image/*" required>
                                                                <small class="text-muted">Format: JPG, PNG. Maks: 2MB</small>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Kirim & Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- === END MODAL === --}}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    Belum ada riwayat pembayaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection