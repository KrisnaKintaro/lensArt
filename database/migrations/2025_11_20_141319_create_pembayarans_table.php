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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('idPembayaran');
            $table->foreignId('idPemesanan')->constrained('pemesanan','idPemesanan')->cascadeOnDelete();
            $table->decimal('jumlahBayar', 15, 2)->default(0);
            $table->enum('metodePembayaran',['transferBank','eWallet','tunai'])->default('transferBank');
            $table->enum('statusPembayaran',['menunggu','dp','lunas','ditolak'])->default('menunggu');
            $table->string('buktiPembayaran')->nullable();
            $table->date('tanggalPembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
