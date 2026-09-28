<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SIPKAL</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sipkal.css') }}">
    <style>
        body { display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 2rem 1rem; }
        .login-card { width: 100%; max-width: 400px; border-radius: .8rem; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,.15); background: #fff; }
        .login-head { background: var(--maroon); color: #fff; text-align: center; padding: 2rem 1.75rem 1.6rem; }
        .login-head .logo-circle {
            width: 52px; height: 52px; border-radius: 999px; background: var(--gold); color: #fff;
            display: flex; align-items: center; justify-content: center; margin: 0 auto .9rem;
            font-family: 'Playfair Display', serif; font-weight: 700; font-size: 1.1rem;
        }
        .login-head h1 { font-size: 1.4rem; color: #fff; letter-spacing: .02em; }
        .login-head .desc { color: #E7C98A; font-size: .82rem; margin-top: .5rem; line-height: 1.45; }
        .login-head .school { color: #C7A968; font-size: .78rem; margin-top: .3rem; }
        .login-body { padding: 1.75rem 1.75rem 1.5rem; }
        .login-body .field input, .login-body .field select { background: var(--cream-input); border: 1px solid #D8D2C0; }
        .login-remember { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.1rem; font-size: .82rem; }
        .login-remember label { display: flex; align-items: center; gap: .4rem; color: #514C3D; }
        .login-remember a { color: var(--maroon); text-decoration: none; font-weight: 600; }
        .login-footer { text-align: center; color: var(--text-muted); font-size: .74rem; padding: 1rem 1.5rem 1.4rem; }
        .demo-box { background: var(--cream); border-radius: .5rem; padding: .8rem 1rem; font-size: .76rem; color: #514C3D; margin-top: 1rem; line-height: 1.6; }
        .demo-box code { background: #fff; border: 1px solid var(--card-border); padding: .05rem .35rem; border-radius: .25rem; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-head">
            <div class="logo-circle">S4</div>
            <h1>SIPKAL</h1>
            <div class="desc">Sistem Informasi Persuratan, PKL &amp; Kerjasama Industri</div>
            <div class="school">SMK Negeri 4 Tanjungpinang</div>
        </div>

        <div class="login-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}">
                @csrf
                <div class="field">
                    <label>Nama Pengguna</label>
                    <input type="text" name="username" placeholder="mis. dedi.kurniawan" value="{{ old('username') }}" required autofocus>
                </div>
                <div class="field">
                    <label>Kata Sandi</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>

                <div class="login-remember">
                    <a href="{{ route('password.request') }}">Lupa kata sandi?</a>
                </div>

                <button type="submit" class="btn btn-navy btn-block" style="background:var(--maroon);">Masuk</button>
            </form>

            <div style="text-align:center;font-size:.82rem;margin-top:1rem;color:var(--text-muted);">
                Perlu mengirim surat dari luar sekolah?
                <a href="{{ route('register') }}" style="color:var(--maroon);font-weight:600;text-decoration:none;">Daftar sebagai Tamu &rarr;</a>
            </div>

            <div class="demo-box">
                <strong>Akun contoh</strong> (password semua <code>password</code>):<br>
                <code>superadmin</code>— Administrator <br>
                <code>dedi.kurniawan</code>— Staf TU <br>
                <code>kepsek</code>— Kepala Sekolah <br>
                <code>waka.humas</code>— Waka Humas <br>
                <code>bendahara</code>— Bendahara <br>
                <code>andi.saputra</code>— Siswa <br>
                <code>telkom.witel</code>— Mitra Industri <br>
                <code>herlina.wati</code>— Tamu
            </div>
        </div>

        <div class="login-footer">© {{ date('Y') }} SMK Negeri 4 Tanjungpinang — Kepulauan Riau</div>
    </div>
</body>
</html>
