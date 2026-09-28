@extends('layouts.app')

@section('title', 'Persetujuan Akun Tamu')

@section('content')
<h2 style="font-size:1.3rem;">Persetujuan Akun Tamu</h2>
<p style="color:var(--text-muted);font-size:.88rem;margin:.25rem 0 1.25rem;max-width:640px;">
    Akun Tamu adalah pihak eksternal (orang tua, mitra, masyarakat umum) yang mendaftar sendiri supaya bisa mengirim
    surat ke sekolah dari jarak jauh. Setujui hanya jika identitas &amp; keperluannya jelas.
</p>

<div class="card" style="margin-bottom:1.25rem;">
    <h3 style="font-size:1rem;margin-bottom:.85rem;">Menunggu Persetujuan ({{ $menunggu->count() }})</h3>
    <div class="table-wrap">
        <table class="simple">
            <thead>
                <tr><th>Nama</th><th>Instansi/Keperluan</th><th>Username</th><th>Didaftarkan</th><th></th></tr>
            </thead>
            <tbody>
            @forelse ($menunggu as $t)
                <tr>
                    <td class="cell-title">{{ $t->name }}</td>
                    <td>{{ $t->instansi_asal }}</td>
                    <td>{{ $t->username }}</td>
                    <td>{{ $t->created_at->translatedFormat('d M Y, H:i') }}</td>
                    <td style="text-align:right;white-space:nowrap;">
                        <form action="{{ route('akun-tamu.setujui', $t) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-gold">Setujui</button>
                        </form>
                        <form action="{{ route('akun-tamu.tolak', $t) }}" method="POST" style="display:inline;" onsubmit="return confirm('Tolak pendaftaran {{ $t->name }}?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-red">Tolak</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5"><div class="empty-state">Tidak ada pendaftaran yang menunggu persetujuan.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <h3 style="font-size:1rem;margin-bottom:.85rem;">Riwayat Keputusan Terbaru</h3>
    <div class="table-wrap">
        <table class="simple">
            <thead>
                <tr><th>Nama</th><th>Instansi/Keperluan</th><th>Username</th><th>Status</th></tr>
            </thead>
            <tbody>
            @forelse ($riwayat as $t)
                <tr>
                    <td class="cell-title">{{ $t->name }}</td>
                    <td>{{ $t->instansi_asal }}</td>
                    <td>{{ $t->username }}</td>
                    <td>
                        @if ($t->status_akun === 'aktif')<span class="badge-pill badge-green">Disetujui</span>
                        @else<span class="badge-pill badge-red">Ditolak</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4"><div class="empty-state">Belum ada riwayat.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
