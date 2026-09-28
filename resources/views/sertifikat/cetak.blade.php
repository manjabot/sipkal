<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $sertifikat->nomor_sertifikat }} - Cetak Sertifikat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sipkal.css') }}">
</head>
<body>
<div class="print-page">
    <div class="print-toolbar no-print">
        <a href="{{ route('sertifikat.show', $sertifikat) }}" class="btn btn-outline btn-sm">&larr; Kembali</a>
        <button type="button" class="btn btn-navy btn-sm" onclick="window.print()">Cetak / Simpan PDF</button>
    </div>

    <div class="print-sheet" style="border:3px double var(--navy); text-align:center;">
        <div class="font-serif" style="font-size:2rem;letter-spacing:.05em;">Sertifikat</div>
        <div style="font-size:.8rem;color:var(--text-muted);margin-top:.4rem;">Nomor: {{ $sertifikat->nomor_sertifikat }}</div>

        <div style="margin-top:1.5rem;font-size:.85rem;">Diberikan Kepada</div>
        <div class="font-serif" style="font-size:1.6rem;margin-top:.25rem;">{{ strtoupper($sertifikat->nama_penerima) }}</div>
        @if ($sertifikat->identitas_penerima)
            <div style="font-size:.85rem;color:var(--text-muted);margin-top:.2rem;">{{ $sertifikat->identitas_penerima }}</div>
        @endif

        <p style="max-width:520px;margin:1.5rem auto 0;font-size:.9rem;line-height:1.7;">
            {{ $sertifikat->keterangan ?: 'Telah berpartisipasi dalam kegiatan ' . $sertifikat->jenis_kegiatan . '.' }}
            @if ($sertifikat->predikat)
                Dengan predikat <strong>{{ $sertifikat->predikat }}</strong>.
            @endif
        </p>

        <div style="font-size:.82rem;color:var(--text-muted);margin-top:1rem;">
            {{ $sertifikat->jenis_kegiatan }} &middot; Tanjungpinang, {{ $sertifikat->tanggal_terbit->translatedFormat('d F Y') }}
        </div>

        <div style="display:flex;justify-content:center;margin-top:2.5rem;font-size:.82rem;">
            <div>
                <div style="color:var(--text-muted);">KEPALA<br>SMK NEGERI 4 TANJUNGPINANG</div>
                <div style="height:3.2rem;"></div>
                <div style="border-top:1px solid #333;padding-top:.3rem;display:inline-block;min-width:220px;">
                    {{ \App\Models\User::where('role','kepala_sekolah')->value('name') ?? '__________________' }}
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
