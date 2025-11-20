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
        Schema::create('portofolio', function (Blueprint $table) {
            $table->id('idPortofolio');
            $table->foreignId('idJenisLayanan')->constrained('jenisLayanan','idJenisLayanan');
            $table->string('namaPortofolio');
            $table->text('deskripsi')->nullable();
            $table->string('urlPorto'); //lokasi penyimpanan portofoliio foto / video
            $table->enum('jenisPorto',['foto','video'])->default('foto');
            $table->date('tanggalPorto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portofolio');
    }
};
