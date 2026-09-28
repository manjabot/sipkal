<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $suratKeluar->nomor_surat }} - Cetak Surat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sipkal.css') }}">
</head>
<body>
<div class="print-page">
    <div class="print-toolbar no-print">
        <a href="{{ route('surat-keluar.show', $suratKeluar) }}" class="btn btn-outline btn-sm">&larr; Kembali</a>
        <button type="button" class="btn btn-navy btn-sm" onclick="window.print()">Cetak / Simpan PDF</button>
    </div>

    <div class="print-sheet">
        @if ($suratKeluar->status !== 'disetujui')
            <div class="watermark-draft">DRAFT</div>
        @endif

        <div class="kop-surat">
            <div class="kop-logo">S4</div>
            <div class="kop-text">
                <h2>SMK NEGERI 4 TANJUNGPINANG</h2>
                <div class="kop-alamat">Jl. Pendidikan, Tanjungpinang, Kepulauan Riau &middot; Telp. (0771) 000000 &middot; smkn4tpi.sch.id</div>
            </div>
        </div>

        <div class="surat-meta">
            <table>
                <tr><td>Nomor</td><td>: {{ $suratKeluar->nomor_surat }}</td></tr>
                <tr><td>Perihal</td><td>: {{ $suratKeluar->perihal }}</td></tr>
                <tr><td>Lampiran</td><td>: {{ $suratKeluar->file_path ? '1 (satu) berkas' : '-' }}</td></tr>
            </table>
            <div>Tanjungpinang, {{ $suratKeluar->tanggal_surat->translatedFormat('d F Y') }}</div>
        </div>

        <p>
            Kepada Yth.<br>
            <strong>{{ $suratKeluar->tujuan }}</strong><br>
            di Tempat
        </p>

        <p>{{ $suratKeluar->pembukaSurat() }}</p>

        <p style="white-space:pre-line;">{{ $suratKeluar->isi_surat ?: $suratKeluar->perihal }}</p>

        <p>Demikian surat ini kami sampaikan, atas perhatian dan kerja samanya kami ucapkan terima kasih.</p>

        <div style="display:flex;justify-content:flex-end;margin-top:2.5rem;">
            <div style="text-align:center;min-width:240px;">
                <div>Kepala SMK Negeri 4 Tanjungpinang,</div>
                <div style="height:4.5rem;"></div>
                <div style="border-bottom:1px solid #333;font-weight:600;">
                    {{ \App\Models\User::where('role', 'kepala_sekolah')->value('name') ?? '__________________' }}
                </div>
                <div style="font-size:.78rem;color:var(--text-muted);margin-top:.2rem;">NIP. __________________</div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
