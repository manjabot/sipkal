<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_surat', 'jenis_surat', 'perihal', 'tujuan', 'isi_surat', 'tanggal_surat', 'file_path',
        'status', 'created_by', 'approved_by', 'approved_at', 'catatan_kepsek',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'approved_at' => 'datetime',
    ];

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function penyetuju()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function labelJenis(): string
    {
        return match ($this->jenis_surat) {
            'undangan' => 'Surat Undangan',
            'permohonan' => 'Surat Permohonan',
            'keterangan' => 'Surat Keterangan',
            'pemberitahuan' => 'Surat Pemberitahuan',
            default => 'Surat Lainnya',
        };
    }

    /** Kalimat pembuka badan surat, menyesuaikan jenis surat. */
    public function pembukaSurat(): string
    {
        return match ($this->jenis_surat) {
            'undangan' => 'Dengan hormat, sehubungan dengan agenda sekolah, kami mengundang Bapak/Ibu/Saudara untuk:',
            'permohonan' => 'Dengan hormat, melalui surat ini kami mengajukan permohonan sebagai berikut:',
            'keterangan' => 'Yang bertanda tangan di bawah ini, Kepala SMK Negeri 4 Tanjungpinang, menerangkan bahwa:',
            'pemberitahuan' => 'Dengan hormat, kami sampaikan pemberitahuan sebagai berikut:',
            default => 'Dengan hormat, bersama surat ini kami sampaikan hal berikut:',
        };
    }

    public static function nomorSuratBerikutnya(): string
    {
        $tahun = now()->year;
        $terakhir = static::query()
            ->whereYear('created_at', $tahun)
            ->selectRaw("MAX(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(nomor_surat, '/', 2), '/', -1) AS UNSIGNED)) as maksimal")
            ->value('maksimal');

        $urut = (int) $terakhir + 1;

        return "421.7/{$urut}/SMKN4-TPI/{$tahun}";
    }
}
