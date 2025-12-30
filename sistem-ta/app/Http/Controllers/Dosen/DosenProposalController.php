<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use Illuminate\Support\Facades\Storage;

class DosenProposalController extends Controller
{
    // 1. LIHAT DAFTAR BIMBINGAN (Dosen sebagai Pembimbing)
    public function indexBimbingan()
    {
        $proposals = Proposal::with('mahasiswa')
            ->where('dosen_pembimbing_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('dosen.proposal.bimbingan', compact('proposals'));
    }

    // 2. LIHAT DAFTAR PENGUJI (Dosen sebagai Penguji)
    public function indexPenguji()
    {
        // Pastikan kolom 'dosen_penguji_id' sudah ada di tabel proposals
        $proposals = Proposal::with('mahasiswa')
            ->where('dosen_penguji_id', auth()->id()) // Sesuaikan nama kolom di DB Anda
            ->latest()
            ->paginate(10);

        return view('dosen.proposal.penguji', compact('proposals'));
    }

    // 3. LIHAT DETAIL PROPOSAL
    public function show($id)
    {
        // Pastikan dosen hanya bisa lihat proposal yang berhubungan dengannya
        $proposal = Proposal::with(['mahasiswa', 'dosenPembimbing'])
            ->where('id', $id)
            ->where(function($q) {
                $q->where('dosen_pembimbing_id', auth()->id())
                  ->orWhere('dosen_penguji_id', auth()->id());
            })
            ->firstOrFail();

        return view('dosen.proposal.show', compact('proposal'));
    }

    public function updateKeputusan(Request $request, $id)
    {
        // 1. Validasi
        $request->validate([
            'status'   => 'required|in:approved,pending,rejected', // Inggris (dari Form)
            'komentar' => 'nullable|string'
        ]);

        $proposal = Proposal::findOrFail($id);

        // 2. Keamanan: Pastikan yang mengubah adalah Dosen yang bersangkutan
        if ($proposal->dosen_pembimbing_id != auth()->id() && $proposal->dosen_penguji_id != auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah proposal ini.');
        }

        // 3. Mapping Status (Inggris -> Indonesia) sesuai database
        $statusDb = 'pending';
        if ($request->status == 'approved') $statusDb = 'disetujui';
        if ($request->status == 'pending')  $statusDb = 'revisi';
        if ($request->status == 'rejected') $statusDb = 'ditolak';

        // 4. Update Database
        $proposal->update([
            'status'   => $statusDb,
            'komentar' => $request->komentar
        ]);

        return back()->with('success', 'Status proposal berhasil diperbarui!');
    }
}