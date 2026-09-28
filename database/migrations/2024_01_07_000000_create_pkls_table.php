<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pkls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->foreignId('industri_id')->constrained('industris')->cascadeOnDelete();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            // status: menunggu_persetujuan, berlangsung, selesai
            $table->enum('status', ['menunggu_persetujuan', 'berlangsung', 'selesai'])->default('menunggu_persetujuan');
            $table->string('predikat')->nullable()->comment('mis. Sangat Baik, Baik');
            $table->string('nomor_sertifikat')->nullable()->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pkls');
    }
};
