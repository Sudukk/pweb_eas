<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $isMahasiswa = $request->role === 'mahasiswa';

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'role'     => ['required', 'in:mahasiswa,dosen'],
            'jurusan'  => ['required', 'string', 'max:100'],
            'no_hp'    => ['required', 'string', 'max:20'],
            'nim'      => $isMahasiswa ? ['required', 'string', 'max:20', 'unique:users,nim'] : ['nullable'],
            'nip'      => !$isMahasiswa ? ['required', 'string', 'max:20', 'unique:users,nip'] : ['nullable'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'nim'      => $isMahasiswa ? $request->nim : null,
            'nip'      => !$isMahasiswa ? $request->nip : null,
            'jurusan'  => $request->jurusan,
            'no_hp'    => $request->no_hp,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route($request->role . '.dashboard');
    }
}
