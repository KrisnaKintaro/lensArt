<?php

use App\Http\Controllers\Admin\kalenderController;
use App\Http\Controllers\Admin\kelolaAkunCustomerController;
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

Route::get('lihatKalenderJadwal',[kalenderController::class, 'index'])->name('kalenderJadwal');
Route::get('ambilDataPaket',[kalenderController::class, 'getPaket'])->name('kalenderJadwal.ambilDataPaket');
Route::get('ambilDataSlotJadwal',[kalenderController::class, 'getSlotJadwal'])->name('kalenderJadwal.ambilDataSlotJadwal');
Route::post('simpanDataBooking',[kalenderController::class, 'simpanBooking'])->name('kalenderJadwal.simpanBooking');
Route::get('dataPresentaseHarian',[kalenderController::class, 'getDataPresentaseBookingHarian'])->name('kalenderJadwal.getDataPresentaseHarian');














Route::get('lihatDataAkunCustomer',[kelolaAkunCustomerController::class, 'index'])->name('kelolaAkunCustomer');
Route::post('tambahDataCustomer',[kelolaAkunCustomerController::class, 'tambahData'])->name('kelolaAkunCustomer.tambahData');
Route::get('ambilDataEdit/{idUser}',[kelolaAkunCustomerController::class, 'ambilDataEdit'])->name('kelolaAkunCustomer.ambilDataEdit');
Route::post('updateDataUser/{idUser}',[kelolaAkunCustomerController::class, 'editData'])->name('kelolaAkunCustomer.editDataUser');
Route::delete('deleteDataUser/{idUser}',[kelolaAkunCustomerController::class, 'hapusData'])->name('kelolaAkunCustomer.hapusData');
