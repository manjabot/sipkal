<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            // role: admin (administrator sistem), admin_tu (Staf Tata Usaha), kepala_sekolah, waka, bendahara, siswa, industri, tamu (pengirim surat eksternal)
            $table->enum('role', ['admin', 'admin_tu', 'kepala_sekolah', 'waka', 'bendahara', 'siswa', 'industri', 'tamu'])
                ->default('admin_tu');
            // status_akun: aktif langsung bisa login; menunggu_persetujuan & ditolak khusus akun Tamu yang mendaftar sendiri
            $table->enum('status_akun', ['aktif', 'menunggu_persetujuan', 'ditolak'])->default('aktif');
            $table->string('jabatan')->nullable()->comment('Label jabatan yang tampil di header, mis. Staf Tata Usaha / Waka Kurikulum');
            $table->string('instansi_asal')->nullable()->comment('Diisi jika role = tamu, nama instansi/keperluan pengirim eksternal');
            $table->string('unit')->nullable()->comment('Nama unit terkait untuk role waka/bendahara, dipakai sbg tujuan disposisi');
            $table->foreignId('industri_id')->nullable()->comment('Diisi jika role = industri, merujuk ke tabel industris');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_requests', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->text('catatan')->nullable();
            $table->enum('status', ['baru', 'selesai'])->default('baru');
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
