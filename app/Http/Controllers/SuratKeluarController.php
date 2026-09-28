<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use Illuminate\Http\Request;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->stafInternal(), 403, 'Surat keluar internal hanya bisa diakses oleh staf sekolah.');

        $suratKeluars = SuratKeluar::with(['pembuat', 'penyetuju'])
            ->when($request->q, fn ($q) => $q->where(function ($w) use ($request) {
                $w->where('perihal', 'like', "%{$request->q}%")
                    ->orWhere('tujuan', 'like', "%{$request->q}%")
                    ->orWhere('nomor_surat', 'like', "%{$request->q}%");
            }))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest('tanggal_surat')
            ->paginate(10)
            ->withQueryString();

        return view('surat_keluar.index', compact('suratKeluars'));
    }

    public function create()
    {
        abort_unless(auth()->user()->bolehKelola(['admin_tu']), 403, 'Hanya Tata Usaha yang bisa membuat draft surat keluar.');

        return view('surat_keluar.create');
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->bolehKelola(['admin_tu']), 403);

        $data = $request->validate([
            'jenis_surat' => ['required', 'in:undangan,permohonan,keterangan,pemberitahuan,lainnya'],
            'perihal' => ['required', 'string'],
            'tujuan' => ['required', 'string'],
            'isi_surat' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'max:5120'],
        ]);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('surat_keluar', 'public');
        }

        $data['nomor_surat'] = SuratKeluar::nomorSuratBerikutnya();
        $data['tanggal_surat'] = now()->toDateString();
        $data['created_by'] = auth()->id();
        $data['status'] = 'draft';

        SuratKeluar::create($data);

        return redirect()->route('surat-keluar.index')->with('success', 'Draft surat keluar berhasil dibuat.');
    }

    public function show(SuratKeluar $suratKeluar)
    {
        abort_unless(auth()->user()->stafInternal(), 403);

        return view('surat_keluar.show', compact('suratKeluar'));
    }

    public function cetak(SuratKeluar $suratKeluar)
    {
        abort_unless(auth()->user()->stafInternal(), 403);

        return view('surat_keluar.cetak', compact('suratKeluar'));
    }

    /** TU mengajukan draft ke Kepala Sekolah. */
    public function ajukan(SuratKeluar $suratKeluar)
    {
        abort_unless(auth()->user()->bolehKelola(['admin_tu']), 403);
        abort_unless($suratKeluar->status === 'draft', 400, 'Surat ini sudah diajukan sebelumnya.');

        $suratKeluar->update(['status' => 'menunggu_acc']);

        return back()->with('success', 'Draft surat diajukan ke Kepala Sekolah untuk persetujuan.');
    }

    /** Kepala Sekolah menyetujui surat keluar. */
    public function setujui(Request $request, SuratKeluar $suratKeluar)
    {
        abort_unless(auth()->user()->bolehKelola(['kepala_sekolah']), 403, 'Hanya Kepala Sekolah yang bisa menyetujui surat keluar.');
        abort_unless($suratKeluar->status === 'menunggu_acc', 400);

        $suratKeluar->update([
            'status' => 'disetujui',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Surat keluar disetujui dan siap dikirim.');
    }

    /** Kepala Sekolah mengembalikan surat keluar ke TU untuk direvisi. */
    public function tolak(Request $request, SuratKeluar $suratKeluar)
    {
        abort_unless(auth()->user()->bolehKelola(['kepala_sekolah']), 403);
        abort_unless($suratKeluar->status === 'menunggu_acc', 400);

        $data = $request->validate([
            'catatan_kepsek' => ['nullable', 'string'],
        ]);

        $suratKeluar->update([
            'status' => 'dikembalikan',
            'catatan_kepsek' => $data['catatan_kepsek'] ?? null,
        ]);

        return back()->with('success', 'Surat keluar dikembalikan ke Tata Usaha untuk direvisi.');
    }

    public function destroy(SuratKeluar $suratKeluar)
    {
        abort_unless(auth()->user()->bolehKelola(['admin_tu']), 403);

        $suratKeluar->delete();

        return redirect()->route('surat-keluar.index')->with('success', 'Surat keluar berhasil dihapus.');
    }
}
