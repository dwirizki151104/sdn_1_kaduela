<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register', [
            'kelasList' => Kelas::orderBy('nama_kelas')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'nis' => ['required', 'string', 'max:30', 'unique:siswa,nis'],
            'nama_siswa' => ['required', 'string', 'max:255'],
            'jk' => ['required', 'in:L,P'],
            'tanggal_lahir' => ['nullable', 'date'],
            'alamat' => ['nullable', 'string'],
            'id_kelas' => ['required', 'exists:kelas,id_kelas'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'username' => $data['username'],
                'password' => $data['password'],
                'role' => 'siswa',
                'status' => 'aktif',
            ]);

            Siswa::create([
                'id_user' => $user->id_user,
                'nis' => $data['nis'],
                'nama_siswa' => $data['nama_siswa'],
                'jk' => $data['jk'],
                'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
                'alamat' => $data['alamat'] ?? null,
                'id_kelas' => $data['id_kelas'],
            ]);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
