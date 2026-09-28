# SIPKAL

**Sistem Informasi Persuratan, PKL & Kerjasama Industri**
SMK Negeri 4 Tanjungpinang

Aplikasi web berbasis Laravel 10 untuk mengelola surat masuk/keluar, PKL, kerjasama industri, dan sertifikat sekolah.

## Fitur

- **Surat Masuk & Disposisi**: pencatatan agenda otomatis (`SM-001`, dst) dan lembar disposisi
- **Surat Keluar**: draft oleh Tata Usaha, persetujuan Kepala Sekolah, cetak surat
- **PKL**: pengajuan, persetujuan, penyelesaian, dan pengesahan sertifikat PKL
- **Kerjasama Industri**: data mitra dan MoU dengan status otomatis (aktif / akan berakhir / berakhir)
- **Sertifikat Umum**: penerbitan dan cetak sertifikat kegiatan
- **Laporan & Rekap**: ringkasan status surat, PKL, dan kerjasama
- **Manajemen Pengguna**: khusus Administrator, termasuk permintaan reset kata sandi
- **Akun Tamu**: pengirim surat eksternal mendaftar sendiri, lalu disetujui Tata Usaha

Peran pengguna: Administrator, Staf Tata Usaha, Kepala Sekolah, Waka, Bendahara, Siswa, Mitra Industri, dan Tamu.

## Kebutuhan

- PHP 8.1 atau lebih baru
- Composer
- MySQL / MariaDB
- Git

Node.js **tidak diperlukan**, karena tampilan memakai `public/css/sipkal.css`.

Kalau memakai XAMPP atau Laragon, aktifkan dulu Apache dan MySQL.

## Cara Install

### 1. Ambil project

```bash
git clone https://github.com/manjabot/sipkal.git
cd sipkal
```

### 2. Pasang dependensi

```bash
composer install
```

### 3. Siapkan file `.env`

Windows (CMD):
```bash
copy .env.example .env
```

Windows (PowerShell), Linux, atau Mac:
```bash
cp .env.example .env
```

Lalu buat kunci aplikasi:
```bash
php artisan key:generate
```

### 4. Buat database

Buat database kosong bernama `simsek` di MySQL (lewat phpMyAdmin atau perintah berikut):

```sql
CREATE DATABASE simsek CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Buka `.env`, lalu sesuaikan tiga baris ini dengan MySQL di komputermu:

```env
DB_DATABASE=simsek
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Isi database

Pilih **salah satu** cara:

**Cara A: lewat migrasi dan seeder (disarankan)**
```bash
php artisan migrate --seed
```

**Cara B: import dump SQL**
Import file `database/sql/simsek.sql` ke database `simsek` lewat phpMyAdmin. Jangan dijalankan bersamaan dengan Cara A.

### 6. Hubungkan folder penyimpanan

File surat yang diunggah disimpan di folder `storage`, jadi perlu tautan ke `public`:

```bash
php artisan storage:link
```

### 7. Jalankan

```bash
php artisan serve
```

Buka **http://127.0.0.1:8000** di browser.

## Akun Demo

Dibuat oleh seeder. Password semua akun: `password`

| Username | Peran |
|---|---|
| `superadmin` | Administrator |
| `dedi.kurniawan` | Staf Tata Usaha |
| `kepsek` | Kepala Sekolah |
| `waka.humas` | Waka Humas |
| `bendahara` | Bendahara |
| `andi.saputra` | Siswa |
| `telkom.witel` | Mitra Industri |
| `herlina.wati` | Tamu |

Ganti atau hapus akun ini sebelum aplikasi dipakai di server sungguhan.

## Masalah Umum

| Masalah | Solusi |
|---|---|
| `No application encryption key has been specified` | Jalankan `php artisan key:generate` |
| `SQLSTATE[HY000] [1049] Unknown database` | Database belum dibuat, atau nama di `DB_DATABASE` salah |
| `SQLSTATE[HY000] [1045] Access denied` | `DB_USERNAME` atau `DB_PASSWORD` di `.env` salah |
| File surat tidak bisa dibuka | Jalankan `php artisan storage:link` |
| Perubahan `.env` tidak terbaca, atau muncul error aneh | Jalankan `php artisan optimize:clear` |
| Login berhasil tapi terlempar ke halaman login | Akses selalu lewat alamat yang sama (`127.0.0.1` atau `localhost`, jangan dicampur) |

## Untuk Server Sungguhan

Di `.env`, ubah `APP_ENV=production` dan `APP_DEBUG=false`. Ganti juga password semua akun demo, karena `APP_DEBUG=true` menampilkan detail error ke pengunjung.

## Lisensi

Dibangun dengan [Laravel](https://laravel.com), berlisensi [MIT](https://opensource.org/licenses/MIT).
