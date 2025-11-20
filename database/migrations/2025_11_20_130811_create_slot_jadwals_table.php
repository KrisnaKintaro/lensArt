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
        Schema::create('slotJadwal', function (Blueprint $table) {
            $table->id('idSlotJadwal');
            $table->foreignId('idJenisLayanan')->constrained('jenisLayanan','idJenisLayanan')->cascadeOnDelete();
            $table->foreignId('idPaketLayanan')->constrained('paketLayanan','idPaketLayanan')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('jamMulai');
            $table->time('jamSelesai')->nullable();
            $table->enum('status',['kosong','terpesan'])->default('kosong');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['idJenisLayanan','idPaketLayanan','tanggal','jamMulai'],'slotUnikPerSlotJadwal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slotJadwal');
    }
};
