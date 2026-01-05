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
        // 1. Validasi Input (Termasuk file_khs)
        $request->validate([
            'judul'         => 'required|string|max:255',
            'deskripsi'     => 'required|string',
            'file_proposal' => 'required|file|mimes:pdf|max:5120', // Maks 5MB
            'file_khs'      => 'required|file|mimes:pdf|max:2048', // WAJIB ADA: Maks 2MB
        ]);

        // 2. Cek apakah Mahasiswa sudah punya proposal (Opsional, untuk mencegah duplikasi)
        $existing = Proposal::where('mahasiswa_id', auth()->id())->first();
        if($existing) {
            return back()->with('error', 'Anda sudah mengajukan proposal sebelumnya.');
        }

        // 3. Proses Upload File
        $pathProposal = null;
        $pathKhs = null;

        if ($request->hasFile('file_proposal')) {
            // Simpan di folder 'storage/app/public/proposals'
            $pathProposal = $request->file('file_proposal')->store('proposals', 'public');
        }

        // PERBAIKAN: Logika Simpan KHS
        if ($request->hasFile('file_khs')) {
            // Simpan di folder 'storage/app/public/khs'
            $pathKhs = $request->file('file_khs')->store('khs', 'public');
        }

        // 4. Simpan ke Database
        Proposal::create([
            'mahasiswa_id'  => auth()->id(),
            'judul'         => $request->judul,
            'deskripsi'     => $request->deskripsi,
            'file_proposal' => $pathProposal, // Kolom file utama
            'file_khs'      => $pathKhs,      // KOLOM YANG SEBELUMNYA HILANG
            'status'        => 'pending',
        ]);

        return redirect()->route('mahasiswa.proposal.index')
                         ->with('success', 'Proposal dan KHS berhasil diajukan!');
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

    // Tambahkan method ini di dalam class
    public function updateFile(Request $request, $id)
    {
        $request->validate([
            'tipe' => 'required|in:proposal,khs,ta', 
            'file' => 'required|mimes:pdf|max:5120',
        ]);

        $proposal = \App\Models\Proposal::where('id', $id)->where('mahasiswa_id', auth()->id())->firstOrFail();
        
        $column = 'file_' . $request->tipe; // Menjadi: file_proposal, file_khs, atau file_ta
        
        // Upload & Simpan Path
        $path = $request->file('file')->store($request->tipe, 'public');
        
        // Hapus file lama jika ada
        if ($proposal->$column && \Illuminate\Support\Facades\Storage::disk('public')->exists($proposal->$column)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($proposal->$column);
        }

        $proposal->update([$column => $path]);

        return back()->with('success', 'File berhasil diperbarui!');
    }

    public function uploadDraft(Request $request)
    {
        $request->validate([
            'file_ta' => 'required|mimes:pdf|max:10240', // Maks 10MB, PDF only
        ]);

        // Ambil proposal milik user yang sedang login
        $proposal = \App\Models\Proposal::where('mahasiswa_id', auth()->id())->firstOrFail();

        // Validasi: Hanya boleh upload jika status sudah disetujui
        if ($proposal->status !== 'disetujui') {
            return back()->with('error', 'Anda hanya dapat mengupload Draft TA setelah proposal disetujui.');
        }

        // Proses Upload File
        if ($request->hasFile('file_ta')) {
            // Hapus file lama jika ada (opsional, agar storage tidak penuh)
            if ($proposal->file_ta && \Illuminate\Support\Facades\Storage::exists('public/' . $proposal->file_ta)) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $proposal->file_ta);
            }

            // Simpan file baru ke folder 'draft_ta' di disk public
            $path = $request->file('file_ta')->store('draft_ta', 'public');
            
            // Update database
            $proposal->update([
                'file_ta' => $path
            ]);
        }

        return back()->with('success', 'Draft Tugas Akhir berhasil diupload!');
    }
}