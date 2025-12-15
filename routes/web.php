<?php

use App\Http\Controllers\Admin\kalenderController;
use App\Http\Controllers\Admin\kelolaPortofolioController;
use App\Http\Controllers\Admin\kelolaAkunCustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PemesananController;
use App\Http\Controllers\Admin\pembayaranController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\bookingController;
use App\Http\Controllers\customer\dashboard_awal_controller;
use App\Http\Controllers\Customer\dashboardAwalController;
use App\Http\Controllers\PortofolioController;
use App\Http\Controllers\Customer\JenisLayananController;
use App\Http\Controllers\Admin\KelolaJenisLayananController;
use App\Http\Controllers\Admin\KelolaPaketLayananController;
use App\Http\Controllers\Admin\laporanPendapatanController;
use App\Http\Controllers\Admin\laporanPermintaanController;

// ------------------------------------------
// ROUTE BEBAS AKSES (TIDAK PERLU LOGIN)
// ------------------------------------------
Route::get('/', [dashboardAwalController::class, 'tampilan_opening'])->name('tampilan_opening');
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

Route::get('prosesLogout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

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

    // Kelola Portofolio
    Route::get('/portofolio', [kelolaPortofolioController::class, 'index'])->name('portofolio.index');
    Route::get('/portofolio/create', [kelolaPortofolioController::class, 'create'])->name('portofolio.create');
    Route::post('/portofolio', [kelolaPortofolioController::class, 'store'])->name('portofolio.store');
    Route::get('/portofolio/{id}/edit', [kelolaPortofolioController::class, 'edit'])->name('portofolio.edit');
    Route::put('/portofolio/{id}', [kelolaPortofolioController::class, 'update'])->name('portofolio.update');
    Route::delete('/portofolio/{id}', [kelolaPortofolioController::class, 'destroy'])->name('portofolio.destroy');

    // Kelola Jenis layanan
    Route::get('/jenis-layanan', [KelolaJenisLayananController::class, 'index'])->name('jenisLayanan.index');
    Route::get('/jenis-layanan/create', [KelolaJenisLayananController::class, 'create'])->name('jenisLayanan.create');
    Route::post('/jenis-layanan', [KelolaJenisLayananController::class, 'store'])->name('jenisLayanan.store');
    Route::get('/jenis-layanan/{id}/edit', [KelolaJenisLayananController::class, 'edit'])->name('jenisLayanan.edit');
    Route::put('/jenis-layanan/{id}', [KelolaJenisLayananController::class, 'update'])->name('jenisLayanan.update');
    Route::delete('/jenis-layanan/{id}', [KelolaJenisLayananController::class, 'destroy'])->name('jenisLayanan.destroy');

    // kelola paket layanan
    Route::get('/paket-layanan', [KelolaPaketLayananController::class, 'index'])->name('paketLayanan.index');
    Route::post('/paket-layanan', [KelolaPaketLayananController::class, 'store'])->name('paketLayanan.store');
    Route::get('/paket-layanan/{id}/edit', [KelolaPaketLayananController::class, 'edit'])->name('paketLayanan.edit');
    Route::put('/paket-layanan/{id}', [KelolaPaketLayananController::class, 'update'])->name('paketLayanan.update');
    Route::delete('/paket-layanan/{id}', [KelolaPaketLayananController::class, 'destroy'])->name('paketLayanan.destroy');

    // Pembayaran
    Route::get('laporan/lihatDataPembayaran', [pembayaranController::class, 'index'])->name('booking.dataPembayaran');
    Route::post('pembayaran/updateStatusPembayaran', [pembayaranController::class, 'updateStatusPembayaran'])->name('booking.pembayaran.updateStatusPembayaran');

    // Laporan pendapatan
    Route::get('laporan/lihatLaporanPendapatan', [laporanPendapatanController::class, 'index'])->name('laporan.pendapatan');

    // Laporan permintaan
    Route::get('laporan/lihatLaporanPermintaan', [laporanPermintaanController::class, 'index'])->name('laporan.permintaan');
});


// ------------------------------------------
// ROUTE KHUSUS CUSTOMER
// ------------------------------------------
Route::middleware(['auth', 'cek_role:customer'])->group(function () {

    // Contoh Route Khusus Customer
    Route::get('/customer/profil', function () {
        return view('customer.profil');
    })->name('customer.profil');

    Route::get('tampilanBookingCustomer', [bookingController::class, 'index'])->name('tampilanBookingCustomer');

    Route::get('ambilDataPaketVersiCustomer', [bookingController::class, 'getPaket'])->name('bookingCustomer.ambilDataPaket');
    Route::get('ambilDataSlotJadwalVersiCustomer', [bookingController::class, 'getSlotJadwal'])->name('bookingCustomer.ambilDataSlotJadwal');
    Route::post('simpanDataBookingVersiCustomer', [bookingController::class, 'simpanBooking'])->name('bookingCustomer.simpanBooking');
    Route::get('dataPresentaseHarianVersiCustomer', [bookingController::class, 'getDataPresentaseBookingHarian'])->name('bookingCustomer.getDataPresentaseHarian');
});
