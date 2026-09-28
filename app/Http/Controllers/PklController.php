<?php

namespace App\Http\Controllers;

use App\Models\Industri;
use App\Models\Pkl;
use App\Models\Siswa;
use Illuminate\Http\Request;

class PklController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        abort_if($user->role === 'tamu', 403, 'Menu ini tidak tersedia untuk akun Tamu.');

        $pkls = Pkl::with(['siswa', 'industri'])
            ->when($request->q, fn ($q) => $q->where(function ($w) use ($request) {
                $w->whereHas('siswa', fn ($s) => $s->where('nama', 'like', "%{$request->q}%"))
                    ->orWhereHas('industri', fn ($i) => $i->where('nama_perusahaan', 'like', "%{$request->q}%"));
            }))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            // Siswa hanya boleh lihat data PKL miliknya sendiri.
            ->when($user->role === 'siswa', fn ($q) => $q->whereHas('siswa', fn ($s) => $s->where('user_id', $user->id)))
            // Mitra industri hanya boleh lihat siswa PKL yang ditempatkan di perusahaannya sendiri.
            ->when($user->role === 'industri', fn ($q) => $q->where('industri_id', $user->industri_id))
            ->latest('tanggal_mulai')
            ->paginate(10)
            ->withQueryString();

        return view('pkl.index', compact('pkls'));
    }

    public function create()
    {
        abort_unless(auth()->user()->bolehKelola(['admin_tu']), 403);

        $industris = Industri::orderBy('nama_perusahaan')->get();

        return view('pkl.create', compact('industris'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->bolehKelola(['admin_tu']), 403);

        $data = $request->validate([
            'nama_siswa' => ['required', 'string'],
            'nis' => ['required', 'string'],
            'tempat_lahir' => ['nullable', 'string'],
            'tanggal_lahir' => ['nullable', 'date'],
            'industri_id' => ['required', 'exists:industris,id'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
        ]);

        $siswa = Siswa::firstOrCreate(
            ['nis' => $data['nis']],
            [
                'nama' => $data['nama_siswa'],
                'tempat_lahir' => $data['tempat_lahir'] ?? null,
                'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
            ]
        );

        Pkl::create([
            'siswa_id' => $siswa->id,
            'industri_id' => $data['industri_id'],
            'tanggal_mulai' => $data['tanggal_mulai'],
            'tanggal_selesai' => $data['tanggal_selesai'],
            'status' => 'menunggu_persetujuan',
        ]);

        return redirect()->route('pkl.index')->with('success', 'Data PKL siswa berhasil disimpan, menunggu persetujuan Kepala Sekolah.');
    }

    public function show(Pkl $pkl)
    {
        $user = auth()->user();
        abort_if($user->role === 'tamu', 403);

        if ($user->role === 'siswa' && optional($pkl->siswa)->user_id !== $user->id) {
            abort(403, 'Kamu hanya bisa melihat data PKL milikmu sendiri.');
        }

        if ($user->role === 'industri' && $pkl->industri_id !== $user->industri_id) {
            abort(403, 'Kamu hanya bisa melihat data PKL siswa yang ditempatkan di perusahaanmu.');
        }

        return view('pkl.show', compact('pkl'));
    }

    /** Kepala Sekolah menyetujui PKL, siswa mulai berangkat. */
    public function setujui(Pkl $pkl)
    {
        abort_unless(auth()->user()->bolehKelola(['kepala_sekolah']), 403, 'Hanya Kepala Sekolah yang bisa menyetujui PKL.');
        abort_unless($pkl->status === 'menunggu_persetujuan', 400);

        $pkl->update(['status' => 'berlangsung']);

        return back()->with('success', 'PKL disetujui, status diperbarui menjadi Berlangsung.');
    }

    /** Kepala Sekolah menuntaskan PKL & mengesahkan sertifikat. */
    public function selesaikan(Request $request, Pkl $pkl)
    {
        abort_unless(auth()->user()->bolehKelola(['kepala_sekolah']), 403, 'Hanya Kepala Sekolah yang bisa mengesahkan sertifikat PKL.');
        abort_unless($pkl->status === 'berlangsung', 400);

        $data = $request->validate([
            'predikat' => ['required', 'in:Sangat Baik,Baik,Cukup'],
        ]);

        $pkl->update([
            'status' => 'selesai',
            'predikat' => $data['predikat'],
            'nomor_sertifikat' => Pkl::nomorSertifikatBerikutnya(),
        ]);

        return back()->with('success', 'PKL selesai & sertifikat berhasil disahkan.');
    }

    public function destroy(Pkl $pkl)
    {
        abort_unless(auth()->user()->bolehKelola(['admin_tu']), 403);

        $pkl->delete();

        return redirect()->route('pkl.index')->with('success', 'Data PKL berhasil dihapus.');
    }
}
