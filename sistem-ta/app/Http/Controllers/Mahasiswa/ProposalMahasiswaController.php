<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Jangan lupa import ini

class ProposalMahasiswaController extends Controller
{
    public function index()
    {
        // Ambil proposal milik mahasiswa yang sedang login
        $proposal = Proposal::with('dosenPembimbing')
                    ->where('mahasiswa_id', auth()->id())
                    ->latest() // Ambil yang paling baru diajukan
                    ->first();

        return view('mahasiswa.proposal.status', compact('proposal'));
    }
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
        $request->validate([
            'judul'         => 'required|string|max:255',
            'deskripsi'     => 'required|string',
            'file_proposal' => 'required|mimes:pdf|max:2048',
            'file_khs'      => 'required|mimes:pdf|max:2048', // <--- Wajib Upload KHS
        ]);

        // Upload File Proposal
        $pathProposal = $request->file('file_proposal')->store('proposals', 'public');
        
        // Upload File KHS
        $pathKhs = $request->file('file_khs')->store('khs', 'public');

        Proposal::create([
            'mahasiswa_id'  => auth()->id(),
            'judul'         => $request->judul,
            'deskripsi'     => $request->deskripsi,
            'file_proposal' => $pathProposal,
            'file_khs'      => $pathKhs, // <--- Simpan Path KHS
            'status'        => 'pending',
        ]);

        return redirect()->route('mahasiswa.sidang.index')->with('success', 'Proposal dan KHS berhasil diajukan!');
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