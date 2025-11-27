<?php

use App\Http\Controllers\Admin\kalenderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PemesananController;
use App\Http\Controllers\Admin\pembayaranController;
use App\Http\Controllers\AuthController;

// route bebas tanpa login
Route::middleware('guest')->group(function () {

    Route::get('/', function () {
        return view('login');
    })->name('login');

    Route::post('prosesLogin', [AuthController::class, 'prosesLogin'])->name('prosesLogin');
});

Route::get('prosesLogout', [AuthController::class, 'logout'])->name('logout');
// Periksa harus login dulu baru bisa logout
Route::middleware('auth')->group(function () {
});

// route group khusus admin
Route::middleware(['auth', 'cek_role:admin'])->group(function () {

    // Kalender
    Route::get('lihatKalenderJadwal', [kalenderController::class, 'index'])->name('kalenderJadwal');
    Route::get('ambilDataPaket', [kalenderController::class, 'getPaket'])->name('kalenderJadwal.ambilDataPaket');
    Route::get('ambilDataSlotJadwal', [kalenderController::class, 'getSlotJadwal'])->name('kalenderJadwal.ambilDataSlotJadwal');
    Route::post('simpanDataBooking', [kalenderController::class, 'simpanBooking'])->name('kalenderJadwal.simpanBooking');
    Route::get('dataPresentaseHarian', [kalenderController::class, 'getDataPresentaseBookingHarian'])->name('kalenderJadwal.getDataPresentaseHarian');

    // Pemesanan
    Route::get('lihatPemesanan', [PemesananController::class, 'index'])->name('booking.daftarPemesanan');
    Route::post('pemesanan/updateStatusBooking', [PemesananController::class, 'updateStatusBooking'])->name('booking.pemesanan.updateStatusBooking');
    Route::post('pemesanan/updateStatusPembayaran', [PemesananController::class, 'updateStatusPembayaran'])->name('booking.pemesanan.updateStatusPembayaran');

    // Pembayaran
    Route::get('lihatDataPembayaran', [pembayaranController::class, 'index'])->name('booking.dataPembayaran');
    Route::post('pembayaran/updateStatusPembayaran', [pembayaranController::class, 'updateStatusPembayaran'])->name('booking.pembayaran.updateStatusPembayaran');
});


// Route group khusus customer
Route::middleware(['auth', 'cek_role:customer'])->group(function () {
    Route::get('tampilanCustomer', function () {
        return view('customer.tampilanCustomer');
    })->name('tampilanCustomer');
});
