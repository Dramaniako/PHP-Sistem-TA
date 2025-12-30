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
            'keputusan' => 'required|in:nilai,tolak', // Pilihan: Beri Nilai atau Tolak Langsung
            'nilai'     => 'nullable|numeric|min:0|max:100', // Wajib diisi jika keputusan == 'nilai'
            'komentar'  => 'nullable|string'
        ]);

        $proposal = Proposal::findOrFail($id);

        // Pastikan dosen berhak
        if ($proposal->dosen_pembimbing_id != auth()->id() && $proposal->dosen_penguji_id != auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        // 2. LOGIKA PENENTUAN STATUS
        $statusDb = 'pending';
        $nilaiMasuk = null;

        if ($request->keputusan == 'tolak') {
            // Jika Dosen memilih opsi TOLAK
            $statusDb = 'ditolak';
            $nilaiMasuk = 0; // Atau null, tergantung kebijakan (di sini saya set 0)
        } else {
            // Jika Dosen memberikan NILAI
            $nilaiMasuk = $request->nilai;
            
            // Logika > 75 Lulus, Selebihnya Revisi
            if ($nilaiMasuk > 75) {
                $statusDb = 'disetujui';
            } else {
                $statusDb = 'revisi';
            }
        }

        // 3. Update Database
        $proposal->update([
            'status'   => $statusDb,
            'nilai'    => $nilaiMasuk,
            'komentar' => $request->komentar
        ]);

        return back()->with('success', 'Penilaian proposal berhasil disimpan!');
    }
}