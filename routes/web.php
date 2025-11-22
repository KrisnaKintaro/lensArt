<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PemesananController;
use App\Http\Controllers\Admin\pembayaranController;

Route::get('/', function () {
    return view('admin.masterAdmin');
});

Route::get('lihatPemesanan',[PemesananController::class, 'index'])->name('booking.daftarPemesanan');
Route::post('pemesanan/updateStatusBooking',[PemesananController::class, 'updateStatusBooking'])->name('booking.pemesanan.updateStatusBooking');
Route::post('pemesanan/updateStatusPembayaran',[PemesananController::class, 'updateStatusPembayaran'])->name('booking.pemesanan.updateStatusPembayaran');

Route::get('lihatDataPembayaran',[pembayaranController::class, 'index'])->name('booking.dataPembayaran');
Route::post('pembayaran/updateStatusPembayaran',[pembayaranController::class, 'updateStatusPembayaran'])->name('booking.pembayaran.updateStatusPembayaran');
