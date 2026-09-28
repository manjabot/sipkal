<?php

namespace Database\Seeders;

use App\Models\Disposisi;
use App\Models\Industri;
use App\Models\Kerjasama;
use App\Models\Pkl;
use App\Models\Siswa;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== AKUN PENGGUNA (semua password: "password") =====
        $admin = User::create([
            'name' => 'Bagus Setiawan',
            'username' => 'superadmin',
            'email' => 'admin@smkn4tpi.sch.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'jabatan' => 'Administrator Sistem',
        ]);

        $tu = User::create([
            'name' => 'Dedi Kurniawan, S.Kom',
            'username' => 'dedi.kurniawan',
            'email' => 'tu@smkn4tpi.sch.id',
            'password' => Hash::make('password'),
            'role' => 'admin_tu',
            'jabatan' => 'Staf Tata Usaha',
        ]);

        $kepsek = User::create([
            'name' => 'Yayuk Sri Mulyani Rahayu, S.Pd., M.M.',
            'username' => 'kepsek',
            'email' => 'kepsek@smkn4tpi.sch.id',
            'password' => Hash::make('password'),
            'role' => 'kepala_sekolah',
            'jabatan' => 'Kepala Sekolah',
        ]);

        $wakaKurikulum = User::create([
            'name' => 'Siti Rahmawati, S.Pd.',
            'username' => 'waka.kurikulum',
            'password' => Hash::make('password'),
            'role' => 'waka',
            'jabatan' => 'Waka Kurikulum',
            'unit' => 'Waka Kurikulum',
        ]);

        $wakaHumas = User::create([
            'name' => 'Rudi Hartono, S.T.',
            'username' => 'waka.humas',
            'password' => Hash::make('password'),
            'role' => 'waka',
            'jabatan' => 'Waka Humas',
            'unit' => 'Waka Humas',
        ]);

        $bendahara = User::create([
            'name' => 'Nurlela, S.E.',
            'username' => 'bendahara',
            'password' => Hash::make('password'),
            'role' => 'bendahara',
            'jabatan' => 'Bendahara',
            'unit' => 'Bendahara',
        ]);

        // ===== SISWA =====
        $siswaAndi = Siswa::create([
            'nis' => '3684',
            'nama' => 'Syarifah Nazura Nazalya',
            'tempat_lahir' => 'Kijang',
            'tanggal_lahir' => '2007-10-08',
            'kelas' => 'XII RPL 1',
            'jurusan' => 'Rekayasa Perangkat Lunak',
        ]);

        $siswaFauzan = Siswa::create([
            'nis' => '3701',
            'nama' => 'Ahmad Fauzan Ramadhan',
            'tempat_lahir' => 'Tanjungpinang',
            'tanggal_lahir' => '2008-05-14',
            'kelas' => 'XII RPL 1',
            'jurusan' => 'Rekayasa Perangkat Lunak',
        ]);

        $siswaNurul = Siswa::create([
            'nis' => '3699',
            'nama' => 'Nurul Aini Ramadhani',
            'tempat_lahir' => 'Tanjungpinang',
            'tanggal_lahir' => '2008-02-02',
            'kelas' => 'XII RPL 2',
            'jurusan' => 'Rekayasa Perangkat Lunak',
        ]);

        // Akun login siswa dihubungkan ke salah satu data siswa
        $siswaUser = User::create([
            'name' => 'Ahmad Fauzan Ramadhan',
            'username' => 'andi.saputra',
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);
        $siswaFauzan->update(['user_id' => $siswaUser->id]);

        // ===== INDUSTRI (DU/DI) =====
        $industriKemenag = Industri::create([
            'nama_perusahaan' => 'Kantor Wilayah Kementerian Agama Provinsi Kepulauan Riau',
            'alamat' => 'Jl. Ir. Sutami, Tanjungpinang',
            'bidang_usaha' => 'Instansi Pemerintahan',
            'contact_person' => 'Drs. H. Edi Batara, M.A.P.',
            'telepon' => '0771-123456',
            'email' => 'humas@kemenagkepri.go.id',
        ]);

        $industriTelkom = Industri::create([
            'nama_perusahaan' => 'PT Telkom Indonesia Witel Kepri',
            'alamat' => 'Jl. Basuki Rahmat, Tanjungpinang',
            'bidang_usaha' => 'Telekomunikasi & Teknologi Informasi',
            'contact_person' => 'Andri Wijaya',
            'telepon' => '0771-987654',
            'email' => 'hrd@telkomwitelkepri.co.id',
        ]);

        $industriDiskominfo = Industri::create([
            'nama_perusahaan' => 'Dinas Komunikasi dan Informatika Kota Tanjungpinang',
            'alamat' => 'Jl. Ir. Sutami, Tanjungpinang',
            'bidang_usaha' => 'Pemerintahan & Layanan TI',
            'contact_person' => 'Rina Marlina',
            'telepon' => '0771-334455',
            'email' => 'diskominfo@tanjungpinangkota.go.id',
        ]);

        // Akun login mitra industri (contoh: PT Telkom Witel Kepri)
        $industriUser = User::create([
            'name' => 'Andri Wijaya',
            'username' => 'telkom.witel',
            'password' => Hash::make('password'),
            'role' => 'industri',
            'jabatan' => 'HRD PT Telkom Witel Kepri',
            'industri_id' => $industriTelkom->id,
        ]);

        // ===== SURAT MASUK =====
        $sm045 = SuratMasuk::create([
            'nomor_agenda' => 'SM-045',
            'nomor_surat' => '421/DISDIK-KEPRI/2026',
            'pengirim' => 'Dinas Pendidikan Provinsi Kepulauan Riau',
            'perihal' => 'Undangan Rapat Koordinasi Kepala Sekolah SMK Se-Kota Tanjungpinang',
            'tanggal_terima' => '2026-08-25',
            'status' => 'sudah_didisposisi',
            'created_by' => $tu->id,
        ]);

        SuratMasuk::create([
            'nomor_agenda' => 'SM-046',
            'nomor_surat' => '187/HR-TEL/VIII/2026',
            'pengirim' => 'PT Telkom Indonesia Witel Kepri',
            'perihal' => 'Penawaran Kerjasama Penempatan Praktik Kerja Lapangan',
            'tanggal_terima' => '2026-08-27',
            'status' => 'menunggu_disposisi',
            'created_by' => $tu->id,
        ]);

        SuratMasuk::create([
            'nomor_agenda' => 'SM-047',
            'nomor_surat' => null,
            'pengirim' => 'Orang Tua Siswa a.n. Rian Saputra (Kelas XII RPL 2)',
            'perihal' => 'Permohonan Izin Tidak Masuk Sekolah 3 Hari (Sakit)',
            'tanggal_terima' => '2026-08-28',
            'status' => 'menunggu_disposisi',
            'created_by' => $tu->id,
        ]);

        $sm048 = SuratMasuk::create([
            'nomor_agenda' => 'SM-048',
            'nomor_surat' => 'B/512.7/Kw.28.1/2026',
            'pengirim' => 'Kantor Wilayah Kementerian Agama Provinsi Kepulauan Riau',
            'perihal' => 'Konfirmasi Jadwal Penjemputan Siswa Peserta PKL',
            'tanggal_terima' => '2026-08-31',
            'status' => 'sudah_didisposisi',
            'created_by' => $tu->id,
        ]);

        // ===== DISPOSISI =====
        Disposisi::create([
            'surat_masuk_id' => $sm045->id,
            'unit_tujuan' => 'Waka Humas',
            'instruksi' => 'Mohon dihadiri dan dibuat laporan hasil rapat.',
            'dibuat_oleh' => $tu->id,
            'tanggal_disposisi' => '2026-08-26',
        ]);

        Disposisi::create([
            'surat_masuk_id' => $sm048->id,
            'unit_tujuan' => 'Tata Usaha (PKL)',
            'instruksi' => 'Koordinasikan jadwal penjemputan dengan siswa PKL terkait.',
            'dibuat_oleh' => $tu->id,
            'tanggal_disposisi' => '2026-08-31',
        ]);

        // ===== SURAT KELUAR =====
        SuratKeluar::create([
            'nomor_surat' => '421.7/209/SMKN4-TPI/2026',
            'jenis_surat' => 'keterangan',
            'perihal' => 'Balasan Penawaran Kerjasama Penempatan PKL',
            'tujuan' => 'PT Telkom Indonesia Witel Kepri',
            'isi_surat' => 'Sehubungan dengan surat penawaran kerjasama Saudara nomor 187/HR-TEL/VIII/2026, dengan ini kami sampaikan bahwa pihak sekolah menyetujui penawaran kerjasama penempatan Praktik Kerja Lapangan (PKL) bagi siswa konsentrasi keahlian Rekayasa Perangkat Lunak.',
            'tanggal_surat' => '2026-08-20',
            'status' => 'disetujui',
            'created_by' => $tu->id,
            'approved_by' => $kepsek->id,
            'approved_at' => '2026-08-21 09:00:00',
        ]);

        SuratKeluar::create([
            'nomor_surat' => '421.7/210/SMKN4-TPI/2026',
            'jenis_surat' => 'permohonan',
            'perihal' => 'Permohonan Penempatan PKL Konsentrasi Rekayasa Perangkat Lunak',
            'tujuan' => 'Dinas Komunikasi dan Informatika Kota Tanjungpinang',
            'isi_surat' => 'Sehubungan dengan program Praktik Kerja Lapangan (PKL) semester ganjil, kami mengajukan permohonan penempatan 3 (tiga) orang siswa konsentrasi keahlian Rekayasa Perangkat Lunak untuk melaksanakan PKL di instansi Bapak/Ibu pada periode yang akan ditentukan kemudian.',
            'tanggal_surat' => '2026-09-01',
            'status' => 'draft',
            'created_by' => $tu->id,
        ]);

        SuratKeluar::create([
            'nomor_surat' => '421.7/211/SMKN4-TPI/2026',
            'jenis_surat' => 'undangan',
            'perihal' => 'Undangan Pertemuan Wali Murid Kelas XII',
            'tujuan' => 'Wali Murid Kelas XII Rekayasa Perangkat Lunak',
            'isi_surat' => 'Hari, Tanggal : Sabtu, 12 September 2026' . "\n" . 'Waktu : 09.00 WIB s.d. selesai' . "\n" . 'Tempat : Aula SMK Negeri 4 Tanjungpinang' . "\n" . 'Acara : Persiapan pelaksanaan Praktik Kerja Lapangan dan kelulusan siswa kelas XII.',
            'tanggal_surat' => '2026-09-02',
            'status' => 'menunggu_acc',
            'created_by' => $tu->id,
        ]);

        // ===== PKL =====
        Pkl::create([
            'siswa_id' => $siswaAndi->id,
            'industri_id' => $industriKemenag->id,
            'tanggal_mulai' => '2025-01-06',
            'tanggal_selesai' => '2025-03-28',
            'status' => 'selesai',
            'predikat' => 'Sangat Baik',
            'nomor_sertifikat' => 'B/423.4/P.06.209/I-SMKN4/PKL/2025',
        ]);

        Pkl::create([
            'siswa_id' => $siswaFauzan->id,
            'industri_id' => $industriDiskominfo->id,
            'tanggal_mulai' => '2026-07-06',
            'tanggal_selesai' => '2026-09-26',
            'status' => 'berlangsung',
        ]);

        Pkl::create([
            'siswa_id' => $siswaNurul->id,
            'industri_id' => $industriTelkom->id,
            'tanggal_mulai' => '2026-06-01',
            'tanggal_selesai' => '2026-08-30',
            'status' => 'menunggu_persetujuan',
        ]);

        // ===== KERJASAMA INDUSTRI =====
        Kerjasama::create([
            'industri_id' => $industriKemenag->id,
            'nomor_dokumen' => '421.4/PKS.01/SMKN4-TPI/2025',
            'bidang' => 'Instansi Pemerintahan',
            'ruang_lingkup' => 'Penempatan siswa PKL dan kunjungan edukatif.',
            'mulai_mou' => '2025-01-06',
            'berakhir_mou' => '2027-01-06',
        ]);

        Kerjasama::create([
            'industri_id' => $industriTelkom->id,
            'nomor_dokumen' => '421.4/PKS.02/SMKN4-TPI/2026',
            'bidang' => 'Telekomunikasi & Teknologi Informasi',
            'ruang_lingkup' => 'Penempatan PKL, kelas industri, dan rekrutmen lulusan konsentrasi RPL.',
            'mulai_mou' => '2026-07-01',
            'berakhir_mou' => '2028-07-01',
        ]);

        Kerjasama::create([
            'industri_id' => $industriDiskominfo->id,
            'nomor_dokumen' => '421.4/PKS.03/SMKN4-TPI/2024',
            'bidang' => 'Pemerintahan & Layanan TI',
            'ruang_lingkup' => 'Penempatan siswa PKL bidang pengembangan aplikasi layanan publik.',
            'mulai_mou' => '2024-01-15',
            'berakhir_mou' => '2026-01-15',
        ]);

        // ===== SERTIFIKAT UMUM =====
        \App\Models\Sertifikat::create([
            'nomor_sertifikat' => 'B/425/1/I-SMKN4/2026',
            'jenis_kegiatan' => 'Peserta Lomba Kompetensi Siswa (LKS) Tingkat Provinsi Bidang Web Technology',
            'nama_penerima' => 'Ahmad Fauzan Ramadhan',
            'identitas_penerima' => 'NIS 3701 · Kelas XII RPL 1',
            'predikat' => 'Juara 2',
            'keterangan' => 'Telah mengikuti dan meraih prestasi pada Lomba Kompetensi Siswa (LKS) Tingkat Provinsi Kepulauan Riau Bidang Web Technology tahun 2026.',
            'tanggal_terbit' => '2026-08-15',
            'dibuat_oleh' => $tu->id,
        ]);

        \App\Models\Sertifikat::create([
            'nomor_sertifikat' => 'B/425/2/I-SMKN4/2026',
            'jenis_kegiatan' => 'Panitia Pelaksana Kegiatan Perayaan HUT Sekolah',
            'nama_penerima' => 'Nurul Aini Ramadhani',
            'identitas_penerima' => 'NIS 3699 · Kelas XII RPL 2',
            'keterangan' => 'Telah berpartisipasi aktif sebagai panitia pelaksana pada kegiatan Perayaan Hari Ulang Tahun SMK Negeri 4 Tanjungpinang.',
            'tanggal_terbit' => '2026-08-30',
            'dibuat_oleh' => $tu->id,
        ]);

        // ===== AKUN TAMU (pengirim surat eksternal) =====
        $tamuAktif = User::create([
            'name' => 'Herlina Wati',
            'username' => 'herlina.wati',
            'password' => Hash::make('password'),
            'role' => 'tamu',
            'status_akun' => 'aktif',
            'instansi_asal' => 'Orang Tua Siswa a.n. Rian Saputra',
        ]);

        User::create([
            'name' => 'Joko Prasetyo',
            'username' => 'joko.prasetyo',
            'password' => Hash::make('password'),
            'role' => 'tamu',
            'status_akun' => 'menunggu_persetujuan',
            'instansi_asal' => 'CV Mitra Karya Tanjungpinang',
        ]);

        // Contoh surat yang sudah dikirim akun Tamu aktif
        SuratMasuk::create([
            'nomor_agenda' => SuratMasuk::nomorAgendaBerikutnya(),
            'pengirim' => 'Herlina Wati (Orang Tua Siswa a.n. Rian Saputra)',
            'perihal' => 'Permohonan Surat Keterangan Aktif Sekolah',
            'tanggal_terima' => '2026-09-03',
            'status' => 'menunggu_disposisi',
            'created_by' => $tamuAktif->id,
        ]);

        // ===== PERMINTAAN RESET KATA SANDI (contoh) =====
        \App\Models\PasswordResetRequest::create([
            'username' => 'andi.saputra',
            'catatan' => 'Lupa kata sandi setelah ganti HP, bisa dihubungi lewat wali kelas.',
            'status' => 'baru',
        ]);
    }
}
