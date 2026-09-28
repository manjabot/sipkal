<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kerjasama extends Model
{
    use HasFactory;

    protected $fillable = [
        'industri_id', 'nomor_dokumen', 'bidang', 'ruang_lingkup', 'mulai_mou', 'berakhir_mou', 'dokumen_mou',
    ];

    protected $casts = [
        'mulai_mou' => 'date',
        'berakhir_mou' => 'date',
    ];

    public function industri()
    {
        return $this->belongsTo(Industri::class);
    }

    /**
     * Status keaktifan MoU dihitung otomatis dari tanggal, bukan diinput manual.
     * aktif / akan_berakhir (<=180 hari lagi) / berakhir
     */
    public function status(): string
    {
        $sisaHari = now()->startOfDay()->diffInDays($this->berakhir_mou, false);

        if ($sisaHari < 0) {
            return 'berakhir';
        }

        if ($sisaHari <= 180) {
            return 'akan_berakhir';
        }

        return 'aktif';
    }

    public function labelStatus(): string
    {
        return match ($this->status()) {
            'aktif' => 'Aktif',
            'akan_berakhir' => 'Akan Berakhir',
            'berakhir' => 'Berakhir',
        };
    }

    public static function nomorDokumenBerikutnya(): string
    {
        $tahun = now()->year;
        $terakhir = static::query()
            ->selectRaw("MAX(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(nomor_dokumen, '/', 2), '.', -1) AS UNSIGNED)) as maksimal")
            ->value('maksimal');

        $urut = (int) $terakhir + 1;

        return "421.4/PKS.{$urut}/SMKN4-TPI/{$tahun}";
    }
}
