<?php

namespace App\Http\Controllers;

use App\Models\Kerjasama;
use App\Models\Pkl;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return match (true) {
            $user->stafInternal() => $this->dashboardStaf($user),
            $user->role === 'siswa' => $this->dashboardSiswa($user),
            $user->role === 'industri' => $this->dashboardIndustri($user),
            $user->role === 'tamu' => $this->dashboardTamu($user),
            default => view('dashboard', ['tipe' => 'lainnya']),
        };
    }

    /** Dashboard staf internal (Admin, TU, Kepsek, Waka, Bendahara): lihat ringkasan operasional sekolah. */
    private function dashboardStaf($user)
    {
        $stat = [
            'surat_masuk_menunggu' => SuratMasuk::where('status', 'menunggu_disposisi')->count(),
            'surat_keluar_menunggu' => SuratKeluar::where('status', 'menunggu_acc')->count(),
            'pkl_berlangsung' => Pkl::where('status', 'berlangsung')->count(),
            'pkl_menunggu' => Pkl::where('status', 'menunggu_persetujuan')->count(),
            'sertifikat_siap' => Pkl::where('status', 'selesai')->count(),
            'mitra_aktif' => Kerjasama::all()->filter(fn ($k) => $k->status() === 'aktif')->count(),
        ];

        $aktivitasTerbaru = collect()
            ->concat(SuratMasuk::where('status', 'sudah_didisposisi')->latest('updated_at')->take(3)->get()->map(fn ($s) => [
                'teks' => "Surat masuk {$s->nomor_agenda} dari {$s->pengirim} telah didisposisikan.",
                'tanggal' => $s->updated_at,
            ]))
            ->concat(Pkl::where('status', 'selesai')->with('siswa')->latest('updated_at')->take(3)->get()->map(fn ($p) => [
                'teks' => "Sertifikat PKL a.n. {$p->siswa->nama} berhasil diterbitkan.",
                'tanggal' => $p->updated_at,
            ]))
            ->concat(SuratKeluar::where('status', 'disetujui')->latest('updated_at')->take(3)->get()->map(fn ($s) => [
                'teks' => "Surat \"{$s->perihal}\" disetujui Kepala Sekolah.",
                'tanggal' => $s->updated_at,
            ]))
            ->sortByDesc('tanggal')
            ->take(5);

        $perluTindakLanjut = collect();

        if ($user->bolehKelola(['admin_tu'])) {
            $perluTindakLanjut = $perluTindakLanjut
                ->concat(SuratMasuk::where('status', 'menunggu_disposisi')->latest('tanggal_terima')->take(4)->get()->map(fn ($s) => [
                    'judul' => $s->perihal, 'sub' => "Surat masuk \u{b7} {$s->nomor_agenda}",
                    'url' => route('surat-masuk.show', $s),
                ]));
        }

        if ($user->bolehKelola(['kepala_sekolah'])) {
            $perluTindakLanjut = $perluTindakLanjut
                ->concat(SuratKeluar::where('status', 'menunggu_acc')->latest('tanggal_surat')->take(4)->get()->map(fn ($s) => [
                    'judul' => $s->perihal, 'sub' => "Surat keluar \u{b7} {$s->nomor_surat}",
                    'url' => route('surat-keluar.show', $s),
                ]))
                ->concat(Pkl::where('status', 'menunggu_persetujuan')->with('siswa')->take(4)->get()->map(fn ($p) => [
                    'judul' => 'Pengajuan PKL ' . $p->siswa->nama, 'sub' => 'PKL \u{b7} menunggu persetujuan',
                    'url' => route('pkl.show', $p),
                ]));
        }

        return view('dashboard', [
            'tipe' => 'staf',
            'stat' => $stat,
            'aktivitasTerbaru' => $aktivitasTerbaru,
            'perluTindakLanjut' => $perluTindakLanjut->take(5),
        ]);
    }

    /** Dashboard Siswa: cuma lihat status PKL & sertifikat miliknya sendiri. */
    private function dashboardSiswa($user)
    {
        $siswa = $user->siswa;
        $pkls = $siswa ? Pkl::with('industri')->where('siswa_id', $siswa->id)->latest('tanggal_mulai')->get() : collect();

        return view('dashboard', [
            'tipe' => 'siswa',
            'siswa' => $siswa,
            'pkls' => $pkls,
        ]);
    }

    /** Dashboard Mitra Industri: cuma lihat siswa PKL & MoU miliknya sendiri. */
    private function dashboardIndustri($user)
    {
        $pkls = Pkl::with('siswa')->where('industri_id', $user->industri_id)->latest('tanggal_mulai')->get();
        $kerjasamas = Kerjasama::where('industri_id', $user->industri_id)->get();

        return view('dashboard', [
            'tipe' => 'industri',
            'pkls' => $pkls,
            'kerjasamas' => $kerjasamas,
        ]);
    }

    /** Dashboard Tamu: cuma lihat status surat yang pernah dia kirim sendiri. */
    private function dashboardTamu($user)
    {
        $suratSaya = SuratMasuk::where('created_by', $user->id)->latest('tanggal_terima')->take(5)->get();

        return view('dashboard', [
            'tipe' => 'tamu',
            'suratSaya' => $suratSaya,
        ]);
    }
}
