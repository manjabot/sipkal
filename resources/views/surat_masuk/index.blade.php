@extends('layouts.app')

@section('title', 'Surat Masuk')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.1rem;">
    <div>
        <h2 style="font-size:1.3rem;">Surat Masuk</h2>
        <p style="color:var(--text-muted);font-size:.88rem;margin-top:.25rem;">
            Buku agenda surat masuk. Setiap surat yang diterima dicatat di sini, lalu diteruskan sebagai lembar disposisi kepada Kepala Sekolah.
        </p>
    </div>
    @if ($u->bolehKelola(['admin_tu']))
        <a href="{{ route('surat-masuk.create') }}" class="btn btn-navy">+ Catat Surat Masuk</a>
    @endif
</div>

<div class="card">
    <div class="filter-bar">
        <form class="search-input" method="GET">
            <span class="icon">&#128269;</span>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari perihal atau pengirim...">
        </form>
        <form class="filter-select" method="GET">
            <input type="hidden" name="q" value="{{ request('q') }}">
            <select name="status" onchange="this.form.submit()">
                <option value="">Semua status</option>
                <option value="menunggu_disposisi" @selected(request('status')==='menunggu_disposisi')>Menunggu Disposisi</option>
                <option value="sudah_didisposisi" @selected(request('status')==='sudah_didisposisi')>Sudah Didisposisi</option>
            </select>
        </form>
    </div>

    <div class="table-wrap">
        <table class="simple">
            <thead>
                <tr><th>No. Agenda</th><th>Pengirim &amp; Perihal</th><th>Tgl Masuk</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
            @forelse ($suratMasuks as $s)
                <tr>
                    <td>{{ $s->nomor_agenda }}</td>
                    <td>
                        <div class="cell-title">{{ $s->perihal }}</div>
                        <div class="cell-sub">{{ $s->pengirim }}{{ $s->nomor_surat ? ' · No. ' . $s->nomor_surat : '' }}</div>
                    </td>
                    <td>{{ $s->tanggal_terima->translatedFormat('d M Y') }}</td>
                    <td>
                        @if ($s->status === 'sudah_didisposisi')
                            <span class="badge-pill badge-green">Sudah Didisposisi</span>
                        @else
                            <span class="badge-pill badge-orange">Menunggu Disposisi</span>
                        @endif
                    </td>
                    <td>
                        @if ($s->status === 'sudah_didisposisi')
                            <a href="{{ route('surat-masuk.show', $s) }}" class="btn btn-sm btn-ghost">Terarsip</a>
                        @elseif ($u->bolehKelola(['admin_tu']))
                            <a href="{{ route('disposisi.create', $s) }}" class="btn btn-sm btn-orange">Buat Disposisi</a>
                        @else
                            <a href="{{ route('surat-masuk.show', $s) }}" class="btn btn-sm btn-outline">Lihat</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5"><div class="empty-state">Belum ada surat masuk.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top:1rem;">{{ $suratMasuks->links() }}</div>
@endsection
