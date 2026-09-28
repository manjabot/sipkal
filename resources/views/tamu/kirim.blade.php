@extends('layouts.app')

@section('title', 'Kirim Surat')

@section('content')
<div class="card" style="max-width:640px;">
    <h2 style="font-size:1.2rem;margin-bottom:.3rem;">Kirim Surat ke Sekolah</h2>
    <p style="color:var(--text-muted);font-size:.88rem;margin-bottom:1.25rem;">
        Surat yang kamu kirim di sini akan langsung tercatat di buku agenda surat masuk sekolah,
        sama seperti surat yang diantar langsung. Kamu tidak perlu datang ke sekolah.
    </p>

    <form action="{{ route('tamu.kirim.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="field">
            <label>Perihal</label>
            <input type="text" name="perihal" placeholder="Contoh: Permohonan Izin Tidak Masuk Sekolah" value="{{ old('perihal') }}" required>
        </div>
        <div class="field">
            <label>Nomor Surat (opsional, jika ada)</label>
            <input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}">
        </div>
        <div class="field">
            <label>Lampiran Surat (opsional)</label>
            <input type="file" name="file">
            <div class="hint">Pengirim otomatis tercatat sebagai: {{ auth()->user()->name }} ({{ auth()->user()->instansi_asal }})</div>
        </div>

        <button type="submit" class="btn btn-navy">Kirim Surat</button>
    </form>
</div>
@endsection
