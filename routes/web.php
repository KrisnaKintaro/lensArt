<?php

use App\Http\Controllers\Admin\kalenderController;
use App\Http\Controllers\Admin\kelolaPortofolioController;
use App\Http\Controllers\Admin\kelolaAkunCustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PemesananController;
use App\Http\Controllers\Admin\pembayaranController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\customer\dashboard_awal_controller;

// route bebas tanpa login
Route::middleware('guest')->group(function () {

    Route::get('/', [dashboard_awal_controller::class,'tampilan_opening'])->name('tampilan_opening'); 

    Route::post('prosesLogin', [AuthController::class, 'prosesLogin'])->name('prosesLogin');
});

Route::get('prosesLogout', [AuthController::class, 'logout'])->name('logout');

// Periksa harus login dulu baru bisa logout
Route::middleware('auth')->group(function () {});

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

    // Kalendar booking
    Route::get('lihatKalenderJadwal', [kalenderController::class, 'index'])->name('kalenderJadwal');
    Route::get('ambilDataPaket', [kalenderController::class, 'getPaket'])->name('kalenderJadwal.ambilDataPaket');
    Route::get('ambilDataSlotJadwal', [kalenderController::class, 'getSlotJadwal'])->name('kalenderJadwal.ambilDataSlotJadwal');
    Route::post('simpanDataBooking', [kalenderController::class, 'simpanBooking'])->name('kalenderJadwal.simpanBooking');
    Route::get('dataPresentaseHarian', [kalenderController::class, 'getDataPresentaseBookingHarian'])->name('kalenderJadwal.getDataPresentaseHarian');

    // Kelola Akun
    Route::get('lihatDataAkunCustomer', [kelolaAkunCustomerController::class, 'index'])->name('kelolaAkunCustomer');
    Route::post('tambahDataCustomer', [kelolaAkunCustomerController::class, 'tambahData'])->name('kelolaAkunCustomer.tambahData');
    Route::get('ambilDataEdit/{idUser}', [kelolaAkunCustomerController::class, 'ambilDataEdit'])->name('kelolaAkunCustomer.ambilDataEdit');
    Route::post('updateDataUser/{idUser}', [kelolaAkunCustomerController::class, 'editData'])->name('kelolaAkunCustomer.editDataUser');
    Route::delete('deleteDataUser/{idUser}', [kelolaAkunCustomerController::class, 'hapusData'])->name('kelolaAkunCustomer.hapusData');

    // Pembayaran
    Route::get('lihatDataPembayaran', [pembayaranController::class, 'index'])->name('booking.dataPembayaran');
    Route::post('pembayaran/updateStatusPembayaran', [pembayaranController::class, 'updateStatusPembayaran'])->name('booking.pembayaran.updateStatusPembayaran');
});

// route group khusus customer 
Route::middleware(['auth','cek_role:admin'])->group(function(){

});

























































































































































Route::get('/portofolio', [kelolaPortofolioController::class, 'index'])->name('portofolio.index');
Route::get('/portofolio/create', [kelolaPortofolioController::class, 'create'])->name('portofolio.create');
Route::post('/portofolio', [kelolaPortofolioController::class, 'store'])->name('portofolio.store');
Route::get('/portofolio/{id}/edit', [kelolaPortofolioController::class, 'edit'])->name('portofolio.edit');
Route::put('/portofolio/{id}', [kelolaPortofolioController::class, 'update'])->name('portofolio.update');
Route::delete('/portofolio/{id}', [kelolaPortofolioController::class, 'destroy'])->name('portofolio.destroy');