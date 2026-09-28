@extends('layouts.app')

@section('title', 'Detail Surat Keluar')

@section('content')
<div class="card" style="max-width:680px;">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;flex-wrap:wrap;">
        <table class="simple">
            <tbody>
                <tr><td style="width:170px;color:var(--text-muted);">Nomor Surat</td><td class="cell-title">{{ $suratKeluar->nomor_surat }}</td></tr>
                <tr><td style="color:var(--text-muted);">Jenis Surat</td><td>{{ $suratKeluar->labelJenis() }}</td></tr>
                <tr><td style="color:var(--text-muted);">Perihal</td><td>{{ $suratKeluar->perihal }}</td></tr>
                <tr><td style="color:var(--text-muted);">Ditujukan Kepada</td><td>{{ $suratKeluar->tujuan }}</td></tr>
                <tr><td style="color:var(--text-muted);">Isi Surat</td><td style="white-space:pre-line;">{{ $suratKeluar->isi_surat ?: '-' }}</td></tr>
                <tr><td style="color:var(--text-muted);">Tanggal</td><td>{{ $suratKeluar->tanggal_surat->translatedFormat('d F Y') }}</td></tr>
                <tr><td style="color:var(--text-muted);">Status</td><td>
                    @if ($suratKeluar->status === 'draft')<span class="badge-pill badge-gray">Draft</span>
                    @elseif ($suratKeluar->status === 'menunggu_acc')<span class="badge-pill badge-orange">Menunggu Persetujuan Kepsek</span>
                    @elseif ($suratKeluar->status === 'disetujui')<span class="badge-pill badge-green">Disetujui &amp; Terkirim</span>
                    @else<span class="badge-pill badge-red">Dikembalikan</span>
                    @endif
                </td></tr>
                @if ($suratKeluar->status === 'dikembalikan' && $suratKeluar->catatan_kepsek)
                    <tr><td style="color:var(--text-muted);">Catatan Kepsek</td><td>{{ $suratKeluar->catatan_kepsek }}</td></tr>
                @endif
                <tr><td style="color:var(--text-muted);">Dibuat Oleh</td><td>{{ $suratKeluar->pembuat->name ?? '-' }}</td></tr>
                <tr><td style="color:var(--text-muted);">Disetujui Oleh</td><td>{{ $suratKeluar->penyetuju->name ?? '-' }}</td></tr>
                <tr><td style="color:var(--text-muted);">Lampiran</td><td>
                    @if ($suratKeluar->file_path)<a href="{{ Storage::url($suratKeluar->file_path) }}" target="_blank">Lihat file</a>@else - @endif
                </td></tr>
            </tbody>
        </table>
    </div>
    <div style="display:flex;gap:.6rem;margin-top:1.1rem;flex-wrap:wrap;">
        <a href="{{ route('surat-keluar.index') }}" class="btn btn-outline btn-sm">&larr; Kembali</a>
        <a href="{{ route('surat-keluar.cetak', $suratKeluar) }}" target="_blank" class="btn btn-navy btn-sm">Cetak Dokumen Surat</a>
    </div>
</div>
@endsection
