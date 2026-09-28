@extends('layouts.app')

@section('title', 'Terbitkan Sertifikat')

@section('content')
<div class="modal-backdrop-page" style="margin:-1.75rem;padding:2.5rem 1.5rem;">
    <div class="modal-card">
        <div class="modal-card-header">
            <h2>Terbitkan Sertifikat</h2>
            <a href="{{ route('sertifikat.index') }}">&times;</a>
        </div>
        <form action="{{ route('sertifikat.store') }}" method="POST">
            @csrf
            <div class="modal-card-body">
                <div class="field">
                    <label>Jenis Kegiatan</label>
                    <input type="text" name="jenis_kegiatan" placeholder="Contoh: Peserta Lomba Kompetensi Siswa (LKS) Tingkat Provinsi" value="{{ old('jenis_kegiatan') }}" required>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label>Nama Penerima</label>
                        <input type="text" name="nama_penerima" placeholder="Nama lengkap" value="{{ old('nama_penerima') }}" required>
                    </div>
                    <div class="field">
                        <label>Identitas (opsional)</label>
                        <input type="text" name="identitas_penerima" placeholder="NIS / NIP / asal instansi" value="{{ old('identitas_penerima') }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label>Predikat (opsional)</label>
                        <input type="text" name="predikat" placeholder="Contoh: Juara 1, Sangat Baik" value="{{ old('predikat') }}">
                    </div>
                    <div class="field">
                        <label>Tanggal Terbit</label>
                        <input type="date" name="tanggal_terbit" value="{{ old('tanggal_terbit', date('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="field">
                    <label>Keterangan</label>
                    <textarea name="keterangan" rows="3" placeholder="Kalimat yang tampil di badan sertifikat, contoh: Telah berpartisipasi sebagai peserta pada kegiatan ... yang diselenggarakan pada tanggal ...">{{ old('keterangan') }}</textarea>
                </div>
                <div class="hint">Nomor sertifikat akan dibuat otomatis oleh sistem.</div>
            </div>
            <div class="modal-card-footer">
                <a href="{{ route('sertifikat.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-navy">Terbitkan</button>
            </div>
        </form>
    </div>
</div>
@endsection
