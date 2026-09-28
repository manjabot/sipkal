<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - SIPKAL</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sipkal.css') }}">
</head>
<body>
@php($u = auth()->user())
<div class="app-shell">
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="logo-circle">S4</div>
            <div>
                <div class="brand-name">SIPKAL</div>
                <div class="brand-sub">SMKN 4 Tanjungpinang</div>
            </div>
        </div>
        <hr>

        <div class="nav-group-label">Ringkasan</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span>Dashboard</span>
        </a>

        @if ($u && $u->role === 'tamu')
            {{-- ===== MENU KHUSUS TAMU: cuma bisa kirim surat & lihat riwayat kirimannya ===== --}}
            <div class="nav-group-label">Persuratan Saya</div>
            <a href="{{ route('tamu.kirim') }}" class="nav-link {{ request()->routeIs('tamu.kirim') ? 'active' : '' }}">
                <span>Kirim Surat</span>
            </a>
            <a href="{{ route('tamu.riwayat') }}" class="nav-link {{ request()->routeIs('tamu.riwayat') ? 'active' : '' }}">
                <span>Riwayat Surat Saya</span>
            </a>

        @elseif ($u && $u->stafInternal())
            {{-- ===== MENU STAF INTERNAL: akses penuh data persuratan sekolah ===== --}}
            <div class="nav-group-label">Persuratan</div>
            <a href="{{ route('surat-masuk.index') }}" class="nav-link {{ request()->routeIs('surat-masuk.*') ? 'active' : '' }}">
                <span>Surat Masuk</span>
                @if(($countSuratMasuk ?? 0) > 0)<span class="count">{{ $countSuratMasuk }}</span>@endif
            </a>
            <a href="{{ route('surat-keluar.index') }}" class="nav-link {{ request()->routeIs('surat-keluar.*') ? 'active' : '' }}">
                <span>Surat Keluar</span>
                @if(($countSuratKeluar ?? 0) > 0)<span class="count">{{ $countSuratKeluar }}</span>@endif
            </a>
            <a href="{{ route('disposisi.index') }}" class="nav-link {{ request()->routeIs('disposisi.*') ? 'active' : '' }}">
                <span>Disposisi</span>
                @if(($countDisposisi ?? 0) > 0)<span class="count">{{ $countDisposisi }}</span>@endif
            </a>

            <div class="nav-group-label">PKL, Industri &amp; Sertifikat</div>
            <a href="{{ route('pkl.index') }}" class="nav-link {{ request()->routeIs('pkl.*') ? 'active' : '' }}">
                <span>PKL &amp; Sertifikat</span>
                @if(($countPkl ?? 0) > 0)<span class="count">{{ $countPkl }}</span>@endif
            </a>
            <a href="{{ route('kerjasama.index') }}" class="nav-link {{ request()->routeIs('kerjasama.*') ? 'active' : '' }}">
                <span>Kerjasama Industri</span>
                @if(($countKerjasama ?? 0) > 0)<span class="count">{{ $countKerjasama }}</span>@endif
            </a>
            <a href="{{ route('sertifikat.index') }}" class="nav-link {{ request()->routeIs('sertifikat.*') ? 'active' : '' }}">
                <span>Sertifikat Umum</span>
            </a>

            <div class="nav-group-label">Lainnya</div>
            <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                <span>Laporan</span>
            </a>
            @if ($u->bolehKelola(['admin_tu']))
                <a href="{{ route('akun-tamu.index') }}" class="nav-link {{ request()->routeIs('akun-tamu.*') ? 'active' : '' }}">
                    <span>Persetujuan Akun Tamu</span>
                    @if(($countAkunTamu ?? 0) > 0)<span class="count">{{ $countAkunTamu }}</span>@endif
                </a>
            @endif
            @if ($u->role === 'admin')
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <span>Manajemen Pengguna</span>
                </a>
            @endif

        @elseif ($u && $u->role === 'siswa')
            {{-- ===== MENU SISWA: cuma data PKL & sertifikat miliknya sendiri ===== --}}
            <div class="nav-group-label">PKL Saya</div>
            <a href="{{ route('pkl.index') }}" class="nav-link {{ request()->routeIs('pkl.*') ? 'active' : '' }}">
                <span>Status PKL Saya</span>
            </a>
            <a href="{{ route('sertifikat.index') }}" class="nav-link {{ request()->routeIs('sertifikat.*') ? 'active' : '' }}">
                <span>Sertifikat Umum</span>
            </a>

        @elseif ($u && $u->role === 'industri')
            {{-- ===== MENU MITRA INDUSTRI: cuma data siswa PKL & MoU miliknya sendiri ===== --}}
            <div class="nav-group-label">Kemitraan Kami</div>
            <a href="{{ route('pkl.index') }}" class="nav-link {{ request()->routeIs('pkl.*') ? 'active' : '' }}">
                <span>Siswa PKL di Perusahaan Kami</span>
            </a>
            <a href="{{ route('kerjasama.index') }}" class="nav-link {{ request()->routeIs('kerjasama.*') ? 'active' : '' }}">
                <span>Status MoU Kami</span>
            </a>
        @endif
    </aside>

    <div class="main-area">
        <div class="topbar">
            <div class="topbar-left">
                <button class="hamburger" id="hamburgerBtn" type="button" aria-label="Menu">&#9776;</button>
                <div>
                    <h1>Sistem Informasi Persuratan, PKL &amp; Kerjasama Industri</h1>
                    <div class="topbar-sub">SMK Negeri 4 Tanjungpinang — Kepulauan Riau</div>
                </div>
            </div>
            <div class="topbar-right">
                <div class="topbar-date">Hari ini: {{ now()->translatedFormat('j F Y') }}</div>
                <div class="avatar">
                    <div class="avatar-circle">{{ $u?->initials() }}</div>
                    <div class="avatar-text">
                        <div class="avatar-name">{{ $u?->name }}</div>
                        <div class="avatar-role">{{ $u?->labelJabatan() }}</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-link" style="background:none;">Keluar</button>
                </form>
            </div>
        </div>

        <div class="content">
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

            @yield('content')
        </div>
    </div>
</div>
<script>
    (function () {
        var sidebar = document.getElementById('sidebar');
        var backdrop = document.getElementById('sidebarBackdrop');
        var btn = document.getElementById('hamburgerBtn');
        function toggle() {
            sidebar.classList.toggle('open');
            backdrop.classList.toggle('open');
        }
        btn && btn.addEventListener('click', toggle);
        backdrop && backdrop.addEventListener('click', toggle);
    })();
</script>
</body>
</html>
