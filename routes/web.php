<?php

use App\Http\Controllers\Admin\kalenderController;
use App\Http\Controllers\Admin\kelolaAkunCustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PemesananController;
use App\Http\Controllers\Admin\pembayaranController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\customer\dashboard_awal_controller;
use App\Http\Controllers\Customer\dashboardAwalController;
use App\Http\Controllers\PortofolioController;
use App\Http\Controllers\Customer\JenisLayananController;


// ------------------------------------------
// ROUTE BEBAS AKSES (TIDAK PERLU LOGIN)
// ------------------------------------------
Route::middleware('guest')->group(function () {

    // Halaman Landing Page (Opening)
    Route::get('/', [dashboardAwalController::class,'tampilan_opening'])->name('tampilan_opening'); 

    // Halaman Portofolio
    Route::get('/portofolio/event', [dashboardAwalController::class, 'tampilPortofolio'])->name('portofolio.event'); 
    Route::get('/portofolio/filter/{id}', [dashboardAwalController::class, 'filter'])->name('filterPorto');

    // Layanan
    Route::get('/layanan', [JenisLayananController::class, 'index'])->name('layanan.index');

    // Route Autentikasi (GET)
    Route::get('/login', [AuthController::class, 'tampilkanFormLogin'])->name('login'); 
    Route::get('/register', [AuthController::class, 'tampilkanFormRegister'])->name('register'); 
    
    // Route Autentikasi (POST)
    Route::post('prosesLogin', [AuthController::class, 'prosesLogin'])->name('prosesLogin');
    
    // ** ROUTE PROSES SUBMIT REGISTER **
    Route::post('/register', [AuthController::class, 'prosesRegister'])->name('register.post');

    // --- CATATAN: ROUTE /dashboard YANG TERDUPLIKASI TELAH DIHAPUS DARI SINI ---
});


// Route Logout (Perlu Autentikasi)
Route::get('prosesLogout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ------------------------------------------
// ROUTE BERSAMA (Perlu Login untuk Booking & Dashboard)
// ------------------------------------------
Route::middleware('auth')->group(function () {
    
    // Dashboard Customer (DIPASTIKAN HANYA ADA DI SINI)
    // Ini adalah tujuan redirect setelah Login/Register
    Route::get('/dashboard', [dashboardAwalController::class, 'index'])->name('dashboard');
    
    // **ROUTE BOOKING**
    Route::get('/booking/create', [PortofolioController::class, 'createBooking'])->name('booking.create'); 
    Route::post('/booking', [PortofolioController::class, 'storeBooking'])->name('booking.store');
    
    
});


// ROUTE KHUSUS ADMIN
// ------------------------------------------
Route::middleware(['auth', 'cek_role:admin'])->group(function () {

    // Kalender dan Slot Jadwal
    Route::get('lihatKalenderJadwal', [kalenderController::class, 'index'])->name('kalenderJadwal');
    Route::get('ambilDataPaket', [kalenderController::class, 'getPaket'])->name('kalenderJadwal.ambilDataPaket');
    Route::get('ambilDataSlotJadwal', [kalenderController::class, 'getSlotJadwal'])->name('kalenderJadwal.ambilDataSlotJadwal');
    Route::post('simpanDataBooking', [kalenderController::class, 'simpanBooking'])->name('kalenderJadwal.simpanBooking');
    Route::get('dataPresentaseHarian', [kalenderController::class, 'getDataPresentaseBookingHarian'])->name('kalenderJadwal.getDataPresentaseHarian');

    // Pemesanan
    Route::get('lihatPemesanan', [PemesananController::class, 'index'])->name('booking.daftarPemesanan');
    Route::post('pemesanan/updateStatusBooking', [PemesananController::class, 'updateStatusBooking'])->name('booking.pemesanan.updateStatusBooking');
    Route::post('pemesanan/updateStatusPembayaran', [PemesananController::class, 'updateStatusPembayaran'])->name('booking.pemesanan.updateStatusPembayaran');

    // Kelola Akun Customer
    Route::get('lihatDataAkunCustomer', [kelolaAkunCustomerController::class, 'index'])->name('kelolaAkunCustomer');
    Route::post('tambahDataCustomer', [kelolaAkunCustomerController::class, 'tambahData'])->name('kelolaAkunCustomer.tambahData');
    Route::get('ambilDataEdit/{idUser}', [kelolaAkunCustomerController::class, 'ambilDataEdit'])->name('kelolaAkunCustomer.ambilDataEdit');
    Route::post('updateDataUser/{idUser}', [kelolaAkunCustomerController::class, 'editData'])->name('kelolaAkunCustomer.editDataUser');
    Route::delete('deleteDataUser/{idUser}', [kelolaAkunCustomerController::class, 'hapusData'])->name('kelolaAkunCustomer.hapusData');

    // Pembayaran
    Route::get('lihatDataPembayaran', [pembayaranController::class, 'index'])->name('booking.dataPembayaran');
    Route::post('pembayaran/updateStatusPembayaran', [pembayaranController::class, 'updateStatusPembayaran'])->name('booking.pembayaran.updateStatusPembayaran');
});


// ------------------------------------------
// ROUTE KHUSUS CUSTOMER
// ------------------------------------------
Route::middleware(['auth','cek_role:customer'])->group(function(){
    
    // Contoh Route Khusus Customer
    Route::get('/customer/profil', function() {
        return view('customer.profil');
    })->name('customer.profil');
    
    // Route untuk melihat riwayat booking
    Route::get('/customer/riwayat-booking', [PortofolioController::class, 'riwayatBooking'])->name('customer.riwayatBooking');
});