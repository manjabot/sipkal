@extends('layouts.app')

@section('title', 'Kerjasama Industri')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.1rem;">
    <div>
        <h2 style="font-size:1.3rem;">Kerjasama Industri (DU/DI)</h2>
        <p style="color:var(--text-muted);font-size:.88rem;margin-top:.25rem;max-width:600px;">
            Daftar mitra dunia usaha/dunia industri beserta masa berlaku nota kesepahaman (MoU) kerjasama.
        </p>
    </div>
    @if ($u->bolehKelola(['admin_tu', 'waka']))
        <a href="{{ route('kerjasama.create') }}" class="btn btn-navy">+ Tambah Mitra &amp; MoU</a>
    @endif
</div>

<div class="card">
    <div class="table-wrap">
        <table class="simple">
            <thead>
                <tr><th>No. Dokumen</th><th>Mitra Industri</th><th>Bidang</th><th>Masa Berlaku MoU</th><th>Status</th></tr>
            </thead>
            <tbody>
            @forelse ($kerjasamas as $k)
                <tr>
                    <td>{{ $k->nomor_dokumen }}</td>
                    <td class="cell-title">
                        <a href="{{ route('kerjasama.show', $k) }}" style="text-decoration:none;color:inherit;">{{ $k->industri->nama_perusahaan }}</a>
                    </td>
                    <td>{{ $k->bidang }}</td>
                    <td>{{ $k->mulai_mou->translatedFormat('d M Y') }} – {{ $k->berakhir_mou->translatedFormat('d M Y') }}</td>
                    <td>
                        @if ($k->status() === 'aktif')<span class="badge-pill badge-green">Aktif</span>
                        @elseif ($k->status() === 'akan_berakhir')<span class="badge-pill badge-orange">Akan Berakhir</span>
                        @else<span class="badge-pill badge-red">Berakhir</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5"><div class="empty-state">Belum ada data kerjasama.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top:1rem;">{{ $kerjasamas->links() }}</div>
@endsection
