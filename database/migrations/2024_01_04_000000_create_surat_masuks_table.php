<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_masuks', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_agenda')->unique()->comment('Dibuat otomatis oleh sistem, format SM-001');
            $table->string('nomor_surat')->nullable()->comment('Nomor surat asli dari pengirim');
            $table->string('pengirim');
            $table->string('perihal');
            $table->date('tanggal_terima');
            $table->string('file_path')->nullable();
            // status: menunggu_disposisi, sudah_didisposisi
            $table->enum('status', ['menunggu_disposisi', 'sudah_didisposisi'])->default('menunggu_disposisi');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_masuks');
    }
};
