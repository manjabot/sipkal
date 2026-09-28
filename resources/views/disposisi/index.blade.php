@extends('layouts.app')

@section('title', 'Disposisi')

@section('content')
<h2 style="font-size:1.3rem;">Lembar Disposisi</h2>
<p style="color:var(--text-muted);font-size:.88rem;margin:.25rem 0 1.1rem;">
    Riwayat lembar disposisi yang telah ditandatangani Kepala Sekolah dan diteruskan kepada unit terkait.
</p>

<div class="card">
    <div class="table-wrap">
        <table class="simple">
            <thead>
                <tr><th>No. Agenda</th><th>Perihal</th><th>Diteruskan Kepada</th><th>Instruksi</th><th>Tgl</th></tr>
            </thead>
            <tbody>
            @forelse ($disposisis as $d)
                <tr>
                    <td>{{ $d->suratMasuk->nomor_agenda ?? '-' }}</td>
                    <td class="cell-title">{{ $d->suratMasuk->perihal ?? '-' }}</td>
                    <td>{{ $d->unit_tujuan }}</td>
                    <td>{{ $d->instruksi ?: '-' }}</td>
                    <td>{{ $d->tanggal_disposisi->translatedFormat('d M Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="5"><div class="empty-state">Belum ada disposisi.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top:1rem;">{{ $disposisis->links() }}</div>
@endsection
