@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.1rem;flex-wrap:wrap;gap:.75rem;">
    <div>
        <h2 style="font-size:1.3rem;">Manajemen Pengguna</h2>
        <p style="color:var(--text-muted);font-size:.88rem;margin-top:.25rem;">Kelola akun &amp; hak akses seluruh pengguna SIPKAL.</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-navy">+ Tambah Akun</a>
</div>

@if ($resetRequests->count())
<div class="card" style="margin-bottom:1.1rem;border-color:var(--orange);">
    <h3 style="font-size:1rem;margin-bottom:.6rem;">Permintaan Reset Kata Sandi ({{ $resetRequests->count() }})</h3>
    <div class="table-wrap">
        <table class="simple">
            <thead><tr><th>Username</th><th>Catatan</th><th>Waktu</th><th></th></tr></thead>
            <tbody>
            @foreach ($resetRequests as $r)
                <tr>
                    <td class="cell-title">{{ $r->username }}</td>
                    <td>{{ $r->catatan ?: '-' }}</td>
                    <td>{{ $r->created_at->translatedFormat('d M Y, H:i') }}</td>
                    <td style="text-align:right;">
                        <form action="{{ route('users.reset-request.selesai', $r) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline">Tandai Selesai</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="hint">Verifikasi identitas orangnya dulu, baru buka menu Edit pada akun terkait di bawah untuk atur kata sandi baru.</div>
</div>
@endif

<div class="card">
    <div class="filter-bar">
        <form class="search-input" method="GET">
            <span class="icon">&#128269;</span>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau nama pengguna...">
        </form>
        <form class="filter-select" method="GET">
            <input type="hidden" name="q" value="{{ request('q') }}">
            <select name="role" onchange="this.form.submit()">
                <option value="">Semua peran</option>
                <option value="admin" @selected(request('role')==='admin')>Administrator</option>
                <option value="admin_tu" @selected(request('role')==='admin_tu')>Staf Tata Usaha</option>
                <option value="kepala_sekolah" @selected(request('role')==='kepala_sekolah')>Kepala Sekolah</option>
                <option value="waka" @selected(request('role')==='waka')>Waka</option>
                <option value="bendahara" @selected(request('role')==='bendahara')>Bendahara</option>
                <option value="siswa" @selected(request('role')==='siswa')>Siswa</option>
                <option value="industri" @selected(request('role')==='industri')>Mitra Industri</option>
            </select>
        </form>
    </div>

    <div class="table-wrap">
        <table class="simple">
            <thead>
                <tr><th>Nama</th><th>Nama Pengguna</th><th>Peran</th><th>Jabatan/Unit</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
            @forelse ($users as $usr)
                <tr>
                    <td class="cell-title">{{ $usr->name }}</td>
                    <td>{{ $usr->username }}</td>
                    <td><span class="badge-pill badge-blue">{{ $usr->labelJabatan() }}</span></td>
                    <td>{{ $usr->unit ?: ($usr->jabatan ?: ($usr->instansi_asal ?: '-')) }}</td>
                    <td>
                        @if ($usr->status_akun === 'aktif')<span class="badge-pill badge-green">Aktif</span>
                        @elseif ($usr->status_akun === 'menunggu_persetujuan')<span class="badge-pill badge-orange">Menunggu</span>
                        @else<span class="badge-pill badge-red">Ditolak</span>
                        @endif
                    </td>
                    <td style="text-align:right;white-space:nowrap;">
                        <a href="{{ route('users.edit', $usr) }}" class="btn btn-sm btn-outline">Edit</a>
                        @if ($usr->id !== $u->id)
                        <form action="{{ route('users.destroy', $usr) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus akun {{ $usr->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-red">Hapus</button>
                        </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6"><div class="empty-state">Belum ada pengguna.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top:1rem;">{{ $users->links() }}</div>
@endsection
