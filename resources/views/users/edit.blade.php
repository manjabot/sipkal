@extends('layouts.app')

@section('title', 'Ubah Akun Pengguna')

@section('content')
<div class="modal-backdrop-page" style="margin:-1.75rem;padding:2.5rem 1.5rem;">
    <div class="modal-card">
        <div class="modal-card-header">
            <h2>Ubah Akun Pengguna</h2>
            <a href="{{ route('users.index') }}">&times;</a>
        </div>
        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf @method('PUT')
            <div class="modal-card-body">
                <div class="form-row">
                    <div class="field">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="field">
                        <label>Nama Pengguna (username)</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label>Kata Sandi Baru (opsional)</label>
                        <input type="text" name="password" placeholder="Kosongkan jika tidak diubah">
                    </div>
                    <div class="field">
                        <label>Peran</label>
                        <select name="role" id="roleSelect" required onchange="toggleFields()">
                            <option value="admin" @selected($user->role==='admin')>Administrator</option>
                            <option value="admin_tu" @selected($user->role==='admin_tu')>Staf Tata Usaha</option>
                            <option value="kepala_sekolah" @selected($user->role==='kepala_sekolah')>Kepala Sekolah</option>
                            <option value="waka" @selected($user->role==='waka')>Waka / Unit Terkait</option>
                            <option value="bendahara" @selected($user->role==='bendahara')>Bendahara</option>
                            <option value="siswa" @selected($user->role==='siswa')>Siswa</option>
                            <option value="industri" @selected($user->role==='industri')>Mitra Industri</option>
                            <option value="tamu" @selected($user->role==='tamu')>Tamu (Pengirim Surat)</option>
                        </select>
                    </div>
                </div>
                <div class="field" id="jabatanField">
                    <label>Jabatan (opsional, tampil di header)</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}">
                </div>
                <div class="field" id="unitField">
                    <label>Unit (untuk peran Waka/Bendahara)</label>
                    <input type="text" name="unit" value="{{ old('unit', $user->unit) }}">
                </div>
                <div class="field" id="industriField">
                    <label>Mitra Industri Terkait</label>
                    <select name="industri_id">
                        <option value="">-- Pilih --</option>
                        @foreach ($industris as $i)
                            <option value="{{ $i->id }}" @selected(old('industri_id', $user->industri_id) == $i->id)>{{ $i->nama_perusahaan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field" id="instansiField">
                    <label>Instansi/Keperluan Asal (untuk peran Tamu)</label>
                    <input type="text" name="instansi_asal" value="{{ old('instansi_asal', $user->instansi_asal) }}">
                </div>
            </div>
            <div class="modal-card-footer">
                <a href="{{ route('users.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-navy">Simpan Perubahan</button>
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
