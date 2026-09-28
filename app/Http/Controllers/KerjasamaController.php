<?php

namespace App\Http\Controllers;

use App\Models\Industri;
use App\Models\Kerjasama;
use Illuminate\Http\Request;

class KerjasamaController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        abort_if(in_array($user->role, ['siswa', 'tamu'], true), 403, 'Menu ini tidak tersedia untuk peranmu.');

        $kerjasamas = Kerjasama::with('industri')
            ->when($request->q, fn ($q) => $q->whereHas('industri', fn ($i) => $i->where('nama_perusahaan', 'like', "%{$request->q}%")))
            // Mitra industri hanya boleh lihat MoU miliknya sendiri.
            ->when($user->role === 'industri', fn ($q) => $q->where('industri_id', $user->industri_id))
            ->latest('mulai_mou')
            ->when($request->status, function ($q) use ($request) {
            $hariIni = now()->startOfDay();
            return match ($request->status) {
                'berakhir' => $q->whereDate('berakhir_mou', '<', $hariIni),
                'akan_berakhir' => $q->whereDate('berakhir_mou', '>=', $hariIni)
                    ->whereDate('berakhir_mou', '<=', $hariIni->copy()->addDays(180)),
                'aktif' => $q->whereDate('berakhir_mou', '>', $hariIni->copy()->addDays(180)),
                default => $q,
            };
        })
            ->paginate(10)
            ->withQueryString();

        

        return view('kerjasama.index', compact('kerjasamas'));
    }

    public function create()
    {
        abort_unless(auth()->user()->bolehKelola(['admin_tu', 'waka']), 403);

        $industris = Industri::orderBy('nama_perusahaan')->get();

        return view('kerjasama.create', compact('industris'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->bolehKelola(['admin_tu', 'waka']), 403);

        $data = $request->validate([
            'nama_perusahaan' => ['required', 'string'],
            'bidang' => ['required', 'string'],
            'ruang_lingkup' => ['nullable', 'string'],
            'mulai_mou' => ['required', 'date'],
            'berakhir_mou' => ['required', 'date', 'after:mulai_mou'],
            'dokumen' => ['nullable', 'file', 'max:5120'],
        ]);

        $industri = Industri::firstOrCreate(
            ['nama_perusahaan' => $data['nama_perusahaan']],
            ['bidang_usaha' => $data['bidang']]
        );

        $dokumenPath = null;
        if ($request->hasFile('dokumen')) {
            $dokumenPath = $request->file('dokumen')->store('kerjasama', 'public');
        }

        Kerjasama::create([
            'industri_id' => $industri->id,
            'nomor_dokumen' => Kerjasama::nomorDokumenBerikutnya(),
            'bidang' => $data['bidang'],
            'ruang_lingkup' => $data['ruang_lingkup'] ?? null,
            'mulai_mou' => $data['mulai_mou'],
            'berakhir_mou' => $data['berakhir_mou'],
            'dokumen_mou' => $dokumenPath,
        ]);

        return redirect()->route('kerjasama.index')->with('success', 'Data mitra & MoU berhasil disimpan.');
    }

    public function show(Kerjasama $kerjasama)
    {
        $user = auth()->user();
        abort_if(in_array($user->role, ['siswa', 'tamu'], true), 403);

        if ($user->role === 'industri' && $kerjasama->industri_id !== $user->industri_id) {
            abort(403, 'Kamu hanya bisa melihat data kerjasama perusahaanmu sendiri.');
        }

        return view('kerjasama.show', compact('kerjasama'));
    }

    public function destroy(Kerjasama $kerjasama)
    {
        abort_unless(auth()->user()->bolehKelola(['admin_tu', 'waka']), 403);

        $kerjasama->delete();

        return redirect()->route('kerjasama.index')->with('success', 'Data kerjasama berhasil dihapus.');
    }
}
