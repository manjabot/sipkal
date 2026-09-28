@extends('layouts.app')

@section('title', 'Input Data PKL Siswa')

@section('content')
<div class="modal-backdrop-page" style="margin:-1.75rem;padding:2.5rem 1.5rem;">
    <div class="modal-card">
        <div class="modal-card-header">
            <h2>Input Data PKL Siswa</h2>
            <a href="{{ route('pkl.index') }}">&times;</a>
        </div>
        <form action="{{ route('pkl.store') }}" method="POST">
            @csrf
            <div class="modal-card-body">
                <div class="form-row">
                    <div class="field">
                        <label>Nama Siswa</label>
                        <input type="text" name="nama_siswa" placeholder="Nama lengkap" value="{{ old('nama_siswa') }}" required>
                    </div>
                    <div class="field">
                        <label>NIS</label>
                        <input type="text" name="nis" placeholder="Nomor Induk Siswa" value="{{ old('nis') }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label>Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" placeholder="Contoh: Tanjungpinang" value="{{ old('tempat_lahir') }}">
                    </div>
                    <div class="field">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                    </div>
                </div>
                <div class="field">
                    <label>Konsentrasi Keahlian</label>
                    <div class="readonly-box">Rekayasa Perangkat Lunak</div>
                </div>
                <div class="field">
                    <label>Lokasi PKL (Mitra Industri)</label>
                    <select name="industri_id" required>
                        <option value="">-- Pilih mitra industri --</option>
                        @foreach ($industris as $i)
                            <option value="{{ $i->id }}" @selected(old('industri_id') == $i->id)>{{ $i->nama_perusahaan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label>Mulai PKL</label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required>
                    </div>
                    <div class="field">
                        <label>Selesai PKL</label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required>
                    </div>
                </div>
            </div>
            <div class="modal-card-footer">
                <a href="{{ route('pkl.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-navy">Simpan Data PKL</button>
            </div>
        </form>
    </div>
</div>
@endsection
