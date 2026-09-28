@extends('layouts.app')

@section('title', 'Detail PKL')

@section('content')
<div class="two-col">
    <div class="card">
        <h3 style="font-size:1.05rem;margin-bottom:1rem;">Detail PKL</h3>
        <table class="simple">
            <tbody>
                <tr><td style="width:160px;color:var(--text-muted);">Nama Siswa</td><td class="cell-title">{{ $pkl->siswa->nama }}</td></tr>
                <tr><td style="color:var(--text-muted);">NIS</td><td>{{ $pkl->siswa->nis }}</td></tr>
                <tr><td style="color:var(--text-muted);">Tempat / Tgl Lahir</td><td>{{ $pkl->siswa->ttl() }}</td></tr>
                <tr><td style="color:var(--text-muted);">Konsentrasi Keahlian</td><td>{{ $pkl->siswa->jurusan }}</td></tr>
                <tr><td style="color:var(--text-muted);">Lokasi PKL</td><td>{{ $pkl->industri->nama_perusahaan }}</td></tr>
                <tr><td style="color:var(--text-muted);">Periode</td><td>{{ $pkl->tanggal_mulai->translatedFormat('d M Y') }} – {{ $pkl->tanggal_selesai->translatedFormat('d M Y') }}</td></tr>
                <tr><td style="color:var(--text-muted);">Status</td><td>
                    @if ($pkl->status === 'selesai')<span class="badge-pill badge-green">Selesai</span>
                    @elseif ($pkl->status === 'berlangsung')<span class="badge-pill badge-blue">Berlangsung</span>
                    @else<span class="badge-pill badge-orange">Menunggu Persetujuan</span>
                    @endif
                </td></tr>
                @if ($pkl->predikat)
                    <tr><td style="color:var(--text-muted);">Predikat</td><td>{{ $pkl->predikat }}</td></tr>
                @endif
            </tbody>
        </table>
        <a href="{{ route('pkl.index') }}" class="btn btn-outline btn-sm" style="margin-top:1rem;">&larr; Kembali</a>
    </div>

    <div class="card">
        <h3 style="font-size:1.05rem;margin-bottom:.85rem;">Aksi</h3>

        @if ($pkl->status === 'menunggu_persetujuan')
            @if ($u->bolehKelola(['kepala_sekolah']))
                <p style="font-size:.85rem;color:var(--text-muted);margin-bottom:.75rem;">Setujui pengajuan PKL ini agar siswa bisa mulai berangkat ke lokasi.</p>
                <form action="{{ route('pkl.setujui', $pkl) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-gold">Setujui PKL</button>
                </form>
            @else
                <div class="empty-state">Menunggu persetujuan Kepala Sekolah.</div>
            @endif
        @elseif ($pkl->status === 'berlangsung')
            @if ($u->bolehKelola(['kepala_sekolah']))
                <p style="font-size:.85rem;color:var(--text-muted);margin-bottom:.75rem;">Tuntaskan PKL &amp; sahkan sertifikat kelulusan siswa.</p>
                <form action="{{ route('pkl.selesaikan', $pkl) }}" method="POST">
                    @csrf
                    <div class="field">
                        <label>Predikat</label>
                        <select name="predikat" required>
                            <option value="Sangat Baik">Sangat Baik</option>
                            <option value="Baik">Baik</option>
                            <option value="Cukup">Cukup</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-gold">Selesaikan &amp; Sahkan Sertifikat</button>
                </form>
            @else
                <div class="empty-state">PKL sedang berlangsung. Sertifikat belum tersedia.</div>
            @endif
        @else
            <p style="font-size:.85rem;color:var(--text-muted);margin-bottom:.75rem;">Sertifikat sudah disahkan dan siap dicetak.</p>
            <button type="button" class="btn btn-navy" onclick="document.getElementById('sertifikat-preview').scrollIntoView({behavior:'smooth'})">Lihat Sertifikat</button>
        @endif
    </div>
</div>

@if ($pkl->status === 'selesai')
<div class="card" id="sertifikat-preview" style="margin-top:1rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
        <h3 style="font-size:1.05rem;">Pratinjau Sertifikat PKL</h3>
        <button type="button" class="btn btn-navy btn-sm" onclick="window.print()">Cetak Sertifikat</button>
    </div>

    <div style="border:3px double var(--navy);border-radius:.5rem;padding:2.5rem 2rem;text-align:center;background:#fff;">
        <div class="font-serif" style="font-size:2rem;letter-spacing:.05em;">Sertifikat</div>
        <div style="font-size:.8rem;color:var(--text-muted);margin-top:.4rem;">Nomor: {{ $pkl->nomor_sertifikat }}</div>

        <div style="margin-top:1.5rem;font-size:.85rem;">Diberikan Kepada</div>
        <div class="font-serif" style="font-size:1.6rem;margin-top:.25rem;">{{ strtoupper($pkl->siswa->nama) }}</div>

        <table style="margin:1.25rem auto 0;font-size:.85rem;text-align:left;">
            <tr><td style="color:var(--text-muted);padding:.15rem .75rem .15rem 0;">Nomor Induk Siswa</td><td>: {{ $pkl->siswa->nis }}</td></tr>
            <tr><td style="color:var(--text-muted);padding:.15rem .75rem .15rem 0;">Tempat / Tgl. Lahir</td><td>: {{ $pkl->siswa->ttl() }}</td></tr>
            <tr><td style="color:var(--text-muted);padding:.15rem .75rem .15rem 0;">Konsentrasi Keahlian</td><td>: {{ $pkl->siswa->jurusan }}</td></tr>
        </table>

        <p style="max-width:520px;margin:1.5rem auto 0;font-size:.85rem;line-height:1.6;">
            Telah selesai mengikuti Praktik Kerja Lapangan (PKL) pada
            <strong>{{ strtoupper($pkl->industri->nama_perusahaan) }}</strong>
            dengan predikat <strong>{{ $pkl->predikat }}</strong>.
        </p>

        <div style="display:flex;justify-content:space-around;margin-top:2.5rem;font-size:.82rem;">
            <div>
                <div style="color:var(--text-muted);">KEPALA<br>SMK NEGERI 4 TANJUNGPINANG</div>
                <div style="height:3.2rem;"></div>
                <div style="border-top:1px solid #333;padding-top:.3rem;display:inline-block;min-width:200px;">
                    {{ \App\Models\User::where('role','kepala_sekolah')->value('name') ?? '__________________' }}
                </div>
            </div>
            <div>
                <div style="color:var(--text-muted);">PIMPINAN<br>{{ strtoupper($pkl->industri->nama_perusahaan) }}</div>
                <div style="height:3.2rem;"></div>
                <div style="border-top:1px solid #333;padding-top:.3rem;display:inline-block;min-width:200px;">
                    {{ $pkl->industri->contact_person ?: '__________________' }}
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
