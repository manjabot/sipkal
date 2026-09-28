@extends('layouts.app')

@section('title', 'Tambah Akun Pengguna')

@section('content')
<div class="modal-backdrop-page" style="margin:-1.75rem;padding:2.5rem 1.5rem;">
    <div class="modal-card">
        <div class="modal-card-header">
            <h2>Tambah Akun Pengguna</h2>
            <a href="{{ route('users.index') }}">&times;</a>
        </div>
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="modal-card-body">
                <div class="form-row">
                    <div class="field">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="field">
                        <label>Nama Pengguna (username)</label>
                        <input type="text" name="username" value="{{ old('username') }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label>Kata Sandi</label>
                        <input type="text" name="password" placeholder="Minimal 6 karakter" required>
                    </div>
                    <div class="field">
                        <label>Peran</label>
                        <select name="role" id="roleSelect" required onchange="toggleFields()">
                            <option value="admin" @selected(old('role')==='admin')>Administrator</option>
                            <option value="admin_tu" @selected(old('role')==='admin_tu')>Staf Tata Usaha</option>
                            <option value="kepala_sekolah" @selected(old('role')==='kepala_sekolah')>Kepala Sekolah</option>
                            <option value="waka" @selected(old('role')==='waka')>Waka / Unit Terkait</option>
                            <option value="bendahara" @selected(old('role')==='bendahara')>Bendahara</option>
                            <option value="siswa" @selected(old('role')==='siswa')>Siswa</option>
                            <option value="industri" @selected(old('role')==='industri')>Mitra Industri</option>
                            <option value="tamu" @selected(old('role')==='tamu')>Tamu (Pengirim Surat)</option>
                        </select>
                    </div>
                </div>
                <div class="field" id="jabatanField">
                    <label>Jabatan (opsional, tampil di header)</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan') }}">
                </div>
                <div class="field" id="unitField">
                    <label>Unit (untuk peran Waka/Bendahara, jadi tujuan disposisi)</label>
                    <input type="text" name="unit" placeholder="Contoh: Waka Kesiswaan" value="{{ old('unit') }}">
                </div>
                <div class="field" id="industriField">
                    <label>Mitra Industri Terkait (untuk peran Mitra Industri)</label>
                    <select name="industri_id">
                        <option value="">-- Pilih --</option>
                        @foreach ($industris as $i)
                            <option value="{{ $i->id }}" @selected(old('industri_id') == $i->id)>{{ $i->nama_perusahaan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field" id="instansiField">
                    <label>Instansi/Keperluan Asal (untuk peran Tamu)</label>
                    <input type="text" name="instansi_asal" placeholder="Contoh: Orang Tua Siswa, PT Maju Bersama" value="{{ old('instansi_asal') }}">
                </div>
            </div>
            <div class="modal-card-footer">
                <a href="{{ route('users.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-navy">Simpan Akun</button>
            </div>
        </form>
    </div>
</div>
<script>
function toggleFields() {
    var role = document.getElementById('roleSelect').value;
    document.getElementById('unitField').style.display = (role === 'waka' || role === 'bendahara') ? 'block' : 'none';
    document.getElementById('industriField').style.display = (role === 'industri') ? 'block' : 'none';
    document.getElementById('instansiField').style.display = (role === 'tamu') ? 'block' : 'none';
}
document.addEventListener('DOMContentLoaded', toggleFields);
</script>
@endsection
