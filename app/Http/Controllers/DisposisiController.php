<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;

class DisposisiController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->stafInternal(), 403, 'Disposisi internal hanya bisa diakses oleh staf sekolah.');

        $disposisis = Disposisi::with(['suratMasuk', 'pembuat'])
            ->latest('tanggal_disposisi')
            ->paginate(10);

        return view('disposisi.index', compact('disposisis'));
    }

    public function create(SuratMasuk $suratMasuk)
    {
        abort_unless(auth()->user()->bolehKelola(['admin_tu']), 403);

        return view('disposisi.create', compact('suratMasuk'));
    }

    public function store(Request $request, SuratMasuk $suratMasuk)
    {
        abort_unless(auth()->user()->bolehKelola(['admin_tu']), 403);

        $data = $request->validate([
            'unit_tujuan' => ['required', 'string'],
            'instruksi' => ['nullable', 'string'],
            'tanggal_disposisi' => ['required', 'date'],
        ]);

        $data['surat_masuk_id'] = $suratMasuk->id;
        $data['dibuat_oleh'] = auth()->id();

        Disposisi::create($data);

        $suratMasuk->update(['status' => 'sudah_didisposisi']);

        return redirect()->route('surat-masuk.show', $suratMasuk)->with('success', 'Lembar disposisi berhasil dibuat.');
    }
}
