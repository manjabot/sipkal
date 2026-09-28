@extends('layouts.app')

@section('title', 'Catat Surat Masuk')

@section('content')
<div class="modal-backdrop-page" style="margin:-1.75rem;padding:2.5rem 1.5rem;">
    <div class="modal-card">
        <div class="modal-card-header">
            <h2>Catat Surat Masuk</h2>
            <a href="{{ route('surat-masuk.index') }}">&times;</a>
        </div>
        <form action="{{ route('surat-masuk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-card-body">
                <div class="field">
                    <label>Pengirim</label>
                    <input type="text" name="pengirim" placeholder="Contoh: Dinas Pendidikan Provinsi Kepulauan Riau" value="{{ old('pengirim') }}" required>
                </div>
                <div class="field">
                    <label>Perihal</label>
                    <input type="text" name="perihal" placeholder="Contoh: Undangan Rapat Koordinasi" value="{{ old('perihal') }}" required>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label>Nomor Surat (dari pengirim)</label>
                        <input type="text" name="nomor_surat" placeholder="Contoh: 421/DISDIK/2026" value="{{ old('nomor_surat') }}">
                    </div>
                    <div class="field">
                        <label>Tanggal Diterima</label>
                        <input type="date" name="tanggal_terima" value="{{ old('tanggal_terima', date('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="field">
                    <label>Lampiran (opsional)</label>
                    <input type="file" name="file">
                </div>
                <div class="hint">Nomor agenda internal akan dibuat otomatis oleh sistem.</div>
            </div>
            <div class="modal-card-footer">
                <a href="{{ route('surat-masuk.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-navy">Simpan ke Agenda</button>
            </div>
        </form>
    </div>
</div>
@endsection
