<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $user = User::where('username', $request->username)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'username' => 'Nama pengguna atau kata sandi salah.',
            ])->onlyInput('username');
        }

        // Akun Tamu harus disetujui Tata Usaha dulu sebelum bisa login.
        if (! $user->sudahAktif()) {
            $pesan = $user->status_akun === 'menunggu_persetujuan'
                ? 'Akun kamu masih menunggu persetujuan dari Tata Usaha. Silakan coba lagi nanti.'
                : 'Akun kamu ditolak oleh Tata Usaha. Hubungi sekolah untuk informasi lebih lanjut.';

            return back()->withErrors(['username' => $pesan])->onlyInput('username');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /** Pendaftaran akun Tamu (pengirim surat eksternal), harus di-ACC Tata Usaha dulu. */
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'instansi_asal' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        User::create([
            'name' => $data['name'],
            'instansi_asal' => $data['instansi_asal'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'role' => 'tamu',
            'status_akun' => 'menunggu_persetujuan',
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat. Silakan tunggu persetujuan dari Tata Usaha sebelum bisa login (biasanya diproses dalam 1x24 jam kerja).');
    }

    /** Permintaan lupa kata sandi: dicatat, lalu ditindaklanjuti manual oleh Administrator/TU. */
    public function showForgot()
    {
        return view('auth.lupa-password');
    }

    public function submitForgot(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string'],
            'catatan' => ['nullable', 'string'],
        ]);

        PasswordResetRequest::create($data);

        return redirect()->route('login')->with('success', 'Permintaan reset kata sandi sudah dicatat. Administrator/Tata Usaha akan menghubungimu untuk verifikasi identitas dan mengatur ulang kata sandi.');
    }
}
