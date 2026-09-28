@extends('layouts.app')

@section('title', 'Detail Kerjasama')

@section('content')
<div class="card" style="max-width:600px;">
    <table class="simple">
        <tbody>
            <tr><td style="width:170px;color:var(--text-muted);">Nomor Dokumen</td><td class="cell-title">{{ $kerjasama->nomor_dokumen }}</td></tr>
            <tr><td style="color:var(--text-muted);">Mitra Industri</td><td>{{ $kerjasama->industri->nama_perusahaan }}</td></tr>
            <tr><td style="color:var(--text-muted);">Bidang</td><td>{{ $kerjasama->bidang }}</td></tr>
            <tr><td style="color:var(--text-muted);">Ruang Lingkup</td><td>{{ $kerjasama->ruang_lingkup ?: '-' }}</td></tr>
            <tr><td style="color:var(--text-muted);">Alamat</td><td>{{ $kerjasama->industri->alamat ?: '-' }}</td></tr>
            <tr><td style="color:var(--text-muted);">Contact Person</td><td>{{ $kerjasama->industri->contact_person ?: '-' }}</td></tr>
            <tr><td style="color:var(--text-muted);">Masa Berlaku MoU</td><td>{{ $kerjasama->mulai_mou->translatedFormat('d M Y') }} – {{ $kerjasama->berakhir_mou->translatedFormat('d M Y') }}</td></tr>
            <tr><td style="color:var(--text-muted);">Status</td><td>
                @if ($kerjasama->status() === 'aktif')<span class="badge-pill badge-green">Aktif</span>
                @elseif ($kerjasama->status() === 'akan_berakhir')<span class="badge-pill badge-orange">Akan Berakhir</span>
                @else<span class="badge-pill badge-red">Berakhir</span>
                @endif
            </td></tr>
            <tr><td style="color:var(--text-muted);">Dokumen MoU</td><td>
                @if ($kerjasama->dokumen_mou)<a href="{{ Storage::url($kerjasama->dokumen_mou) }}" target="_blank">Lihat dokumen</a>@else - @endif
            </td></tr>
        </tbody>
    </table>
    <a href="{{ route('kerjasama.index') }}" class="btn btn-outline btn-sm" style="margin-top:1rem;">&larr; Kembali</a>
</div>
@endsection
