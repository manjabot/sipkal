<?php

namespace App\Providers;

use App\Models\Disposisi;
use App\Models\Pkl;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // $u (user yang login) dibagikan ke SEMUA view, bukan cuma layout,
        // karena banyak halaman anak (index/show/create) memakainya untuk cek peran.
        View::composer('*', function ($view) {
            $view->with('u', auth()->user());
        });

        // Badge angka di sidebar, dihitung sekali per request untuk semua halaman ber-layout app.
        View::composer('layouts.app', function ($view) {
            if (! auth()->check()) {
                return;
            }

            $view->with([
                'countSuratMasuk' => SuratMasuk::where('status', 'menunggu_disposisi')->count(),
                'countSuratKeluar' => SuratKeluar::where('status', 'menunggu_acc')->count(),
                'countDisposisi' => Disposisi::whereDate('tanggal_disposisi', '>=', now()->subDays(7))->count(),
                'countPkl' => Pkl::where('status', 'menunggu_persetujuan')->count(),
                'countKerjasama' => \App\Models\Kerjasama::all()->filter(fn ($k) => $k->status() === 'akan_berakhir')->count(),
                'countAkunTamu' => \App\Models\User::where('role', 'tamu')->where('status_akun', 'menunggu_persetujuan')->count(),
            ]);
        });
    }
}
