<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Illuminate\Http\Request;

class TamuSuratController extends Controller
{
    private function pastikanTamu(): void
    {
        abort_unless(auth()->user()->role === 'tamu', 403, 'Halaman ini khusus akun Tamu.');
    }

    /** Riwayat surat yang pernah dikirim oleh Tamu yang login. */
    public function index()
    {
        $this->pastikanTamu();

        $suratSaya = SuratMasuk::where('created_by', auth()->id())
            ->latest('tanggal_terima')
            ->paginate(10);

        return view('tamu.riwayat', compact('suratSaya'));
    }

    public function create()
    {
        $this->pastikanTamu();

        return view('tamu.kirim');
    }

    public function store(Request $request)
    {
        $this->pastikanTamu();

        $data = $request->validate([
            'perihal' => ['required', 'string'],
            'nomor_surat' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'max:5120'],
        ]);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('surat_masuk', 'public');
        }

        $data['nomor_agenda'] = SuratMasuk::nomorAgendaBerikutnya();
        $data['pengirim'] = auth()->user()->name . ' (' . (auth()->user()->instansi_asal ?: 'Tamu') . ')';
        $data['tanggal_terima'] = now()->toDateString();
        $data['status'] = 'menunggu_disposisi';
        $data['created_by'] = auth()->id();

        SuratMasuk::create($data);

        return redirect()->route('tamu.riwayat')->with('success', 'Surat berhasil dikirim ke sekolah dengan nomor agenda ' . $data['nomor_agenda'] . '. Kamu bisa memantau statusnya di halaman ini.');
    }
}
