<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TamuApprovalController extends Controller
{
    private function pastikanBolehApprove(): void
    {
        abort_unless(auth()->user()->bolehKelola(['admin_tu']), 403, 'Hanya Tata Usaha/Administrator yang bisa menyetujui akun Tamu.');
    }

    public function index()
    {
        $this->pastikanBolehApprove();

        $menunggu = User::where('role', 'tamu')->where('status_akun', 'menunggu_persetujuan')->latest()->get();
        $riwayat = User::where('role', 'tamu')->whereIn('status_akun', ['aktif', 'ditolak'])->latest()->take(15)->get();

        return view('akun-tamu.index', compact('menunggu', 'riwayat'));
    }

    public function setujui(User $user)
    {
        $this->pastikanBolehApprove();

        abort_unless($user->role === 'tamu', 400);

        $user->update(['status_akun' => 'aktif']);

        return back()->with('success', "Akun tamu \"{$user->name}\" disetujui dan sekarang bisa login.");
    }

    public function tolak(User $user)
    {
        $this->pastikanBolehApprove();

        abort_unless($user->role === 'tamu', 400);

        $user->update(['status_akun' => 'ditolak']);

        return back()->with('success', "Akun tamu \"{$user->name}\" ditolak.");
    }
}
