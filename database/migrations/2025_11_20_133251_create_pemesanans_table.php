<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id('idPemesanan');
            $table->foreignId('idUser')->constrained('user','idUser');
            $table->foreignId('idSlotJadwal')->constrained('slotJadwal','idSlotJadwal');
            $table->date('tanggalPemesanan')->useCurrent();
            $table->string('lokasiAcara')->nullable();
            $table->text('catatan');
            $table->enum('statusPemesanan',['pending','disetujui','selesai','dibatalkan'])->default('pending');
            $table->enum('metodePembayaran',['transferBank','eWallet','tunai'])->default('transferBank');
            $table->enum('statusPembayaran',['menunggu','dp','lunas','ditolak'])->default('menunggu');
            $table->string('buktiPembayaran')->nullable();
            $table->decimal('totalHarga',15,2)->default(0);
            $table->date('tanggalPembayaran')->nullable();
            $table->string('nomorBooking')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};
