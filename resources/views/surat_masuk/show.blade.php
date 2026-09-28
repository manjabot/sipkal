@extends('layouts.app')

@section('title', 'Detail Surat Masuk')

@section('content')
<div class="two-col">
    <div class="card">
        <h3 style="font-size:1.05rem;margin-bottom:1rem;">{{ $suratMasuk->nomor_agenda }}</h3>
        <table class="simple">
            <tbody>
                <tr><td style="width:150px;color:var(--text-muted);">Pengirim</td><td class="cell-title">{{ $suratMasuk->pengirim }}</td></tr>
                <tr><td style="color:var(--text-muted);">Perihal</td><td>{{ $suratMasuk->perihal }}</td></tr>
                <tr><td style="color:var(--text-muted);">No. Surat Asli</td><td>{{ $suratMasuk->nomor_surat ?: '-' }}</td></tr>
                <tr><td style="color:var(--text-muted);">Tanggal Diterima</td><td>{{ $suratMasuk->tanggal_terima->translatedFormat('d F Y') }}</td></tr>
                <tr><td style="color:var(--text-muted);">Status</td><td>
                    @if ($suratMasuk->status === 'sudah_didisposisi')
                        <span class="badge-pill badge-green">Sudah Didisposisi</span>
                    @else
                        <span class="badge-pill badge-orange">Menunggu Disposisi</span>
                    @endif
                </td></tr>
                <tr><td style="color:var(--text-muted);">Lampiran</td><td>
                    @if ($suratMasuk->file_path)
                        <a href="{{ Storage::url($suratMasuk->file_path) }}" target="_blank">Lihat file</a>
                    @else - @endif
                </td></tr>
            </tbody>
        </table>
        <a href="{{ route('surat-masuk.index') }}" class="btn btn-outline btn-sm" style="margin-top:1rem;">&larr; Kembali</a>
    </div>

    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.85rem;">
            <h3 style="font-size:1.05rem;">Disposisi</h3>
            @if ($u->bolehKelola(['admin_tu']) && $suratMasuk->status !== 'sudah_didisposisi')
                <a href="{{ route('disposisi.create', $suratMasuk) }}" class="btn btn-sm btn-orange">+ Buat Disposisi</a>
            @endif
        </div>

        @forelse ($suratMasuk->disposisis as $d)
            <div style="border:1px solid var(--card-border);border-radius:.5rem;padding:.75rem .9rem;margin-bottom:.6rem;">
                <div style="display:flex;justify-content:space-between;">
                    <strong>{{ $d->unit_tujuan }}</strong>
                    <span class="cell-sub">{{ $d->tanggal_disposisi->translatedFormat('d M Y') }}</span>
                </div>
                @if ($d->instruksi)
                    <div class="cell-sub" style="margin-top:.35rem;">{{ $d->instruksi }}</div>
                @endif
            </div>
        @empty
            <div class="empty-state">Belum ada disposisi untuk surat ini.</div>
        @endforelse
    </div>
</div>
@endsection
