<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_agenda', 'nomor_surat', 'pengirim', 'perihal', 'tanggal_terima',
        'file_path', 'status', 'created_by',
    ];

    protected $casts = [
        'tanggal_terima' => 'date',
    ];

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function disposisis()
    {
        return $this->hasMany(Disposisi::class);
    }

    public static function nomorAgendaBerikutnya(): string
    {
        $terakhir = static::query()
            ->selectRaw("MAX(CAST(SUBSTRING(nomor_agenda, 4) AS UNSIGNED)) as maksimal")
            ->value('maksimal');

        return 'SM-' . str_pad((int) $terakhir + 1, 3, '0', STR_PAD_LEFT);
    }
}
