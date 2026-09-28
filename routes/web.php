<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\KerjasamaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PklController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\TamuApprovalController;
use App\Http\Controllers\TamuSuratController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt')->middleware('throttle:5,1'); 
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register'])->name('register.attempt');
    Route::get('/lupa-password', [AuthController::class, 'showForgot'])->name('password.request');
    Route::post('/lupa-password', [AuthController::class, 'submitForgot'])->name('password.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Surat Masuk (staf internal)
    Route::get('surat-masuk', [SuratMasukController::class, 'index'])->name('surat-masuk.index');
    Route::get('surat-masuk/create', [SuratMasukController::class, 'create'])->name('surat-masuk.create');
    Route::post('surat-masuk', [SuratMasukController::class, 'store'])->name('surat-masuk.store');
    Route::get('surat-masuk/{suratMasuk}', [SuratMasukController::class, 'show'])->name('surat-masuk.show');
    Route::delete('surat-masuk/{suratMasuk}', [SuratMasukController::class, 'destroy'])->name('surat-masuk.destroy');

    // Disposisi (staf internal)
    Route::get('surat-masuk/{suratMasuk}/disposisi/create', [DisposisiController::class, 'create'])->name('disposisi.create');
    Route::post('surat-masuk/{suratMasuk}/disposisi', [DisposisiController::class, 'store'])->name('disposisi.store');
    Route::get('disposisi', [DisposisiController::class, 'index'])->name('disposisi.index');

    // Surat Keluar (staf internal)
    Route::get('surat-keluar', [SuratKeluarController::class, 'index'])->name('surat-keluar.index');
    Route::get('surat-keluar/create', [SuratKeluarController::class, 'create'])->name('surat-keluar.create');
    Route::post('surat-keluar', [SuratKeluarController::class, 'store'])->name('surat-keluar.store');
    Route::get('surat-keluar/{suratKeluar}', [SuratKeluarController::class, 'show'])->name('surat-keluar.show');
    Route::post('surat-keluar/{suratKeluar}/ajukan', [SuratKeluarController::class, 'ajukan'])->name('surat-keluar.ajukan');
    Route::post('surat-keluar/{suratKeluar}/setujui', [SuratKeluarController::class, 'setujui'])->name('surat-keluar.setujui');
    Route::post('surat-keluar/{suratKeluar}/tolak', [SuratKeluarController::class, 'tolak'])->name('surat-keluar.tolak');
    Route::get('surat-keluar/{suratKeluar}/cetak', [SuratKeluarController::class, 'cetak'])->name('surat-keluar.cetak');
    Route::delete('surat-keluar/{suratKeluar}', [SuratKeluarController::class, 'destroy'])->name('surat-keluar.destroy');

    // PKL & Sertifikat (dibatasi per role di controller: siswa/industri hanya lihat miliknya, tamu diblok)
    Route::get('pkl', [PklController::class, 'index'])->name('pkl.index');
    Route::get('pkl/create', [PklController::class, 'create'])->name('pkl.create');
    Route::post('pkl', [PklController::class, 'store'])->name('pkl.store');
    Route::get('pkl/{pkl}', [PklController::class, 'show'])->name('pkl.show');
    Route::post('pkl/{pkl}/setujui', [PklController::class, 'setujui'])->name('pkl.setujui');
    Route::post('pkl/{pkl}/selesaikan', [PklController::class, 'selesaikan'])->name('pkl.selesaikan');
    Route::delete('pkl/{pkl}', [PklController::class, 'destroy'])->name('pkl.destroy');

    // Kerjasama Industri (dibatasi per role: industri hanya lihat miliknya, siswa/tamu diblok)
    Route::get('kerjasama', [KerjasamaController::class, 'index'])->name('kerjasama.index');
    Route::get('kerjasama/create', [KerjasamaController::class, 'create'])->name('kerjasama.create');
    Route::post('kerjasama', [KerjasamaController::class, 'store'])->name('kerjasama.store');
    Route::get('kerjasama/{kerjasama}', [KerjasamaController::class, 'show'])->name('kerjasama.show');
    Route::delete('kerjasama/{kerjasama}', [KerjasamaController::class, 'destroy'])->name('kerjasama.destroy');

    // Laporan & Rekap (staf internal)
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // Sertifikat Umum (semua peran boleh lihat, kecuali tamu)
    Route::get('sertifikat', [SertifikatController::class, 'index'])->name('sertifikat.index');
    Route::get('sertifikat/create', [SertifikatController::class, 'create'])->name('sertifikat.create');
    Route::post('sertifikat', [SertifikatController::class, 'store'])->name('sertifikat.store');
    Route::get('sertifikat/{sertifikat}', [SertifikatController::class, 'show'])->name('sertifikat.show');
    Route::get('sertifikat/{sertifikat}/cetak', [SertifikatController::class, 'cetak'])->name('sertifikat.cetak');
    Route::delete('sertifikat/{sertifikat}', [SertifikatController::class, 'destroy'])->name('sertifikat.destroy');

    // Manajemen Pengguna (khusus Administrator)
    Route::get('pengguna', [UserController::class, 'index'])->name('users.index');
    Route::get('pengguna/create', [UserController::class, 'create'])->name('users.create');
    Route::post('pengguna', [UserController::class, 'store'])->name('users.store');
    Route::get('pengguna/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('pengguna/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('pengguna/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('reset-password/{resetRequest}/selesai', [UserController::class, 'selesaikanResetRequest'])->name('users.reset-request.selesai');

    // Persetujuan akun Tamu (Tata Usaha & Administrator)
    Route::get('akun-tamu', [TamuApprovalController::class, 'index'])->name('akun-tamu.index');
    Route::post('akun-tamu/{user}/setujui', [TamuApprovalController::class, 'setujui'])->name('akun-tamu.setujui');
    Route::post('akun-tamu/{user}/tolak', [TamuApprovalController::class, 'tolak'])->name('akun-tamu.tolak');

    // Kirim Surat (khusus akun Tamu)
    Route::get('kirim-surat', [TamuSuratController::class, 'create'])->name('tamu.kirim');
    Route::post('kirim-surat', [TamuSuratController::class, 'store'])->name('tamu.kirim.store');
    Route::get('riwayat-surat-saya', [TamuSuratController::class, 'index'])->name('tamu.riwayat');
});
