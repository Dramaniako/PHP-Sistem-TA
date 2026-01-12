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
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Tambahkan validasi untuk 'nim'
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'string', 'max:20', 'unique:users'], // <-- Cek NIM unik
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Masukkan 'nim' ke dalam proses create user
        $user = User::create([
            'name' => $request->name,
            'nim' => $request->nim,       // <-- TAMBAHKAN BARIS INI
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'mahasiswa',        // Default role mahasiswa
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
