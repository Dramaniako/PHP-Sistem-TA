<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Dokumen;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil
     */
    public function show()
    {
        $user = Auth::user();

        // Ambil data mahasiswa
        $mahasiswa = Mahasiswa::with('prodi.fakultas')
            ->where('user_id', $user->id)
            ->first();

        // Jika belum ada → buat otomatis
        if (!$mahasiswa) {
            $mahasiswa = Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $user->nim,
                'nama_lengkap' => $user->name ?? '-',
                'prodi_id' => 1, // PASTIKAN ID INI ADA
            ]);
        }

        return view('profile.show', [
            'user' => $user,
            'mahasiswa' => $mahasiswa,
            'dosen' => Dosen::first(),
            'dokumen' => Dokumen::where('user_id', $user->id)->get(),
            'statusPengajuan' => 'Tidak Diterima',
            'statusKelulusan' => 'Tidak Lulus',
            'jadwal' => null,
        ]);
    }

    /**
     * Halaman edit profil
     */
    public function edit()
    {
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();

        return view('profile.edit', compact('mahasiswa'));
    }

    /**
     * Update profil
     */
    public function update(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->firstOrFail();

        if ($request->hasFile('foto')) {
            $mahasiswa->foto = $request->file('foto')->store('foto', 'public');
        }

        $mahasiswa->nama_lengkap = $request->nama_lengkap;
        $mahasiswa->save();

        return redirect()
            ->route('profile.show')
            ->with('success', 'Profil berhasil diperbarui');
    }
}
