<?php

namespace App\Http\Controllers;

use App\Models\Industri;
use App\Models\PasswordResetRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private function pastikanAdmin(): void
    {
        abort_unless(auth()->user()->role === 'admin', 403, 'Hanya Administrator yang bisa mengelola akun pengguna.');
    }

    public function index(Request $request)
    {
        $this->pastikanAdmin();

        $users = User::when($request->q, fn ($q) => $q->where(function ($w) use ($request) {
            $w->where('name', 'like', "%{$request->q}%")
                ->orWhere('username', 'like', "%{$request->q}%");
            }))
            ->when($request->role, fn ($q) => $q->where('role', $request->role))
            ->orderBy('role')->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $resetRequests = PasswordResetRequest::where('status', 'baru')->latest()->get();

        return view('users.index', compact('users', 'resetRequests'));
    }

    public function create()
    {
        $this->pastikanAdmin();

        $industris = Industri::orderBy('nama_perusahaan')->get();

        return view('users.create', compact('industris'));
    }

    public function store(Request $request)
    {
        $this->pastikanAdmin();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:admin,admin_tu,kepala_sekolah,waka,bendahara,siswa,industri,tamu'],
            'jabatan' => ['nullable', 'string'],
            'unit' => ['nullable', 'string'],
            'instansi_asal' => ['nullable', 'string'],
            'industri_id' => ['nullable', 'exists:industris,id'],
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('users.index')->with('success', 'Akun pengguna berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $this->pastikanAdmin();

        $industris = Industri::orderBy('nama_perusahaan')->get();

        return view('users.edit', compact('user', 'industris'));
    }

    public function update(Request $request, User $user)
    {
        $this->pastikanAdmin();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'in:admin,admin_tu,kepala_sekolah,waka,bendahara,siswa,industri,tamu'],
            'jabatan' => ['nullable', 'string'],
            'unit' => ['nullable', 'string'],
            'instansi_asal' => ['nullable', 'string'],
            'industri_id' => ['nullable', 'exists:industris,id'],
        ]);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Akun pengguna berhasil diperbarui.');
    }

    public function selesaikanResetRequest(PasswordResetRequest $resetRequest)
    {
        $this->pastikanAdmin();

        $resetRequest->update(['status' => 'selesai']);

        return back()->with('success', 'Permintaan reset kata sandi ditandai selesai.');
    }

    public function destroy(User $user)
    {
        $this->pastikanAdmin();

        abort_if($user->id === auth()->id(), 400, 'Tidak bisa menghapus akun sendiri yang sedang login.');

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Akun pengguna berhasil dihapus.');
    }
}
