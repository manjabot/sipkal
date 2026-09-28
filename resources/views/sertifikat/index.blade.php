@extends('layouts.app')

@section('title', 'Sertifikat Umum')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.1rem;flex-wrap:wrap;gap:.75rem;">
    <div>
        <h2 style="font-size:1.3rem;">Sertifikat Umum</h2>
        <p style="color:var(--text-muted);font-size:.88rem;margin-top:.25rem;max-width:600px;">
            Penerbitan sertifikat dari template untuk kegiatan sekolah di luar PKL — mis. lomba, seminar, kepanitiaan, penghargaan guru/siswa.
        </p>
    </div>
    @if ($u->bolehKelola(['admin_tu']))
        <a href="{{ route('sertifikat.create') }}" class="btn btn-navy">+ Terbitkan Sertifikat</a>
    @endif
</div>

<div class="card">
    <div class="filter-bar">
        <form class="search-input" method="GET">
            <span class="icon">&#128269;</span>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama penerima atau kegiatan...">
        </form>
    </div>

    <div class="table-wrap">
        <table class="simple">
            <thead>
                <tr><th>No. Sertifikat</th><th>Penerima</th><th>Kegiatan</th><th>Tanggal Terbit</th><th></th></tr>
            </thead>
            <tbody>
            @forelse ($sertifikats as $s)
                <tr>
                    <td>{{ $s->nomor_sertifikat }}</td>
                    <td>
                        <div class="cell-title">{{ $s->nama_penerima }}</div>
                        @if ($s->identitas_penerima)<div class="cell-sub">{{ $s->identitas_penerima }}</div>@endif
                    </td>
                    <td>{{ $s->jenis_kegiatan }}</td>
                    <td>{{ $s->tanggal_terbit->translatedFormat('d M Y') }}</td>
                    <td><a href="{{ route('sertifikat.show', $s) }}" class="btn btn-sm btn-outline">Lihat</a></td>
                </tr>
            @empty
                <tr><td colspan="5"><div class="empty-state">Belum ada sertifikat diterbitkan.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top:1rem;">{{ $sertifikats->links() }}</div>
@endsection
