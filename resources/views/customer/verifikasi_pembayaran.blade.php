@extends('layouts.master_frontend')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-4">Verifikasi Pembayaran</h1>
    <h3 class="text-center text-warning mb-5">Pesanan ID #{{ $pemesanan->id }}</h3>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-dark text-white border-warning p-4">
                
                <h5 class="text-warning">Detail Paket</h5>
                <p><strong>Nama Paket:</strong> {{ $pemesanan->paketLayanan->nama_paket ?? 'N/A' }}</p>
                <p><strong>Tanggal Acara:</strong> {{ \Carbon\Carbon::parse($pemesanan->tanggal_acara)->format('d F Y') }}</p>
                <p><strong>Total Biaya:</strong> Rp {{ number_format($pemesanan->total_biaya, 0, ',', '.') }}</p>
                
                <hr class="border-secondary my-4">

                <h5 class="text-warning">Status Pembayaran Admin</h5>
                <p class="mb-3">
                    Status Saat Ini: 
                    <span class="badge {{ $pemesanan->status_pembayaran == 'Diterima' ? 'bg-success' : ($pemesanan->status_pembayaran == 'Ditolak' ? 'bg-danger' : 'bg-warning') }}">
                        {{ $pemesanan->status_pembayaran }}
                    </span>
                </p>

                @if($pemesanan->status_pembayaran != 'Diterima')
                    <h5 class="text-warning mt-4">Upload Ulang Bukti Pembayaran</h5>
                    <div class="alert alert-info">
                        @if($pemesanan->status_pembayaran == 'Ditolak')
                            Pembayaran Anda sebelumnya **Ditolak**. Silakan unggah bukti baru.
                        @else
                            Anda dapat unggah ulang jika terjadi kesalahan.
                        @endif
                    </div>
                    
                    <form action="{{ route('customer.uploadBuktiBayar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="pemesanan_id" value="{{ $pemesanan->id }}">
                        
                        <div class="mb-3">
                            <label for="bukti_pembayaran" class="form-label">Pilih File Bukti Baru (Max 2MB)</label>
                            <input class="form-control" type="file" id="bukti_pembayaran" name="bukti_pembayaran" required>
                            @error('bukti_pembayaran')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-warning w-100">Upload Bukti Baru</button>
                    </form>
                @else
                    <div class="alert alert-success mt-4">
                        Pembayaran telah **Diterima (ACC)** oleh admin.
                    </div>
                @endif
                
                <a href="{{ route('customer.riwayatBooking') }}" class="btn btn-outline-secondary mt-4">Kembali ke Riwayat Booking</a>

            </div>
        </div>
    </div>
</div>
@endsection