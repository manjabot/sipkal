@extends('layouts.app')

@section('title', 'Tambah Mitra & MoU')

@section('content')
<div class="modal-backdrop-page" style="margin:-1.75rem;padding:2.5rem 1.5rem;">
    <div class="modal-card">
        <div class="modal-card-header">
            <h2>Tambah Mitra &amp; MoU</h2>
            <a href="{{ route('kerjasama.index') }}">&times;</a>
        </div>
        <form action="{{ route('kerjasama.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-card-body">
                <div class="field">
                    <label>Nama Mitra Industri / Instansi</label>
                    <input type="text" name="nama_perusahaan" placeholder="Contoh: PT Pelindo Cabang Tanjungpinang" value="{{ old('nama_perusahaan') }}" required>
                </div>
                <div class="field">
                    <label>Bidang</label>
                    <input type="text" name="bidang" placeholder="Contoh: Logistik &amp; Kepelabuhanan" value="{{ old('bidang') }}" required>
                </div>
                <div class="field">
                    <label>Ruang Lingkup Kerjasama</label>
                    <textarea name="ruang_lingkup" rows="3" placeholder="Contoh: Penempatan siswa PKL, kunjungan industri, dan rekrutmen lulusan.">{{ old('ruang_lingkup') }}</textarea>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label>Mulai MoU</label>
                        <input type="date" name="mulai_mou" value="{{ old('mulai_mou') }}" required>
                    </div>
                    <div class="field">
                        <label>Berakhir MoU</label>
                        <input type="date" name="berakhir_mou" value="{{ old('berakhir_mou') }}" required>
                    </div>
                </div>
                <div class="field">
                    <label>Dokumen MoU (opsional)</label>
                    <input type="file" name="dokumen">
                </div>
                <div class="hint">Nomor dokumen MoU/PKS dan status keaktifan akan dibuat &amp; dihitung otomatis oleh sistem.</div>
            </div>
            <div class="modal-card-footer">
                <a href="{{ route('kerjasama.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-navy">Simpan Mitra</button>
            </div>
        </form>
    </div>
</div>
@endsection
