<?php

use Illuminate\Support\Facades\Route;

// ====================================================
// IMPORT CONTROLLERS
// ====================================================

// --- AUTH & UMUM ---
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PortofolioController;

// --- ADMIN ---
use App\Http\Controllers\Admin\kalenderController;
use App\Http\Controllers\Admin\kelolaAkunCustomerController;
use App\Http\Controllers\Admin\PemesananController;
use App\Http\Controllers\Admin\pembayaranController;

// --- CUSTOMER ---
use App\Http\Controllers\Customer\dashboardAwalController;
use App\Http\Controllers\Customer\JenisLayananController;
use App\Http\Controllers\Customer\bookingController;
use App\Http\Controllers\Customer\CustomerTransaksiController; // Controller Transaksi Customer

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ====================================================
// 1. ROUTE PUBLIK (BEBAS AKSES / TANPA LOGIN)
// ====================================================

// Landing Page & Portofolio
Route::get('/', [dashboardAwalController::class, 'tampilan_opening'])->name('tampilan_opening');
Route::get('/portofolio/event', [dashboardAwalController::class, 'tampilPortofolio'])->name('portofolio.event');
Route::get('/portofolio/filter/{id}', [dashboardAwalController::class, 'filter'])->name('filterPorto');

// Halaman Layanan
Route::get('/layanan', [JenisLayananController::class, 'index'])->name('layanan.index');

// Autentikasi (Login & Register)
Route::get('/login', [AuthController::class, 'tampilkanFormLogin'])->name('login');
Route::post('prosesLogin', [AuthController::class, 'prosesLogin'])->name('prosesLogin');
Route::get('/register', [AuthController::class, 'tampilkanFormRegister'])->name('register');
Route::post('/register', [AuthController::class, 'prosesRegister'])->name('register.post');


// ====================================================
// 2. ROUTE YANG BUTUH LOGIN (AUTH)
// ====================================================
Route::middleware(['auth'])->group(function () {
    
    // Logout (Bisa diakses Admin maupun Customer)
    Route::get('prosesLogout', [AuthController::class, 'logout'])->name('logout');


    // --------------------------------------------------
    // A. GROUP KHUSUS ADMIN
    // --------------------------------------------------
    Route::middleware(['cek_role:admin'])->group(function () {

        // Kalender & Slot Jadwal
        Route::get('lihatKalenderJadwal', [kalenderController::class, 'index'])->name('kalenderJadwal');
        Route::get('ambilDataPaket', [kalenderController::class, 'getPaket'])->name('kalenderJadwal.ambilDataPaket');
        Route::get('ambilDataSlotJadwal', [kalenderController::class, 'getSlotJadwal'])->name('kalenderJadwal.ambilDataSlotJadwal');
        Route::post('simpanDataBooking', [kalenderController::class, 'simpanBooking'])->name('kalenderJadwal.simpanBooking');
        Route::get('dataPresentaseHarian', [kalenderController::class, 'getDataPresentaseBookingHarian'])->name('kalenderJadwal.getDataPresentaseHarian');

        // Manajemen Pemesanan
        Route::get('lihatPemesanan', [PemesananController::class, 'index'])->name('booking.daftarPemesanan');
        Route::post('pemesanan/updateStatusBooking', [PemesananController::class, 'updateStatusBooking'])->name('booking.pemesanan.updateStatusBooking');
        Route::post('pemesanan/updateStatusPembayaran', [PemesananController::class, 'updateStatusPembayaran'])->name('booking.pemesanan.updateStatusPembayaran');

        // Kelola Akun Customer
        Route::get('lihatDataAkunCustomer', [kelolaAkunCustomerController::class, 'index'])->name('kelolaAkunCustomer');
        Route::post('tambahDataCustomer', [kelolaAkunCustomerController::class, 'tambahData'])->name('kelolaAkunCustomer.tambahData');
        Route::get('ambilDataEdit/{idUser}', [kelolaAkunCustomerController::class, 'ambilDataEdit'])->name('kelolaAkunCustomer.ambilDataEdit');
        Route::post('updateDataUser/{idUser}', [kelolaAkunCustomerController::class, 'editData'])->name('kelolaAkunCustomer.editDataUser');
        Route::delete('deleteDataUser/{idUser}', [kelolaAkunCustomerController::class, 'hapusData'])->name('kelolaAkunCustomer.hapusData');

        // Manajemen Pembayaran
        Route::get('lihatDataPembayaran', [pembayaranController::class, 'index'])->name('booking.dataPembayaran');
        Route::post('pembayaran/updateStatusPembayaran', [pembayaranController::class, 'updateStatusPembayaran'])->name('booking.pembayaran.updateStatusPembayaran');
    });


    // --------------------------------------------------
    // B. GROUP KHUSUS CUSTOMER
    // --------------------------------------------------
    Route::middleware(['cek_role:customer'])->group(function () {

        // Dashboard / Profil Customer
        Route::get('/customer/profil', function () {
            return view('customer.profil');
        })->name('customer.profil');

        // Fitur Booking (Formulir & AJAX untuk Kalender)
        Route::get('tampilanBookingCustomer',[bookingController::class, 'index'])->name('tampilanBookingCustomer');
        Route::get('ambilDataPaketVersiCustomer', [bookingController::class, 'getPaket'])->name('bookingCustomer.ambilDataPaket');
        Route::get('ambilDataSlotJadwalVersiCustomer', [bookingController::class, 'getSlotJadwal'])->name('bookingCustomer.ambilDataSlotJadwal');
        Route::post('simpanDataBookingVersiCustomer', [bookingController::class, 'simpanBooking'])->name('bookingCustomer.simpanBooking');
        Route::get('dataPresentaseHarianVersiCustomer', [bookingController::class, 'getDataPresentaseBookingHarian'])->name('bookingCustomer.getDataPresentaseHarian');

        // ==========================================
        // FITUR TRANSAKSI & RIWAYAT (Customer)
        // ==========================================
        Route::prefix('customer')->name('customer.')->group(function () {
            
            // 1. Riwayat Booking
            Route::get('/riwayat-booking', [CustomerTransaksiController::class, 'riwayatBooking'])
                ->name('riwayat.booking');
            
            // 2. Batalkan Booking (Hanya jika status pending)
            Route::post('/booking/cancel/{id}', [CustomerTransaksiController::class, 'cancelBooking'])
                ->name('booking.cancel');

            // 3. Riwayat Pembayaran (Lihat Status)
            Route::get('/riwayat-pembayaran', [CustomerTransaksiController::class, 'riwayatPembayaran'])
                ->name('riwayat.pembayaran');

            // 4. Update Bukti Pembayaran (Fitur Upload Ulang jika Ditolak) - INI YANG BARU
            Route::post('/pembayaran/update-bukti/{id}', [CustomerTransaksiController::class, 'updateBuktiPembayaran'])
                ->name('pembayaran.updateBukti');
        });

    });

});