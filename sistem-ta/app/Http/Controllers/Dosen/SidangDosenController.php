<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SidangJadwal;

class SidangDosenController extends Controller
{
    // 1. Tampilkan Form Pembuatan Jadwal
    public function create()
    {
        // Ambil list Mahasiswa & Dosen untuk Dropdown
        // Pastikan di database user sudah ada role 'mahasiswa' dan 'dosen'
        $mahasiswas = User::where('role', 'mahasiswa')->get();
        $dosens = User::where('role', 'dosen')->orWhere('role', 'koordinator')->get();

        return view('dosen.sidang.create', compact('mahasiswas', 'dosens'));
    }

    // 2. Simpan Data Jadwal ke Database
    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:users,id',
            'dosen_id'     => 'required|exists:users,id', // Dosen Penguji
            'judul_ta'     => 'required|string|max:255',
            'jenis_sidang' => 'required|in:proposal,seminar_hasil,sidang_akhir',
            'tanggal'      => 'required|date|after:today',
            'jam_mulai'    => 'required',
            'jam_selesai'  => 'required|after:jam_mulai',
            'lokasi'       => 'required|string',
        ]);

        SidangJadwal::create([
            'mahasiswa_id' => $request->mahasiswa_id,
            'dosen_id'     => $request->dosen_id,
            'judul_ta'     => $request->judul_ta,
            'jenis_sidang' => $request->jenis_sidang,
            'tanggal'      => $request->tanggal,
            'jam_mulai'    => $request->jam_mulai,
            'jam_selesai'  => $request->jam_selesai,
            'lokasi'       => $request->lokasi,
            'status'       => 'dijadwalkan'
        ]);

        // Redirect kembali ke halaman approval atau dashboard
        return redirect()->route('dashboard')->with('success', 'Jadwal Sidang Berhasil Dibuat!');
    }
}