@extends('layouts.master_frontend')

@section('title', 'Formulir Pemesanan')

@section('content')

<div class="container py-5">
    <h2 class="text-center text-white mb-5">Formulir Pemesanan Layanan</h2>
    
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card bg-dark text-white border-warning p-4 shadow-lg">
                
                <div class="mb-4 p-3 rounded" style="background-color: #383433;">
                    <h4 class="text-warning mb-1">Paket yang Dipilih: {{ $paket->namaPaket ?? 'Belum Dipilih' }}</h4>
                    <p class="mb-1 small text-light">{{ $paket->deskripsi ?? 'Detail paket akan muncul di sini.' }}</p>
                    <h3 class="text-success fw-bold mt-2">Rp. {{ number_format($paket->harga ?? 0, 0, ',', '.') }}</h3>
                    
                    {{-- Hidden inputs wajib --}}
                    <input type="hidden" name="idPaketLayanan" value="{{ $paket->idPaketLayanan ?? '' }}">
                </div>
                
                {{-- FORM BOOKING --}}
                <form action="{{ route('booking.store') }}" method="POST">
                    @csrf
                    
                    {{-- Hidden inputs wajib dari user session dan harga --}}
                    <input type="hidden" name="idUser" value="{{ Auth::id() }}">
                    <input type="hidden" name="totalHarga" value="{{ $paket->harga ?? '' }}">


                    <h5 class="text-white mt-4 mb-3"><i class="fas fa-calendar-alt me-2"></i> Jadwal & Lokasi Acara</h5>
                    
                    {{-- 2. PEMILIHAN TANGGAL --}}
                    <div class="mb-4">
                        <label for="tanggalPemesanan" class="form-label text-light">Pilih Tanggal Acara <span class="text-danger">*</span></label>
                        {{-- Field ini memerlukan JS/Ajax untuk memfilter ketersediaan --}}
                        <input type="date" id="tanggalPemesanan" name="tanggalPemesanan" class="form-control bg-secondary text-white border-0" required>
                    </div>

                    {{-- 3. SLOT WAKTU (Akan diisi AJAX) --}}
                    <div class="mb-4" id="slotJadwalArea">
                        <label for="idSlotJadwal" class="form-label text-light">Pilih Slot Waktu <span class="text-danger">*</span></label>
                        <select id="idSlotJadwal" name="idSlotJadwal" class="form-select bg-secondary text-white border-0" required>
                            <option value="">Pilih Slot (Pilih tanggal dahulu)</option>
                        </select>
                    </div>
                    
                    {{-- 4. LOKASI ACARA --}}
                    <div class="mb-4">
                        <label for="lokasiAcara" class="form-label text-light">Lokasi Acara <span class="text-danger">*</span></label>
                        <input type="text" id="lokasiAcara" name="lokasiAcara" class="form-control bg-secondary text-white border-0" placeholder="Contoh: Gedung Serbaguna Kota Bandung" required>
                    </div>

                    {{-- 5. CATATAN KHUSUS --}}
                    <div class="mb-4">
                        <label for="catatan" class="form-label text-light">Catatan / Permintaan Khusus</label>
                        <textarea id="catatan" name="catatan" class="form-control bg-secondary text-white border-0" rows="3" placeholder="Contoh: Fokus pada foto keluarga di awal."></textarea>
                    </div>

                    {{-- 6. METODE PEMBAYARAN --}}
                    <div class="mb-5">
                        <label for="metodePembayaran" class="form-label text-light">Metode Pembayaran <span class="text-danger">*</span></label>
                        <select id="metodePembayaran" name="metodePembayaran" class="form-select bg-secondary text-white border-0" required>
                            <option value="" disabled selected>Pilih Metode Pembayaran</option>
                            <option value="transferBank">Transfer Bank</option>
                            <option value="eWallet">E-Wallet</option>
                            <option value="tunai">Tunai</option>
                        </select>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning btn-lg fw-bold">Konfirmasi Pemesanan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    {{-- Diperlukan JQuery/JS/Ajax untuk: 
    1. Membatasi tanggal yang bisa dipilih.
    2. Mengambil slot jadwal (idSlotJadwal) yang tersedia setelah tanggal dipilih. --}}
@endsection