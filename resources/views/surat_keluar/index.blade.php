@extends('layouts.app')

@section('title', 'Surat Keluar')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.1rem;">
    <div>
        <h2 style="font-size:1.3rem;">Surat Keluar</h2>
        <p style="color:var(--text-muted);font-size:.88rem;margin-top:.25rem;">
            Draft surat keluar diajukan untuk persetujuan dan tanda tangan Kepala Sekolah sebelum dicetak dan dikirim.
        </p>
    </div>
    @if ($u->bolehKelola(['admin_tu']))
        <a href="{{ route('surat-keluar.create') }}" class="btn btn-navy">+ Buat Draft Surat</a>
    @endif
</div>

@if ($u->bolehKelola(['kepala_sekolah']))
<div class="note-banner">
    Surat berstatus <strong>Menunggu ACC</strong> menunggu keputusan kamu. Klik "Setujui" atau "Kembalikan" pada baris terkait.
</div>
@endif

<div class="card">
    <div class="filter-bar">
        <form class="search-input" method="GET">
            <span class="icon">&#128269;</span>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari perihal atau tujuan...">
        </form>
        <form class="filter-select" method="GET">
            <input type="hidden" name="q" value="{{ request('q') }}">
            <select name="status" onchange="this.form.submit()">
                <option value="">Semua status</option>
                <option value="draft" @selected(request('status')==='draft')>Draft</option>
                <option value="menunggu_acc" @selected(request('status')==='menunggu_acc')>Menunggu ACC</option>
                <option value="disetujui" @selected(request('status')==='disetujui')>Disetujui</option>
                <option value="dikembalikan" @selected(request('status')==='dikembalikan')>Dikembalikan</option>
            </select>
        </form>
    </div>

    <div class="table-wrap">
        <table class="simple">
            <thead>
                <tr><th>No. Surat</th><th>Perihal &amp; Tujuan</th><th>Status</th><th style="width:230px;"></th></tr>
            </thead>
            <tbody>
            @forelse ($suratKeluars as $s)
                <tr>
                    <td>{{ $s->nomor_surat }}</td>
                    <td>
                        <div class="cell-title">{{ $s->perihal }}</div>
                        <div class="cell-sub">Kepada: {{ $s->tujuan }}</div>
                    </td>
                    <td>
                        @if ($s->status === 'draft')
                            <span class="badge-pill badge-gray">Draft</span>
                        @elseif ($s->status === 'menunggu_acc')
                            <span class="badge-pill badge-orange">Menunggu Persetujuan Kepsek</span>
                        @elseif ($s->status === 'disetujui')
                            <span class="badge-pill badge-green">Disetujui &amp; Terkirim</span>
                        @else
                            <span class="badge-pill badge-red">Dikembalikan</span>
                        @endif
                    </td>
                    <td style="text-align:right;">
                        @if ($s->status === 'draft' && $u->bolehKelola(['admin_tu']))
                            <form action="{{ route('surat-keluar.ajukan', $s) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-orange">Ajukan ke Kepsek</button>
                            </form>
                        @elseif ($s->status === 'menunggu_acc' && $u->bolehKelola(['kepala_sekolah']))
                            <form action="{{ route('surat-keluar.setujui', $s) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-gold">Setujui</button>
                            </form>
                            <form action="{{ route('surat-keluar.tolak', $s) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-red" onclick="return confirm('Kembalikan surat ini ke Tata Usaha?')">Kembalikan</button>
                            </form>
                        @else
                            <a href="{{ route('surat-keluar.show', $s) }}" class="btn btn-sm btn-ghost">Terarsip</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4"><div class="empty-state">Belum ada surat keluar.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top:1rem;">{{ $suratKeluars->links() }}</div>
@endsection
