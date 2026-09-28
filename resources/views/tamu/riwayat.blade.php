@extends('layouts.app')

@section('title', 'Riwayat Surat Saya')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.1rem;flex-wrap:wrap;gap:.75rem;">
    <div>
        <h2 style="font-size:1.3rem;">Riwayat Surat Saya</h2>
        <p style="color:var(--text-muted);font-size:.88rem;margin-top:.25rem;">Status surat yang pernah kamu kirim ke sekolah.</p>
    </div>
    <a href="{{ route('tamu.kirim') }}" class="btn btn-navy">+ Kirim Surat Baru</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="simple">
            <thead>
                <tr><th>No. Agenda</th><th>Perihal</th><th>Tgl Kirim</th><th>Status</th></tr>
            </thead>
            <tbody>
            @forelse ($suratSaya as $s)
                <tr>
                    <td>{{ $s->nomor_agenda }}</td>
                    <td class="cell-title">{{ $s->perihal }}</td>
                    <td>{{ $s->tanggal_terima->translatedFormat('d M Y') }}</td>
                    <td>
                        @if ($s->status === 'sudah_didisposisi')
                            <span class="badge-pill badge-green">Sudah Diproses Sekolah</span>
                        @else
                            <span class="badge-pill badge-orange">Menunggu Diproses</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4"><div class="empty-state">Kamu belum pernah mengirim surat.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top:1rem;">{{ $suratSaya->links() }}</div>
@endsection
