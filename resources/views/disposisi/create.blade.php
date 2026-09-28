@extends('layouts.app')

@section('title', 'Buat Lembar Disposisi')

@section('content')
<div class="modal-backdrop-page" style="margin:-1.75rem;padding:2.5rem 1.5rem;">
    <div class="modal-card">
        <div class="modal-card-header">
            <h2>Buat Lembar Disposisi</h2>
            <a href="{{ route('surat-masuk.show', $suratMasuk) }}">&times;</a>
        </div>
        <form action="{{ route('disposisi.store', $suratMasuk) }}" method="POST">
            @csrf
            <div class="modal-card-body">
                <div class="field">
                    <label>Surat</label>
                    <div class="readonly-box">{{ $suratMasuk->nomor_agenda }} — {{ $suratMasuk->perihal }}</div>
                </div>
                <div class="field">
                    <label>Diteruskan kepada</label>
                    <select name="unit_tujuan" required>
                        <option value="">-- Pilih unit --</option>
                        <option value="Waka Kurikulum">Waka Kurikulum</option>
                        <option value="Waka Kesiswaan">Waka Kesiswaan</option>
                        <option value="Waka Humas">Waka Humas</option>
                        <option value="Waka Sarana Prasarana">Waka Sarana Prasarana</option>
                        <option value="Bendahara">Bendahara</option>
                        <option value="Tata Usaha (PKL)">Tata Usaha (PKL)</option>
                    </select>
                </div>
                <div class="field">
                    <label>Tanggal Disposisi</label>
                    <input type="date" name="tanggal_disposisi" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="field">
                    <label>Instruksi Kepala Sekolah</label>
                    <textarea name="instruksi" placeholder="Contoh: Mohon ditindaklanjuti dan dilaporkan hasilnya"></textarea>
                </div>
                <div class="hint">Lembar disposisi ini disimulasikan sudah ditandatangani Kepala Sekolah.</div>
            </div>
            <div class="modal-card-footer">
                <a href="{{ route('surat-masuk.show', $suratMasuk) }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-gold">Simpan Disposisi</button>
            </div>
        </form>
    </div>
</div>
@endsection
