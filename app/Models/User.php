<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'username', 'email', 'password', 'role', 'status_akun',
        'jabatan', 'instansi_asal', 'unit', 'industri_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }

    public function industri()
    {
        return $this->belongsTo(Industri::class);
    }

    /** Label jabatan yang tampil di header/avatar. */
    public function labelJabatan(): string
    {
        if ($this->jabatan) {
            return $this->jabatan;
        }

        return match ($this->role) {
            'admin' => 'Administrator',
            'admin_tu' => 'Staf Tata Usaha',
            'kepala_sekolah' => 'Kepala Sekolah',
            'waka' => $this->unit ?: 'Waka',
            'bendahara' => 'Bendahara',
            'siswa' => 'Siswa',
            'industri' => 'Mitra Industri',
            'tamu' => 'Tamu (Pengirim Surat)',
            default => ucfirst($this->role),
        };
    }

    /** Inisial untuk avatar bulat, mis. "Dedi Kurniawan" -> "DK". */
    public function initials(): string
    {
        $words = preg_split('/\s+/', trim($this->name));
        $letters = array_map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)), array_slice($words, 0, 2));

        return implode('', $letters) ?: '?';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /** True jika role user ada di daftar $roles, ATAU dia Administrator (admin selalu punya akses penuh). */
    public function bolehKelola(array $roles): bool
    {
        return $this->role === 'admin' || in_array($this->role, $roles, true);
    }

    /** True untuk staf internal sekolah (bukan siswa/industri/tamu) yang wajar melihat data persuratan internal. */
    public function stafInternal(): bool
    {
        return in_array($this->role, ['admin', 'admin_tu', 'kepala_sekolah', 'waka', 'bendahara'], true);
    }

    public function isKepalaSekolah(): bool
    {
        return $this->role === 'kepala_sekolah';
    }

    public function sudahAktif(): bool
    {
        return $this->status_akun === 'aktif';
    }

    public function labelStatusAkun(): string
    {
        return match ($this->status_akun) {
            'aktif' => 'Aktif',
            'menunggu_persetujuan' => 'Menunggu Persetujuan',
            'ditolak' => 'Ditolak',
            default => ucfirst($this->status_akun),
        };
    }
}
