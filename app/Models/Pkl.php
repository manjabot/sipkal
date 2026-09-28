<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pkl extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id', 'industri_id', 'tanggal_mulai', 'tanggal_selesai',
        'status', 'predikat', 'nomor_sertifikat',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function industri()
    {
        return $this->belongsTo(Industri::class);
    }

    public function sertifikatSiap(): bool
    {
        return $this->status === 'selesai' && $this->nomor_sertifikat;
    }

    public static function nomorSertifikatBerikutnya(): string
    {
        $tahun = now()->year;
        $terakhir = static::query()
            ->whereNotNull('nomor_sertifikat')
            ->selectRaw("MAX(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(nomor_sertifikat, '/', 3), '/', -1) AS UNSIGNED)) as maksimal")
            ->value('maksimal');

        $urut = (int) $terakhir + 1;

        return "B/423.4/P.06.{$urut}/I-SMKN4/PKL/{$tahun}";
    }
}
