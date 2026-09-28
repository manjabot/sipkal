<?php

namespace App\Http\Controllers;

use App\Models\Kerjasama;
use App\Models\Pkl;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;

class LaporanController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->stafInternal(), 403, 'Laporan & rekap hanya bisa diakses oleh staf sekolah.');

        $suratMasuk = [
            'Menunggu Disposisi' => SuratMasuk::where('status', 'menunggu_disposisi')->count(),
            'Sudah Didisposisi' => SuratMasuk::where('status', 'sudah_didisposisi')->count(),
        ];

        $suratKeluar = [
            'Draft' => SuratKeluar::where('status', 'draft')->count(),
            'Menunggu ACC' => SuratKeluar::where('status', 'menunggu_acc')->count(),
            'Disetujui' => SuratKeluar::where('status', 'disetujui')->count(),
            'Dikembalikan' => SuratKeluar::where('status', 'dikembalikan')->count(),
        ];

        $pkl = [
            'Berlangsung' => Pkl::where('status', 'berlangsung')->count(),
            'Menunggu ACC' => Pkl::where('status', 'menunggu_persetujuan')->count(),
            'Selesai' => Pkl::where('status', 'selesai')->count(),
        ];

        $kerjasamaAll = Kerjasama::all();
        $kerjasama = [
            'Aktif' => $kerjasamaAll->filter(fn ($k) => $k->status() === 'aktif')->count(),
            'Akan Berakhir' => $kerjasamaAll->filter(fn ($k) => $k->status() === 'akan_berakhir')->count(),
            'Berakhir' => $kerjasamaAll->filter(fn ($k) => $k->status() === 'berakhir')->count(),
        ];

        return view('laporan.index', compact('suratMasuk', 'suratKeluar', 'pkl', 'kerjasama'));
    }
}
