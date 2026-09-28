@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

@if ($tipe === 'staf')
    @php
        $judulDashboard = match ($u->role) {
            'admin' => 'Dashboard Administrator',
            'admin_tu' => 'Dashboard Tata Usaha',
            'kepala_sekolah' => 'Dashboard Kepala Sekolah',
            'waka' => 'Dashboard ' . ($u->unit ?: 'Waka'),
            'bendahara' => 'Dashboard Bendahara',
            default => 'Dashboard',
        };
    @endphp

    <h2 style="font-size:1.3rem;margin-bottom:.3rem;">{{ $judulDashboard }}</h2>
    <p style="color:var(--text-muted);font-size:.88rem;margin-bottom:1.25rem;">
        Ringkasan aktivitas persuratan, PKL, dan kerjasama industri yang perlu ditindaklanjuti hari ini.
    </p>

    <div class="stat-grid">
        <div class="stat-card {{ $stat['surat_masuk_menunggu'] > 0 ? 'highlight' : '' }}">
            <div class="stat-number">{{ $stat['surat_masuk_menunggu'] }}</div>
            <div class="stat-label">Surat masuk menunggu disposisi</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stat['surat_keluar_menunggu'] }}</div>
            <div class="stat-label">Surat keluar menunggu persetujuan</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stat['pkl_berlangsung'] }}</div>
            <div class="stat-label">Siswa PKL sedang berjalan</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stat['sertifikat_siap'] }}</div>
            <div class="stat-label">Sertifikat siap dicetak</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stat['mitra_aktif'] }}</div>
            <div class="stat-label">Mitra industri aktif</div>
        </div>
    </div>

    <div class="two-col">
        <div class="card">
            <h3 style="font-size:1rem;margin-bottom:.75rem;">Aktivitas terbaru</h3>
            @forelse ($aktivitasTerbaru as $item)
                <div class="timeline-item">
                    <span class="dot-lead"></span>{{ $item['teks'] }}
                    <div class="timeline-date">{{ \Illuminate\Support\Carbon::parse($item['tanggal'])->translatedFormat('d M Y') }}</div>
                </div>
            @empty
                <div class="empty-state">Belum ada aktivitas.</div>
            @endforelse
        </div>

        <div class="card">
            <h3 style="font-size:1rem;margin-bottom:.75rem;">Perlu tindak lanjut</h3>
            @forelse ($perluTindakLanjut as $item)
                <a href="{{ $item['url'] }}" class="todo-item">
                    <div class="todo-title">{{ $item['judul'] }}</div>
                    <div class="todo-sub">{{ $item['sub'] }}</div>
                </a>
            @empty
                <div class="empty-state">Tidak ada yang perlu ditindaklanjuti saat ini. 🎉</div>
            @endforelse
        </div>
    </div>

@elseif ($tipe === 'siswa')
    <h2 style="font-size:1.3rem;margin-bottom:.3rem;">Dashboard Siswa</h2>
    <p style="color:var(--text-muted);font-size:.88rem;margin-bottom:1.25rem;">Status PKL &amp; sertifikat milikmu sendiri.</p>

    @if (! $siswa)
        <div class="card"><div class="empty-state">Akunmu belum terhubung ke data siswa. Hubungi Tata Usaha.</div></div>
    @else
        <div class="card" style="margin-bottom:1rem;">
            <strong>{{ $siswa->nama }}</strong> — NIS {{ $siswa->nis }} &middot; {{ $siswa->kelas }} &middot; {{ $siswa->jurusan }}
        </div>
        <div class="card">
            <h3 style="font-size:1rem;margin-bottom:.75rem;">Riwayat PKL Saya</h3>
            <div class="table-wrap">
                <table class="simple">
                    <thead><tr><th>Lokasi PKL</th><th>Periode</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                    @forelse ($pkls as $p)
                        <tr>
                            <td>{{ $p->industri->nama_perusahaan }}</td>
                            <td>{{ $p->tanggal_mulai->translatedFormat('d M Y') }} – {{ $p->tanggal_selesai->translatedFormat('d M Y') }}</td>
                            <td>
                                @if ($p->status === 'selesai')<span class="badge-pill badge-green">Selesai</span>
                                @elseif ($p->status === 'berlangsung')<span class="badge-pill badge-blue">Berlangsung</span>
                                @else<span class="badge-pill badge-orange">Menunggu Persetujuan</span>
                                @endif
                            </td>
                            <td><a href="{{ route('pkl.show', $p) }}" class="btn btn-sm btn-outline">Detail{{ $p->status === 'selesai' ? ' & Sertifikat' : '' }}</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="4"><div class="empty-state">Belum ada data PKL.</div></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@elseif ($tipe === 'industri')
    <h2 style="font-size:1.3rem;margin-bottom:.3rem;">Dashboard Mitra Industri</h2>
    <p style="color:var(--text-muted);font-size:.88rem;margin-bottom:1.25rem;">Siswa PKL yang ditempatkan &amp; status kerjasama dengan perusahaanmu.</p>

    <div class="two-col">
        <div class="card">
            <h3 style="font-size:1rem;margin-bottom:.75rem;">Siswa PKL di Perusahaan Kami</h3>
            @forelse ($pkls as $p)
                <div class="timeline-item">
                    <span class="dot-lead"></span>{{ $p->siswa->nama }}
                    <span class="badge-pill {{ $p->status === 'selesai' ? 'badge-green' : ($p->status === 'berlangsung' ? 'badge-blue' : 'badge-orange') }}" style="margin-left:.4rem;">
                        {{ $p->status === 'selesai' ? 'Selesai' : ($p->status === 'berlangsung' ? 'Berlangsung' : 'Menunggu') }}
                    </span>
                </div>
            @empty
                <div class="empty-state">Belum ada siswa PKL yang ditempatkan.</div>
            @endforelse
        </div>
        <div class="card">
            <h3 style="font-size:1rem;margin-bottom:.75rem;">Status MoU Kami</h3>
            @forelse ($kerjasamas as $k)
                <div class="timeline-item">
                    <span class="dot-lead"></span>{{ $k->nomor_dokumen }}
                    <span class="badge-pill {{ $k->status()==='aktif' ? 'badge-green' : ($k->status()==='akan_berakhir' ? 'badge-orange' : 'badge-red') }}" style="margin-left:.4rem;">
                        {{ $k->labelStatus() }}
                    </span>
                    <div class="timeline-date">s/d {{ $k->berakhir_mou->translatedFormat('d M Y') }}</div>
                </div>
            @empty
                <div class="empty-state">Belum ada data kerjasama.</div>
            @endforelse
        </div>
    </div>

@elseif ($tipe === 'tamu')
    <h2 style="font-size:1.3rem;margin-bottom:.3rem;">Selamat datang, {{ $u->name }}</h2>
    <p style="color:var(--text-muted);font-size:.88rem;margin-bottom:1.25rem;">
        Kamu login sebagai <strong>Tamu</strong> — cuma bisa mengirim surat ke sekolah dari jarak jauh dan memantau statusnya.
    </p>

    <div class="card" style="margin-bottom:1rem;">
        <a href="{{ route('tamu.kirim') }}" class="btn btn-navy">+ Kirim Surat Baru</a>
    </div>

    <div class="card">
        <h3 style="font-size:1rem;margin-bottom:.75rem;">Surat Terakhir yang Kamu Kirim</h3>
        @forelse ($suratSaya as $s)
            <div class="timeline-item">
                <span class="dot-lead"></span>{{ $s->perihal }}
                <span class="badge-pill {{ $s->status === 'sudah_didisposisi' ? 'badge-green' : 'badge-orange' }}" style="margin-left:.4rem;">
                    {{ $s->status === 'sudah_didisposisi' ? 'Sudah Diproses' : 'Menunggu Diproses' }}
                </span>
                <div class="timeline-date">{{ $s->tanggal_terima->translatedFormat('d M Y') }}</div>
            </div>
        @empty
            <div class="empty-state">Kamu belum pernah mengirim surat.</div>
        @endforelse
        <div style="margin-top:.75rem;"><a href="{{ route('tamu.riwayat') }}" class="btn btn-sm btn-outline">Lihat semua riwayat &rarr;</a></div>
    </div>
@endif

@endsection
