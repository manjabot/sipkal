<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Tamu - SIPKAL</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sipkal.css') }}">
    <style>
        body { display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 2rem 1rem; }
        .login-card { width: 100%; max-width: 440px; border-radius: .8rem; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,.15); background: #fff; }
        .login-head { background: var(--maroon); color: #fff; text-align: center; padding: 1.75rem 1.75rem 1.4rem; }
        .login-head .logo-circle {
            width: 46px; height: 46px; border-radius: 999px; background: var(--gold); color: #fff;
            display: flex; align-items: center; justify-content: center; margin: 0 auto .7rem;
            font-family: 'Playfair Display', serif; font-weight: 700; font-size: 1rem;
        }
        .login-head h1 { font-size: 1.2rem; color: #fff; }
        .login-head .desc { color: #E7C98A; font-size: .8rem; margin-top: .4rem; line-height: 1.45; }
        .login-body { padding: 1.5rem 1.75rem; }
        .login-body .field input { background: var(--cream-input); border: 1px solid #D8D2C0; }
        .login-footer { text-align: center; color: var(--text-muted); font-size: .74rem; padding: 0 1.5rem 1.4rem; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-head">
            <div class="logo-circle">S4</div>
            <h1>Daftar Akun Tamu</h1>
            <div class="desc">Untuk mengirim surat ke sekolah dari jarak jauh, tanpa perlu datang langsung. Akun akan diverifikasi oleh Tata Usaha sebelum bisa dipakai.</div>
        </div>

        <div class="login-body">
            @if ($errors->any())
                <div class="alert alert-error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register.attempt') }}">
                @csrf
                <div class="field">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus>
                </div>
                <div class="field">
                    <label>Instansi / Keperluan Asal</label>
                    <input type="text" name="instansi_asal" placeholder="Contoh: Orang Tua Siswa, PT Maju Bersama, dst." value="{{ old('instansi_asal') }}" required>
                </div>
                <div class="field">
                    <label>Nama Pengguna (username)</label>
                    <input type="text" name="username" value="{{ old('username') }}" required>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label>Kata Sandi</label>
                        <input type="password" name="password" required>
                    </div>
                    <div class="field">
                        <label>Ulangi Kata Sandi</label>
                        <input type="password" name="password_confirmation" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-navy btn-block" style="background:var(--maroon);">Daftar</button>
            </form>

            <div style="text-align:center;font-size:.82rem;margin-top:1rem;">
                <a href="{{ route('login') }}" style="color:var(--maroon);font-weight:600;text-decoration:none;">&larr; Kembali ke halaman masuk</a>
            </div>
        </div>

        <div class="login-footer">© {{ date('Y') }} SMK Negeri 4 Tanjungpinang — Kepulauan Riau</div>
    </div>
</body>
</html>
