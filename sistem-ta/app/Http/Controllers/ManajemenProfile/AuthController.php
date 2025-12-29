<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        
        // 1️⃣ VALIDASI DULU
        $credentials = $request->validate([
            'nim' => 'required',
            'password' => 'required',
        ]);

        // 2️⃣ COBA LOGIN
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // 🔎 TES (boleh hapus setelah yakin)
            // dd('LOGIN OK', Auth::user(), Auth::check());

            return redirect()->route('dashboard');
        }

        // 3️⃣ GAGAL LOGIN
        return back()->withErrors([
            'nim' => 'NIM atau password salah',
        ]);
    }
}
