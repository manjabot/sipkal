<?php

namespace App\Http\Controllers;

use App\Models\Sertifikat;
use Illuminate\Http\Request;

class SertifikatController extends Controller
{
    public function index(Request $request)
    {
        abort_if(auth()->user()->role === 'tamu', 403, 'Menu ini tidak tersedia untuk akun Tamu.');

        $sertifikats = Sertifikat::with('pembuat')
            ->when($request->q, fn ($q) => $q->where(function ($w) use ($request) {
                $w->where('nama_penerima', 'like', "%{$request->q}%")
                    ->orWhere('jenis_kegiatan', 'like', "%{$request->q}%")
                    ->orWhere('nomor_sertifikat', 'like', "%{$request->q}%");
            }))
            ->latest('tanggal_terbit')
            ->paginate(10)
            ->withQueryString();

        return view('sertifikat.index', compact('sertifikats'));
    }

    public function create()
    {
        abort_unless(auth()->user()->bolehKelola(['admin_tu']), 403);

        return view('sertifikat.create');
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->bolehKelola(['admin_tu']), 403);

        $data = $request->validate([
            'jenis_kegiatan' => ['required', 'string'],
            'nama_penerima' => ['required', 'string'],
            'identitas_penerima' => ['nullable', 'string'],
            'predikat' => ['nullable', 'string'],
            'keterangan' => ['nullable', 'string'],
            'tanggal_terbit' => ['required', 'date'],
        ]);

        $data['nomor_sertifikat'] = Sertifikat::nomorBerikutnya();
        $data['dibuat_oleh'] = auth()->id();

        Sertifikat::create($data);

        return redirect()->route('sertifikat.index')->with('success', 'Sertifikat berhasil diterbitkan.');
    }

    public function show(Sertifikat $sertifikat)
    {
        abort_if(auth()->user()->role === 'tamu', 403);

        return view('sertifikat.show', compact('sertifikat'));
    }

    public function cetak(Sertifikat $sertifikat)
{
    abort_if(auth()->user()->role === 'tamu', 403);

    return view('sertifikat.cetak', compact('sertifikat'));
}

    public function destroy(Sertifikat $sertifikat)
    {
        abort_unless(auth()->user()->bolehKelola(['admin_tu']), 403);

        $sertifikat->delete();

        return redirect()->route('sertifikat.index')->with('success', 'Sertifikat berhasil dihapus.');
    }
}
