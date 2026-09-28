@extends('layouts.app')

@section('title', 'Detail Sertifikat')

@section('content')
<div class="card" style="max-width:600px;">
    <table class="simple">
        <tbody>
            <tr><td style="width:170px;color:var(--text-muted);">Nomor Sertifikat</td><td class="cell-title">{{ $sertifikat->nomor_sertifikat }}</td></tr>
            <tr><td style="color:var(--text-muted);">Jenis Kegiatan</td><td>{{ $sertifikat->jenis_kegiatan }}</td></tr>
            <tr><td style="color:var(--text-muted);">Penerima</td><td>{{ $sertifikat->nama_penerima }}</td></tr>
            <tr><td style="color:var(--text-muted);">Identitas</td><td>{{ $sertifikat->identitas_penerima ?: '-' }}</td></tr>
            <tr><td style="color:var(--text-muted);">Predikat</td><td>{{ $sertifikat->predikat ?: '-' }}</td></tr>
            <tr><td style="color:var(--text-muted);">Tanggal Terbit</td><td>{{ $sertifikat->tanggal_terbit->translatedFormat('d F Y') }}</td></tr>
            <tr><td style="color:var(--text-muted);">Diterbitkan Oleh</td><td>{{ $sertifikat->pembuat->name ?? '-' }}</td></tr>
        </tbody>
    </table>
    <div style="display:flex;gap:.6rem;margin-top:1.1rem;flex-wrap:wrap;">
        <a href="{{ route('sertifikat.index') }}" class="btn btn-outline btn-sm">&larr; Kembali</a>
        <a href="{{ route('sertifikat.cetak', $sertifikat) }}" target="_blank" class="btn btn-navy btn-sm">Cetak Sertifikat</a>
    </div>
</div>
@endsection
