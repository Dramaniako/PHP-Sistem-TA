<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use Illuminate\Support\Facades\Auth;

class ProposalMahasiswaController extends Controller
{
    // Form Pengajuan Judul
    public function create()
    {
        // Cek apakah mahasiswa sudah pernah mengajukan?
        $existing = Proposal::where('mahasiswa_id', Auth::id())->first();
        if ($existing) {
            return redirect()->route('sidang.index')->with('error', 'Anda sudah mengajukan proposal.');
        }

        return view('mahasiswa.proposal.create');
    }

    // Simpan Judul
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        Proposal::create([
            'mahasiswa_id' => Auth::id(),
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'status' => 'pending', // Menunggu penetapan dosen
        ]);

        return redirect()->route('sidang.index')->with('success', 'Proposal berhasil diajukan! Menunggu penetapan dosen.');
    }
}