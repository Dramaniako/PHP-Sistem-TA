<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\BimbinganSlot;
use App\Traits\LogAktivitas; // Tambahkan Trait LogAktivitas
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DosenProposalController extends Controller
{
    use LogAktivitas; // Gunakan Trait di sini

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
        $proposals = Proposal::with('mahasiswa')
            ->where('dosen_penguji_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('dosen.proposal.penguji', compact('proposals'));
    }

    // 3. LIHAT DETAIL PROPOSAL
    public function show($id)
    {
        $proposal = Proposal::with(['mahasiswa', 'dosenPembimbing'])
            ->where('id', $id)
            ->where(function ($q) {
                $q->where('dosen_pembimbing_id', auth()->id())
                    ->orWhere('dosen_penguji_id', auth()->id());
            })
            ->firstOrFail();

        $mahasiswa = $proposal->mahasiswa;

        // Jika user adalah Pembimbing
        if ($proposal->dosen_pembimbing_id == auth()->id()) {
            $jadwals = BimbinganSlot::where('mahasiswa_id', $proposal->mahasiswa_id)
                ->where('dosen_id', auth()->id())
                ->latest()
                ->get();

            return view('dosen.proposal.show', compact('proposal', 'jadwals', 'mahasiswa'));
        }

        // Jika user adalah Penguji
        if ($proposal->dosen_penguji_id == auth()->id()) {
            return view('dosen.proposal.show_penguji', compact('proposal', 'mahasiswa'));
        }

        abort(403, 'Akses ditolak.');
    }

    public function updateKeputusan(Request $request, $id)
    {
        $request->validate([
            'keputusan' => 'required|in:nilai,tolak',
            'nilai' => 'nullable|numeric|min:0|max:100',
            'komentar' => 'nullable|string'
        ]);

        $proposal = Proposal::findOrFail($id);
        $statusDb = 'pending';
        $nilaiMasuk = null;

        if ($request->keputusan == 'tolak') {
            $statusDb = 'ditolak';
            $nilaiMasuk = 0;
        } else {
            $nilaiMasuk = $request->nilai;
            if ($nilaiMasuk > 75) {
                $statusDb = 'disetujui';
            } else {
                $statusDb = 'revisi';
            }
        }

        $proposal->update([
            'status' => $statusDb,
            'nilai' => $nilaiMasuk,
            'komentar' => $request->komentar
        ]);

        // ==========================================================
        // INTEGRASI LOG RIWAYAT TA
        // ==========================================================
        $roleDosen = ($proposal->dosen_pembimbing_id == auth()->id()) ? 'Pembimbing' : 'Penguji';
        $labelStatus = strtoupper($statusDb);

        $this->simpanLog(
            $proposal->mahasiswa_id,
            'Proposal',
            'Keputusan ' . $roleDosen,
            "Dosen $roleDosen memberikan status $labelStatus dengan nilai $nilaiMasuk. Komentar: " . ($request->komentar ?? '-')
        );

        $pesan = 'Keputusan proposal berhasil disimpan!';

        if ($proposal->dosen_pembimbing_id == auth()->id()) {
            return redirect()->route('dosen.bimbingan.index')->with('success', $pesan);
        }

        if ($proposal->dosen_penguji_id == auth()->id()) {
            return redirect()->route('dosen.penguji.index')->with('success', $pesan);
        }

        return back()->with('success', $pesan);
    }

    // Method storeJadwal, responReschedule, dan download tetap dipertahankan
    // sesuai struktur file sebelumnya namun dipastikan sinkron.

    public function storeJadwal(Request $request, $id)
    {
        $request->validate([
            'waktu_bimbingan' => 'required|date|after:now',
            'tempat' => 'required|string',
            'topik' => 'required|string',
        ]);

        $proposal = Proposal::findOrFail($id);
        if ($proposal->dosen_pembimbing_id != auth()->id())
            abort(403);

        BimbinganSlot::create([
            'dosen_id' => auth()->id(),
            'mahasiswa_id' => $proposal->mahasiswa_id,
            'waktu_bimbingan' => $request->waktu_bimbingan,
            'tempat' => $request->tempat,
            'topik' => $request->topik,
            'status' => 'dijadwalkan'
        ]);

        return back()->with('success', 'Jadwal bimbingan dibuat!');
    }

    public function downloadProposal($id)
    {
        $proposal = Proposal::findOrFail($id);

        // Cek apakah kolom file_proposal tidak kosong
        if (!$proposal->file_proposal) {
            return back()->with('error', 'Mahasiswa belum mengunggah file proposal.');
        }

        // Gunakan Storage::exists untuk mengecek file di folder storage/app/private atau public
        if (!\Storage::exists($proposal->file_proposal)) {
            return back()->with('error', 'File fisik tidak ditemukan di server. Mahasiswa mungkin perlu mengunggah ulang.');
        }

        return \Storage::download($proposal->file_proposal, 'Proposal_' . $proposal->mahasiswa->name . '.pdf');
    }

    public function downloadKHS($id)
    {
        $proposal = Proposal::findOrFail($id);
        $filePath = $proposal->file_khs;

        if (!$filePath)
            return back()->with('error', 'File KHS belum diunggah.');

        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->download($filePath);
        }

        $absolutePath = public_path($filePath);
        if (file_exists($absolutePath))
            return response()->download($absolutePath);

        return back()->with('error', 'File KHS tidak ditemukan.');
    }
}