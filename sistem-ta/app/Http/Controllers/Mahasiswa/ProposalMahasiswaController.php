<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Jangan lupa import ini

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

    public function store(Request $request)
{
    // 1. Validasi
    $request->validate([
        'judul' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        // Validasi File: Wajib, harus PDF, max 5MB (5120 KB)
        'file_proposal' => 'required|file|mimes:pdf|max:5120', 
    ]);

    // 2. Proses Upload File
    if ($request->hasFile('file_proposal')) {
        // Simpan ke folder 'storage/app/public/proposals'
        // 'public' adalah nama disk (agar bisa diakses url-nya nanti)
        $filePath = $request->file('file_proposal')->store('proposals', 'public');
    }

    // 3. Simpan ke Database
    \App\Models\Proposal::create([
        // UBAH 'user_id' MENJADI 'mahasiswa_id'
        'mahasiswa_id' => auth()->id(), 
        
        'judul' => $request->judul,
        'deskripsi' => $request->deskripsi,
        'file_proposal' => $filePath,
        'status' => 'pending', 
    ]);

    // 4. Redirect dengan pesan sukses
    return redirect()->route('mahasiswa.sidang.index')
        ->with('success', 'Proposal berhasil diajukan!');
}

public function download($id)
{
    $proposal = Proposal::findOrFail($id);

    // Keamanan: Pastikan yang download adalah pemilik proposal
    if ($proposal->mahasiswa_id != auth()->id()) {
        abort(403, 'Anda tidak berhak mengunduh dokumen ini.');
    }

    // Cek keberadaan file
    if (!Storage::disk('public')->exists($proposal->file_proposal)) {
        return back()->with('error', 'File tidak ditemukan.');
    }

    // Nama file rapi
    $cleanName = 'Proposal_TA_' . auth()->user()->nim . '.pdf';

    return Storage::disk('public')->download($proposal->file_proposal, $cleanName);
}
}