<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposisi extends Model
{
    use HasFactory;

    protected $fillable = [
        'surat_masuk_id', 'unit_tujuan', 'instruksi', 'dibuat_oleh', 'tanggal_disposisi',
    ];

    protected $casts = [
        'tanggal_disposisi' => 'date',
    ];

    public function suratMasuk()
    {
        return $this->belongsTo(SuratMasuk::class);
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
