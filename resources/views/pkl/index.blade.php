@extends('layouts.app')

@section('title', 'PKL & Sertifikat')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.1rem;">
    <div>
        <h2 style="font-size:1.3rem;">PKL &amp; Sertifikat</h2>
        <p style="color:var(--text-muted);font-size:.88rem;margin-top:.25rem;max-width:640px;">
            Data siswa Praktik Kerja Lapangan, konsentrasi keahlian Rekayasa Perangkat Lunak. Sertifikat dapat dicetak setelah PKL selesai dan disahkan Kepala Sekolah.
        </p>
    </div>
    @if ($u->bolehKelola(['admin_tu']))
        <a href="{{ route('pkl.create') }}" class="btn btn-navy">+ Input Data PKL Siswa</a>
    @endif
</div>

<div class="card">
    <div class="filter-bar">
        <form class="search-input" method="GET">
            <span class="icon">&#128269;</span>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama siswa atau lokasi PKL...">
        </form>
        <form class="filter-select" method="GET">
            <input type="hidden" name="q" value="{{ request('q') }}">
            <select name="status" onchange="this.form.submit()">
                <option value="">Semua status</option>
                <option value="menunggu_persetujuan" @selected(request('status')==='menunggu_persetujuan')>Menunggu Persetujuan</option>
                <option value="berlangsung" @selected(request('status')==='berlangsung')>Berlangsung</option>
                <option value="selesai" @selected(request('status')==='selesai')>Selesai</option>
            </select>
        </form>
    </div>

    <div class="table-wrap">
        <table class="simple">
            <thead>
                <tr><th>Siswa</th><th>Lokasi PKL</th><th>Periode</th><th>Status</th><th>Sertifikat</th></tr>
            </thead>
            <tbody>
            @forelse ($pkls as $pkl)
                <tr>
                    <td>
                        <div class="cell-title">{{ $pkl->siswa->nama }}</div>
                        <div class="cell-sub">NIS {{ $pkl->siswa->nis }} · {{ $pkl->siswa->ttl() }}</div>
                    </td>
                    <td>{{ $pkl->industri->nama_perusahaan }}</td>
                    <td>{{ $pkl->tanggal_mulai->translatedFormat('d M Y') }} – {{ $pkl->tanggal_selesai->translatedFormat('d M Y') }}</td>
                    <td>
                        @if ($pkl->status === 'selesai')<span class="badge-pill badge-green">Selesai</span>
                        @elseif ($pkl->status === 'berlangsung')<span class="badge-pill badge-blue">Berlangsung</span>
                        @else<span class="badge-pill badge-orange">Menunggu Persetujuan</span>
                        @endif
                    </td>
                    <td>
                        @if ($pkl->status === 'selesai')
                            <a href="{{ route('pkl.show', $pkl) }}" class="btn btn-sm btn-outline">Lihat Sertifikat</a>
                        @elseif ($pkl->status === 'berlangsung')
                            <span class="badge-pill badge-gray">Belum tersedia</span>
                        @else
                            <span class="badge-pill badge-orange">Menunggu ACC Kepsek</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5"><div class="empty-state">Belum ada data PKL.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top:1rem;">{{ $pkls->links() }}</div>
@endsection
