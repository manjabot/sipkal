@extends('layouts.app')

@section('title', 'Buat Draft Surat Keluar')

@section('content')
<div class="modal-backdrop-page" style="margin:-1.75rem;padding:2.5rem 1.5rem;">
    <div class="modal-card modal-card-lg">
        <div class="modal-card-header">
            <h2>Buat Draft Surat Keluar</h2>
            <a href="{{ route('surat-keluar.index') }}">&times;</a>
        </div>
        <form action="{{ route('surat-keluar.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-card-body">
                <div class="form-row">
                    <div class="field">
                        <label>Jenis Surat</label>
                        <select name="jenis_surat" required>
                            <option value="undangan" @selected(old('jenis_surat')==='undangan')>Surat Undangan</option>
                            <option value="permohonan" @selected(old('jenis_surat')==='permohonan')>Surat Permohonan</option>
                            <option value="keterangan" @selected(old('jenis_surat')==='keterangan')>Surat Keterangan</option>
                            <option value="pemberitahuan" @selected(old('jenis_surat')==='pemberitahuan')>Surat Pemberitahuan</option>
                            <option value="lainnya" @selected(old('jenis_surat')==='lainnya')>Lainnya</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Ditujukan kepada</label>
                        <input type="text" name="tujuan" placeholder="Contoh: PT Telkom Indonesia Witel Kepri" value="{{ old('tujuan') }}" required>
                    </div>
                </div>
                <div class="field">
                    <label>Perihal</label>
                    <input type="text" name="perihal" placeholder="Contoh: Permohonan Penempatan PKL" value="{{ old('perihal') }}" required>
                </div>
                <div class="field">
                    <label>Isi Surat</label>
                    <textarea name="isi_surat" rows="6" placeholder="Tulis isi lengkap surat di sini. Ini akan tampil apa adanya pada dokumen surat resmi yang dicetak.">{{ old('isi_surat') }}</textarea>
                    <div class="hint">Tidak perlu menulis kop, nomor surat, salam pembuka/penutup, atau tanda tangan — bagian itu dibuat otomatis mengikuti jenis surat saat dicetak.</div>
                </div>
                <div class="field">
                    <label>Lampiran (opsional)</label>
                    <input type="file" name="file">
                </div>
                <div class="hint">Nomor surat resmi akan dibuat otomatis oleh sistem.</div>
            </div>
            <div class="modal-card-footer">
                <a href="{{ route('surat-keluar.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-navy">Simpan Draft</button>
            </div>
        </form>
    </div>
</div>
@endsection
