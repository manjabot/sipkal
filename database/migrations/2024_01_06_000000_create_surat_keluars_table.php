<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_keluars', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique()->comment('Dibuat otomatis, format 421.7/{urut}/SMKN4-TPI/{tahun}');
            $table->string('jenis_surat')->default('lainnya')->comment('undangan, permohonan, keterangan, pemberitahuan, lainnya');
            $table->string('perihal');
            $table->string('tujuan');
            $table->longText('isi_surat')->nullable()->comment('Isi lengkap surat untuk dicetak sebagai dokumen resmi');
            $table->date('tanggal_surat');
            $table->string('file_path')->nullable();
            // status: draft, menunggu_acc, disetujui, dikembalikan
            $table->enum('status', ['draft', 'menunggu_acc', 'disetujui', 'dikembalikan'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('catatan_kepsek')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keluars');
    }
};
