-- =========================================================
-- SIPKAL - Sistem Informasi Persuratan, PKL & Kerjasama Industri
-- SMK Negeri 4 Tanjungpinang
-- Dump database MySQL (skema + akun & data contoh)
-- Import: mysql -u root -p simsek < simsek.sql
-- Password SEMUA akun contoh di bawah ini: "password"
-- =========================================================

SET FOREIGN_KEY_CHECKS = 0;
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `simsek` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `simsek`;

-- ---------------------------------------------------------
-- Tabel: users
-- ---------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `username` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) DEFAULT NULL,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','admin_tu','kepala_sekolah','waka','bendahara','siswa','industri','tamu') NOT NULL DEFAULT 'admin_tu',
  `status_akun` ENUM('aktif','menunggu_persetujuan','ditolak') NOT NULL DEFAULT 'aktif',
  `jabatan` VARCHAR(255) DEFAULT NULL,
  `instansi_asal` VARCHAR(255) DEFAULT NULL COMMENT 'Diisi jika role = tamu',
  `unit` VARCHAR(255) DEFAULT NULL,
  `industri_id` BIGINT UNSIGNED DEFAULT NULL,
  `remember_token` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `password_reset_requests`;
CREATE TABLE `password_reset_requests` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `catatan` TEXT DEFAULT NULL,
  `status` ENUM('baru','selesai') NOT NULL DEFAULT 'baru',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- Tabel: siswas
-- ---------------------------------------------------------
DROP TABLE IF EXISTS `siswas`;
CREATE TABLE `siswas` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED DEFAULT NULL,
  `nis` VARCHAR(255) NOT NULL,
  `nama` VARCHAR(255) NOT NULL,
  `tempat_lahir` VARCHAR(255) DEFAULT NULL,
  `tanggal_lahir` DATE DEFAULT NULL,
  `kelas` VARCHAR(255) DEFAULT NULL,
  `jurusan` VARCHAR(255) NOT NULL DEFAULT 'Rekayasa Perangkat Lunak',
  `no_hp` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `siswas_nis_unique` (`nis`),
  KEY `siswas_user_id_foreign` (`user_id`),
  CONSTRAINT `siswas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- Tabel: industris (DU/DI)
-- ---------------------------------------------------------
DROP TABLE IF EXISTS `industris`;
CREATE TABLE `industris` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_perusahaan` VARCHAR(255) NOT NULL,
  `alamat` TEXT DEFAULT NULL,
  `bidang_usaha` VARCHAR(255) DEFAULT NULL,
  `contact_person` VARCHAR(255) DEFAULT NULL,
  `telepon` VARCHAR(255) DEFAULT NULL,
  `email` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `users`
  ADD CONSTRAINT `users_industri_id_foreign` FOREIGN KEY (`industri_id`) REFERENCES `industris` (`id`) ON DELETE SET NULL;

-- ---------------------------------------------------------
-- Tabel: surat_masuks
-- ---------------------------------------------------------
DROP TABLE IF EXISTS `surat_masuks`;
CREATE TABLE `surat_masuks` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nomor_agenda` VARCHAR(255) NOT NULL COMMENT 'Dibuat otomatis, format SM-001',
  `nomor_surat` VARCHAR(255) DEFAULT NULL COMMENT 'Nomor surat asli dari pengirim',
  `pengirim` VARCHAR(255) NOT NULL,
  `perihal` VARCHAR(255) NOT NULL,
  `tanggal_terima` DATE NOT NULL,
  `file_path` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('menunggu_disposisi','sudah_didisposisi') NOT NULL DEFAULT 'menunggu_disposisi',
  `created_by` BIGINT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `surat_masuks_nomor_agenda_unique` (`nomor_agenda`),
  KEY `surat_masuks_created_by_foreign` (`created_by`),
  CONSTRAINT `surat_masuks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- Tabel: disposisis
-- ---------------------------------------------------------
DROP TABLE IF EXISTS `disposisis`;
CREATE TABLE `disposisis` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `surat_masuk_id` BIGINT UNSIGNED NOT NULL,
  `unit_tujuan` VARCHAR(255) NOT NULL,
  `instruksi` TEXT DEFAULT NULL,
  `dibuat_oleh` BIGINT UNSIGNED DEFAULT NULL,
  `tanggal_disposisi` DATE NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `disposisis_surat_masuk_id_foreign` (`surat_masuk_id`),
  KEY `disposisis_dibuat_oleh_foreign` (`dibuat_oleh`),
  CONSTRAINT `disposisis_surat_masuk_id_foreign` FOREIGN KEY (`surat_masuk_id`) REFERENCES `surat_masuks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `disposisis_dibuat_oleh_foreign` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- Tabel: surat_keluars
-- ---------------------------------------------------------
DROP TABLE IF EXISTS `surat_keluars`;
CREATE TABLE `surat_keluars` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nomor_surat` VARCHAR(255) NOT NULL COMMENT 'Dibuat otomatis, format 421.7/{urut}/SMKN4-TPI/{tahun}',
  `jenis_surat` VARCHAR(255) NOT NULL DEFAULT 'lainnya' COMMENT 'undangan, permohonan, keterangan, pemberitahuan, lainnya',
  `perihal` VARCHAR(255) NOT NULL,
  `tujuan` VARCHAR(255) NOT NULL,
  `isi_surat` LONGTEXT DEFAULT NULL COMMENT 'Isi lengkap surat untuk dicetak sebagai dokumen resmi',
  `tanggal_surat` DATE NOT NULL,
  `file_path` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('draft','menunggu_acc','disetujui','dikembalikan') NOT NULL DEFAULT 'draft',
  `created_by` BIGINT UNSIGNED DEFAULT NULL,
  `approved_by` BIGINT UNSIGNED DEFAULT NULL,
  `approved_at` TIMESTAMP NULL DEFAULT NULL,
  `catatan_kepsek` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `surat_keluars_nomor_surat_unique` (`nomor_surat`),
  KEY `surat_keluars_created_by_foreign` (`created_by`),
  KEY `surat_keluars_approved_by_foreign` (`approved_by`),
  CONSTRAINT `surat_keluars_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `surat_keluars_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- Tabel: pkls
-- ---------------------------------------------------------
DROP TABLE IF EXISTS `pkls`;
CREATE TABLE `pkls` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `siswa_id` BIGINT UNSIGNED NOT NULL,
  `industri_id` BIGINT UNSIGNED NOT NULL,
  `tanggal_mulai` DATE NOT NULL,
  `tanggal_selesai` DATE NOT NULL,
  `status` ENUM('menunggu_persetujuan','berlangsung','selesai') NOT NULL DEFAULT 'menunggu_persetujuan',
  `predikat` VARCHAR(255) DEFAULT NULL COMMENT 'mis. Sangat Baik, Baik',
  `nomor_sertifikat` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pkls_nomor_sertifikat_unique` (`nomor_sertifikat`),
  KEY `pkls_siswa_id_foreign` (`siswa_id`),
  KEY `pkls_industri_id_foreign` (`industri_id`),
  CONSTRAINT `pkls_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pkls_industri_id_foreign` FOREIGN KEY (`industri_id`) REFERENCES `industris` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- Tabel: kerjasamas (status MoU dihitung otomatis di aplikasi, tidak disimpan)
-- ---------------------------------------------------------
DROP TABLE IF EXISTS `kerjasamas`;
CREATE TABLE `kerjasamas` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `industri_id` BIGINT UNSIGNED NOT NULL,
  `nomor_dokumen` VARCHAR(255) NOT NULL COMMENT 'Nomor dokumen MoU/PKS, dibuat otomatis',
  `bidang` VARCHAR(255) NOT NULL,
  `ruang_lingkup` TEXT DEFAULT NULL COMMENT 'Ruang lingkup kerjasama',
  `mulai_mou` DATE NOT NULL,
  `berakhir_mou` DATE NOT NULL,
  `dokumen_mou` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kerjasamas_nomor_dokumen_unique` (`nomor_dokumen`),
  KEY `kerjasamas_industri_id_foreign` (`industri_id`),
  CONSTRAINT `kerjasamas_industri_id_foreign` FOREIGN KEY (`industri_id`) REFERENCES `industris` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- Tabel: sertifikats (Sertifikat Umum, di luar PKL)
-- ---------------------------------------------------------
DROP TABLE IF EXISTS `sertifikats`;
CREATE TABLE `sertifikats` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nomor_sertifikat` VARCHAR(255) NOT NULL COMMENT 'Dibuat otomatis, format B/425/{urut}/I-SMKN4/{tahun}',
  `jenis_kegiatan` VARCHAR(255) NOT NULL,
  `nama_penerima` VARCHAR(255) NOT NULL,
  `identitas_penerima` VARCHAR(255) DEFAULT NULL,
  `predikat` VARCHAR(255) DEFAULT NULL,
  `keterangan` TEXT DEFAULT NULL,
  `tanggal_terbit` DATE NOT NULL,
  `dibuat_oleh` BIGINT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sertifikats_nomor_sertifikat_unique` (`nomor_sertifikat`),
  KEY `sertifikats_dibuat_oleh_foreign` (`dibuat_oleh`),
  CONSTRAINT `sertifikats_dibuat_oleh_foreign` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- Tabel bawaan Laravel (session & password reset)
-- ---------------------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` VARCHAR(255) NOT NULL,
  `user_id` BIGINT UNSIGNED DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` TEXT DEFAULT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- AKUN & DATA CONTOH
-- Password untuk SEMUA akun di bawah: "password"
-- =========================================================

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `status_akun`, `jabatan`, `instansi_asal`, `unit`, `industri_id`, `created_at`, `updated_at`) VALUES
(1, 'Bagus Setiawan', 'superadmin', 'admin@smkn4tpi.sch.id', '$2y$10$swTI.VH0dWTf0HIG0npiLuLYAxrgZIMMgrArlKnCfU3iNOXB.qww2', 'admin', 'aktif', 'Administrator Sistem', NULL, NULL, NULL, NOW(), NOW()),
(2, 'Dedi Kurniawan, S.Kom', 'dedi.kurniawan', 'tu@smkn4tpi.sch.id', '$2y$10$swTI.VH0dWTf0HIG0npiLuLYAxrgZIMMgrArlKnCfU3iNOXB.qww2', 'admin_tu', 'aktif', 'Staf Tata Usaha', NULL, NULL, NULL, NOW(), NOW()),
(3, 'Yayuk Sri Mulyani Rahayu, S.Pd., M.M.', 'kepsek', 'kepsek@smkn4tpi.sch.id', '$2y$10$swTI.VH0dWTf0HIG0npiLuLYAxrgZIMMgrArlKnCfU3iNOXB.qww2', 'kepala_sekolah', 'aktif', 'Kepala Sekolah', NULL, NULL, NULL, NOW(), NOW()),
(4, 'Siti Rahmawati, S.Pd.', 'waka.kurikulum', NULL, '$2y$10$swTI.VH0dWTf0HIG0npiLuLYAxrgZIMMgrArlKnCfU3iNOXB.qww2', 'waka', 'aktif', 'Waka Kurikulum', NULL, 'Waka Kurikulum', NULL, NOW(), NOW()),
(5, 'Rudi Hartono, S.T.', 'waka.humas', NULL, '$2y$10$swTI.VH0dWTf0HIG0npiLuLYAxrgZIMMgrArlKnCfU3iNOXB.qww2', 'waka', 'aktif', 'Waka Humas', NULL, 'Waka Humas', NULL, NOW(), NOW()),
(6, 'Nurlela, S.E.', 'bendahara', NULL, '$2y$10$swTI.VH0dWTf0HIG0npiLuLYAxrgZIMMgrArlKnCfU3iNOXB.qww2', 'bendahara', 'aktif', 'Bendahara', NULL, 'Bendahara', NULL, NOW(), NOW()),
(7, 'Ahmad Fauzan Ramadhan', 'andi.saputra', NULL, '$2y$10$swTI.VH0dWTf0HIG0npiLuLYAxrgZIMMgrArlKnCfU3iNOXB.qww2', 'siswa', 'aktif', NULL, NULL, NULL, NULL, NOW(), NOW()),
(8, 'Andri Wijaya', 'telkom.witel', NULL, '$2y$10$swTI.VH0dWTf0HIG0npiLuLYAxrgZIMMgrArlKnCfU3iNOXB.qww2', 'industri', 'aktif', 'HRD PT Telkom Witel Kepri', NULL, NULL, 2, NOW(), NOW()),
(9, 'Herlina Wati', 'herlina.wati', NULL, '$2y$10$swTI.VH0dWTf0HIG0npiLuLYAxrgZIMMgrArlKnCfU3iNOXB.qww2', 'tamu', 'aktif', NULL, 'Orang Tua Siswa a.n. Rian Saputra', NULL, NULL, NOW(), NOW()),
(10, 'Joko Prasetyo', 'joko.prasetyo', NULL, '$2y$10$swTI.VH0dWTf0HIG0npiLuLYAxrgZIMMgrArlKnCfU3iNOXB.qww2', 'tamu', 'menunggu_persetujuan', NULL, 'CV Mitra Karya Tanjungpinang', NULL, NULL, NOW(), NOW());

INSERT INTO `password_reset_requests` (`id`, `username`, `catatan`, `status`, `created_at`, `updated_at`) VALUES
(1, 'andi.saputra', 'Lupa kata sandi setelah ganti HP, bisa dihubungi lewat wali kelas.', 'baru', NOW(), NOW());

INSERT INTO `siswas` (`id`, `user_id`, `nis`, `nama`, `tempat_lahir`, `tanggal_lahir`, `kelas`, `jurusan`, `created_at`, `updated_at`) VALUES
(1, NULL, '3684', 'Syarifah Nazura Nazalya', 'Kijang', '2007-10-08', 'XII RPL 1', 'Rekayasa Perangkat Lunak', NOW(), NOW()),
(2, 7, '3701', 'Ahmad Fauzan Ramadhan', 'Tanjungpinang', '2008-05-14', 'XII RPL 1', 'Rekayasa Perangkat Lunak', NOW(), NOW()),
(3, NULL, '3699', 'Nurul Aini Ramadhani', 'Tanjungpinang', '2008-02-02', 'XII RPL 2', 'Rekayasa Perangkat Lunak', NOW(), NOW());

INSERT INTO `industris` (`id`, `nama_perusahaan`, `alamat`, `bidang_usaha`, `contact_person`, `telepon`, `email`, `created_at`, `updated_at`) VALUES
(1, 'Kantor Wilayah Kementerian Agama Provinsi Kepulauan Riau', 'Jl. Ir. Sutami, Tanjungpinang', 'Instansi Pemerintahan', 'Drs. H. Edi Batara, M.A.P.', '0771-123456', 'humas@kemenagkepri.go.id', NOW(), NOW()),
(2, 'PT Telkom Indonesia Witel Kepri', 'Jl. Basuki Rahmat, Tanjungpinang', 'Telekomunikasi & Teknologi Informasi', 'Andri Wijaya', '0771-987654', 'hrd@telkomwitelkepri.co.id', NOW(), NOW()),
(3, 'Dinas Komunikasi dan Informatika Kota Tanjungpinang', 'Jl. Ir. Sutami, Tanjungpinang', 'Pemerintahan & Layanan TI', 'Rina Marlina', '0771-334455', 'diskominfo@tanjungpinangkota.go.id', NOW(), NOW());

INSERT INTO `surat_masuks` (`id`, `nomor_agenda`, `nomor_surat`, `pengirim`, `perihal`, `tanggal_terima`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'SM-045', '421/DISDIK-KEPRI/2026', 'Dinas Pendidikan Provinsi Kepulauan Riau', 'Undangan Rapat Koordinasi Kepala Sekolah SMK Se-Kota Tanjungpinang', '2026-08-25', 'sudah_didisposisi', 2, NOW(), NOW()),
(2, 'SM-046', '187/HR-TEL/VIII/2026', 'PT Telkom Indonesia Witel Kepri', 'Penawaran Kerjasama Penempatan Praktik Kerja Lapangan', '2026-08-27', 'menunggu_disposisi', 2, NOW(), NOW()),
(3, 'SM-047', NULL, 'Orang Tua Siswa a.n. Rian Saputra (Kelas XII RPL 2)', 'Permohonan Izin Tidak Masuk Sekolah 3 Hari (Sakit)', '2026-08-28', 'menunggu_disposisi', 2, NOW(), NOW()),
(4, 'SM-048', 'B/512.7/Kw.28.1/2026', 'Kantor Wilayah Kementerian Agama Provinsi Kepulauan Riau', 'Konfirmasi Jadwal Penjemputan Siswa Peserta PKL', '2026-08-31', 'sudah_didisposisi', 2, NOW(), NOW()),
(5, 'SM-049', NULL, 'Herlina Wati (Orang Tua Siswa a.n. Rian Saputra)', 'Permohonan Surat Keterangan Aktif Sekolah', '2026-09-03', 'menunggu_disposisi', 9, NOW(), NOW());

INSERT INTO `disposisis` (`id`, `surat_masuk_id`, `unit_tujuan`, `instruksi`, `dibuat_oleh`, `tanggal_disposisi`, `created_at`, `updated_at`) VALUES
(1, 1, 'Waka Humas', 'Mohon dihadiri dan dibuat laporan hasil rapat.', 2, '2026-08-26', NOW(), NOW()),
(2, 4, 'Tata Usaha (PKL)', 'Koordinasikan jadwal penjemputan dengan siswa PKL terkait.', 2, '2026-08-31', NOW(), NOW());

INSERT INTO `surat_keluars` (`id`, `nomor_surat`, `jenis_surat`, `perihal`, `tujuan`, `isi_surat`, `tanggal_surat`, `status`, `created_by`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, '421.7/209/SMKN4-TPI/2026', 'keterangan', 'Balasan Penawaran Kerjasama Penempatan PKL', 'PT Telkom Indonesia Witel Kepri', 'Sehubungan dengan surat penawaran kerjasama Saudara nomor 187/HR-TEL/VIII/2026, dengan ini kami sampaikan bahwa pihak sekolah menyetujui penawaran kerjasama penempatan Praktik Kerja Lapangan (PKL) bagi siswa konsentrasi keahlian Rekayasa Perangkat Lunak.', '2026-08-20', 'disetujui', 2, 3, '2026-08-21 09:00:00', NOW(), NOW()),
(2, '421.7/210/SMKN4-TPI/2026', 'permohonan', 'Permohonan Penempatan PKL Konsentrasi Rekayasa Perangkat Lunak', 'Dinas Komunikasi dan Informatika Kota Tanjungpinang', 'Sehubungan dengan program Praktik Kerja Lapangan (PKL) semester ganjil, kami mengajukan permohonan penempatan 3 (tiga) orang siswa konsentrasi keahlian Rekayasa Perangkat Lunak untuk melaksanakan PKL di instansi Bapak/Ibu pada periode yang akan ditentukan kemudian.', '2026-09-01', 'draft', 2, NULL, NULL, NOW(), NOW()),
(3, '421.7/211/SMKN4-TPI/2026', 'undangan', 'Undangan Pertemuan Wali Murid Kelas XII', 'Wali Murid Kelas XII Rekayasa Perangkat Lunak', 'Hari, Tanggal : Sabtu, 12 September 2026\nWaktu : 09.00 WIB s.d. selesai\nTempat : Aula SMK Negeri 4 Tanjungpinang\nAcara : Persiapan pelaksanaan Praktik Kerja Lapangan dan kelulusan siswa kelas XII.', '2026-09-02', 'menunggu_acc', 2, NULL, NULL, NOW(), NOW());

INSERT INTO `pkls` (`id`, `siswa_id`, `industri_id`, `tanggal_mulai`, `tanggal_selesai`, `status`, `predikat`, `nomor_sertifikat`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-01-06', '2025-03-28', 'selesai', 'Sangat Baik', 'B/423.4/P.06.209/I-SMKN4/PKL/2025', NOW(), NOW()),
(2, 2, 3, '2026-07-06', '2026-09-26', 'berlangsung', NULL, NULL, NOW(), NOW()),
(3, 3, 2, '2026-06-01', '2026-08-30', 'menunggu_persetujuan', NULL, NULL, NOW(), NOW());

INSERT INTO `kerjasamas` (`id`, `industri_id`, `nomor_dokumen`, `bidang`, `ruang_lingkup`, `mulai_mou`, `berakhir_mou`, `created_at`, `updated_at`) VALUES
(1, 1, '421.4/PKS.01/SMKN4-TPI/2025', 'Instansi Pemerintahan', 'Penempatan siswa PKL dan kunjungan edukatif.', '2025-01-06', '2027-01-06', NOW(), NOW()),
(2, 2, '421.4/PKS.02/SMKN4-TPI/2026', 'Telekomunikasi & Teknologi Informasi', 'Penempatan PKL, kelas industri, dan rekrutmen lulusan konsentrasi RPL.', '2026-07-01', '2028-07-01', NOW(), NOW()),
(3, 3, '421.4/PKS.03/SMKN4-TPI/2024', 'Pemerintahan & Layanan TI', 'Penempatan siswa PKL bidang pengembangan aplikasi layanan publik.', '2024-01-15', '2026-01-15', NOW(), NOW());

INSERT INTO `sertifikats` (`id`, `nomor_sertifikat`, `jenis_kegiatan`, `nama_penerima`, `identitas_penerima`, `predikat`, `keterangan`, `tanggal_terbit`, `dibuat_oleh`, `created_at`, `updated_at`) VALUES
(1, 'B/425/1/I-SMKN4/2026', 'Peserta Lomba Kompetensi Siswa (LKS) Tingkat Provinsi Bidang Web Technology', 'Ahmad Fauzan Ramadhan', 'NIS 3701 · Kelas XII RPL 1', 'Juara 2', 'Telah mengikuti dan meraih prestasi pada Lomba Kompetensi Siswa (LKS) Tingkat Provinsi Kepulauan Riau Bidang Web Technology tahun 2026.', '2026-08-15', 2, NOW(), NOW()),
(2, 'B/425/2/I-SMKN4/2026', 'Panitia Pelaksana Kegiatan Perayaan HUT Sekolah', 'Nurul Aini Ramadhani', 'NIS 3699 · Kelas XII RPL 2', NULL, 'Telah berpartisipasi aktif sebagai panitia pelaksana pada kegiatan Perayaan Hari Ulang Tahun SMK Negeri 4 Tanjungpinang.', '2026-08-30', 2, NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;
