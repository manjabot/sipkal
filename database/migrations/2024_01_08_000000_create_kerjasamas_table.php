<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kerjasamas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('industri_id')->constrained('industris')->cascadeOnDelete();
            $table->string('nomor_dokumen')->unique()->comment('Nomor dokumen MoU/PKS, dibuat otomatis');
            $table->string('bidang')->comment('mis. Instansi Pemerintahan, Telekomunikasi & TI');
            $table->text('ruang_lingkup')->nullable()->comment('Ruang lingkup kerjasama, mis. Penempatan PKL & Rekrutmen Lulusan');
            $table->date('mulai_mou');
            $table->date('berakhir_mou');
            $table->string('dokumen_mou')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kerjasamas');
    }
};
