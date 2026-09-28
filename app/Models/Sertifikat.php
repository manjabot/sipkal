<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_sertifikat', 'jenis_kegiatan', 'nama_penerima', 'identitas_penerima',
        'predikat', 'keterangan', 'tanggal_terbit', 'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
    ];

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public static function nomorBerikutnya(): string
    {
        $tahun = now()->year;
        $terakhir = static::query()
            ->whereYear('created_at', $tahun)
            ->selectRaw("MAX(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(nomor_sertifikat, '/', 3), '.', -1) AS UNSIGNED)) as maksimal")
            ->value('maksimal');

        $urut = (int) $terakhir + 1;

        return "B/425/{$urut}/I-SMKN4/{$tahun}";
    }
}
