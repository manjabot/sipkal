@extends('layouts.app')

@section('title', 'Laporan & Rekap')

@php
    $warnaProgress = function ($label) {
        return match(true) {
            in_array($label, ['Menunggu Disposisi','Menunggu ACC','Menunggu Persetujuan','Akan Berakhir']) => '#B5651D',
            in_array($label, ['Sudah Didisposisi','Disetujui','Selesai','Aktif']) => '#4C7A4C',
            in_array($label, ['Dikembalikan','Berakhir']) => '#8A2E3D',
            in_array($label, ['Berlangsung']) => '#2F3F73',
            default => '#B7AE93',
        };
    };
    $maxOf = fn($arr) => max(1, max($arr));
@endphp

@section('content')
<h2 style="font-size:1.3rem;">Laporan &amp; Rekap</h2>
<p style="color:var(--text-muted);font-size:.88rem;margin:.25rem 0 1.1rem;">
    Rekap otomatis berdasarkan data pada seluruh modul, untuk mendukung pengambilan keputusan Kepala Sekolah.
</p>

<div class="two-col">
    <div class="card">
        <h3 style="font-size:1rem;margin-bottom:.9rem;">Status surat masuk</h3>
        @php($max = $maxOf($suratMasuk))
        @foreach ($suratMasuk as $label => $jumlah)
            <div class="progress-row">
                <div class="progress-label"><span>{{ $label }}</span><span>{{ $jumlah }}</span></div>
                <div class="progress-track"><div class="progress-fill" style="width:{{ $jumlah/$max*100 }}%;background:{{ $warnaProgress($label) }};"></div></div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <h3 style="font-size:1rem;margin-bottom:.9rem;">Status surat keluar</h3>
        @php($max = $maxOf($suratKeluar))
        @foreach ($suratKeluar as $label => $jumlah)
            <div class="progress-row">
                <div class="progress-label"><span>{{ $label }}</span><span>{{ $jumlah }}</span></div>
                <div class="progress-track"><div class="progress-fill" style="width:{{ $jumlah/$max*100 }}%;background:{{ $warnaProgress($label) }};"></div></div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <h3 style="font-size:1rem;margin-bottom:.9rem;">Status PKL siswa — Rekayasa Perangkat Lunak</h3>
        @php($max = $maxOf($pkl))
        @foreach ($pkl as $label => $jumlah)
            <div class="progress-row">
                <div class="progress-label"><span>{{ $label }}</span><span>{{ $jumlah }}</span></div>
                <div class="progress-track"><div class="progress-fill" style="width:{{ $jumlah/$max*100 }}%;background:{{ $warnaProgress($label) }};"></div></div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <h3 style="font-size:1rem;margin-bottom:.9rem;">Status kerjasama industri</h3>
        @php($max = $maxOf($kerjasama))
        @foreach ($kerjasama as $label => $jumlah)
            <div class="progress-row">
                <div class="progress-label"><span>{{ $label }}</span><span>{{ $jumlah }}</span></div>
                <div class="progress-track"><div class="progress-fill" style="width:{{ $jumlah/$max*100 }}%;background:{{ $warnaProgress($label) }};"></div></div>
            </div>
        @endforeach
    </div>
</div>
@endsection
