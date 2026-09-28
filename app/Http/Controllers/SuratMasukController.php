<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Illuminate\Http\Request;

class SuratMasukController extends Controller
{
    private function pastikanStafInternal(): void
    {
        abort_unless(auth()->user()->stafInternal(), 403, 'Surat masuk internal hanya bisa diakses oleh staf sekolah.');
    }

    public function index(Request $request)
    {
        $this->pastikanStafInternal();

        $suratMasuks = SuratMasuk::with('pembuat')
            ->when($request->q, fn ($q) => $q->where(function ($w) use ($request) {
                $w->where('perihal', 'like', "%{$request->q}%")
                    ->orWhere('pengirim', 'like', "%{$request->q}%")
                    ->orWhere('nomor_agenda', 'like', "%{$request->q}%");
            }))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest('tanggal_terima')
            ->paginate(10)
            ->withQueryString();

        return view('surat_masuk.index', compact('suratMasuks'));
    }

    public function create()
    {
        $this->pastikanStafInternal();
        abort_unless(auth()->user()->bolehKelola(['admin_tu']), 403);

        return view('surat_masuk.create');
    }

    public function store(Request $request)
    {
        $this->pastikanStafInternal();
        abort_unless(auth()->user()->bolehKelola(['admin_tu']), 403);

        $data = $request->validate([
            'pengirim' => ['required', 'string'],
            'perihal' => ['required', 'string'],
            'nomor_surat' => ['nullable', 'string'],
            'tanggal_terima' => ['required', 'date'],
            'file' => ['nullable', 'file', 'max:5120'],
        ]);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('surat_masuk', 'public');
        }

        $data['nomor_agenda'] = SuratMasuk::nomorAgendaBerikutnya();
        $data['created_by'] = auth()->id();
        $data['status'] = 'menunggu_disposisi';

        SuratMasuk::create($data);

        return redirect()->route('surat-masuk.index')->with('success', 'Surat berhasil dicatat ke agenda dengan nomor ' . $data['nomor_agenda'] . '.');
    }

    public function show(SuratMasuk $suratMasuk)
    {
        $this->pastikanStafInternal();

        $suratMasuk->load('disposisis.pembuat');

        return view('surat_masuk.show', compact('suratMasuk'));
    }

    public function destroy(SuratMasuk $suratMasuk)
    {
        abort_unless(auth()->user()->bolehKelola(['admin_tu']), 403);

        $suratMasuk->delete();

        return redirect()->route('surat-masuk.index')->with('success', 'Surat masuk berhasil dihapus.');
    }
}
