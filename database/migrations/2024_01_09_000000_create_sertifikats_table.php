<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sertifikats', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_sertifikat')->unique()->comment('Dibuat otomatis, format B/{kode}/{urut}/I-SMKN4/{tahun}');
            $table->string('jenis_kegiatan')->comment('mis. Peserta Lomba LKS, Narasumber Webinar, Panitia Kegiatan');
            $table->string('nama_penerima');
            $table->string('identitas_penerima')->nullable()->comment('mis. NIS siswa / NIP guru / instansi asal');
            $table->string('predikat')->nullable();
            $table->text('keterangan')->nullable()->comment('Kalimat penjelas di badan sertifikat');
            $table->date('tanggal_terbit');
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikats');
    }
};
