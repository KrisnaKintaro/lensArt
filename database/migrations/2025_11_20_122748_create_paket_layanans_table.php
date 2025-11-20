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
        Schema::create('paketLayanan', function (Blueprint $table) {
            $table->id('idPaketLayanan');
            $table->foreignId('idJenisLayanan')->constrained('jenisLayanan','idJenisLayanan')->cascadeOnDelete();
            $table->string('namaPaket');
            $table->text('deskripsi')->nullable();
            $table->integer('jumlahFileEdit')->nullable();
            $table->integer('durasiJam')->nullable();
            $table->decimal('harga', 15, 2)->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paketLayanan');
    }
};
