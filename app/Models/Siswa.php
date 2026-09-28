<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nis', 'nama', 'tempat_lahir', 'tanggal_lahir', 'kelas', 'jurusan', 'no_hp'];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pkls()
    {
        return $this->hasMany(Pkl::class);
    }

    /** "Kijang / 08 Oktober 2007" seperti di tabel PKL & sertifikat. */
    public function ttl(): string
    {
        if (! $this->tempat_lahir && ! $this->tanggal_lahir) {
            return '-';
        }

        $tanggal = $this->tanggal_lahir ? $this->tanggal_lahir->translatedFormat('d F Y') : '-';

        return trim(($this->tempat_lahir ?: '-') . ' / ' . $tanggal);
    }
}
