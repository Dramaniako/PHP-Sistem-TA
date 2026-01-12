<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Traits\LogAktivitas; // Tambahkan Trait
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProposalMahasiswaController extends Controller
{
    use LogAktivitas; // Gunakan Trait di sini

    public function index()
    {
        // Ambil proposal milik mahasiswa yang sedang login
        $proposal = Proposal::with('dosenPembimbing')
            ->where('mahasiswa_id', auth()->id())
            ->latest()
            ->first();

        return view('mahasiswa.proposal.status', compact('proposal'));
    }

    // Form Pengajuan Judul
    public function create()
    {
        $proposal = Proposal::where('mahasiswa_id', Auth::id())->first();

        // JIKA proposal sudah ada DAN statusnya BUKAN 'ditolak'
        if ($proposal && $proposal->status !== 'ditolak') {
            return redirect()->route('mahasiswa.sidang.status')
                ->with('error', 'Anda sudah memiliki proposal yang sedang diproses.');
        }

        return view('mahasiswa.proposal.create', compact('proposal'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $existing = Proposal::where('mahasiswa_id', Auth::id())->first();

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'file_proposal' => $existing ? 'nullable|file|mimes:pdf|max:5120' : 'required|file|mimes:pdf|max:5120',
            'file_khs' => $existing ? 'nullable|file|mimes:pdf|max:2048' : 'required|file|mimes:pdf|max:2048',
        ]);

        // 2. Siapkan data dasar
        $data = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'status' => 'pending',
        ];

        // 3. Proses Upload File
        if ($request->hasFile('file_proposal')) {
            $data['file_proposal'] = $request->file('file_proposal')->store('proposals', 'public');
        }

        if ($request->hasFile('file_khs')) {
            $data['file_khs'] = $request->file('file_khs')->store('khs', 'public');
        }

        // 4. Update atau Create Proposal
        $proposal = Proposal::updateOrCreate(
            ['mahasiswa_id' => Auth::id()],
            $data
        );

        // 5. CATAT LOG RIWAYAT
        $aksi = $existing ? 'Pembaruan Proposal' : 'Pengajuan Proposal Baru';
        $this->simpanLog(
            Auth::id(),
            'Proposal',
            $aksi,
            'Mahasiswa mengajukan judul: ' . $request->judul,
            $data['file_proposal'] ?? ($existing ? $existing->file_proposal : null)
        );

        return redirect()->route('mahasiswa.proposal.status')
            ->with('success', 'Proposal berhasil diperbarui dan sedang menunggu verifikasi!');
    }

    public function download($id)
    {
        $proposal = Proposal::findOrFail($id);

        if ($proposal->mahasiswa_id != auth()->id()) {
            abort(403, 'Anda tidak berhak mengunduh dokumen ini.');
        }

        if (!Storage::disk('public')->exists($proposal->file_proposal)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        $cleanName = 'Proposal_TA_' . auth()->user()->nim . '.pdf';

        return Storage::disk('public')->download($proposal->file_proposal, $cleanName);
    }

    public function updateFile(Request $request, $id)
    {
        $request->validate([
            'tipe' => 'required|in:proposal,khs,ta',
            'file' => 'required|mimes:pdf|max:5120',
        ]);

        $proposal = Proposal::where('id', $id)->where('mahasiswa_id', auth()->id())->firstOrFail();

        $column = 'file_' . $request->tipe;

        $path = $request->file('file')->store($request->tipe, 'public');

        if ($proposal->$column && Storage::disk('public')->exists($proposal->$column)) {
            Storage::disk('public')->delete($proposal->$column);
        }

        $proposal->update([$column => $path]);

        // CATAT LOG RIWAYAT
        $this->simpanLog(
            auth()->id(),
            'Proposal',
            'Pembaruan File',
            'Mahasiswa memperbarui file ' . strtoupper($request->tipe),
            $path
        );

        return back()->with('success', 'File berhasil diperbarui!');
    }

    public function uploadDraft(Request $request)
    {
        $request->validate([
            'file_ta' => 'required|mimes:pdf|max:10240',
        ]);

        $proposal = Proposal::where('mahasiswa_id', auth()->id())->firstOrFail();

        if ($proposal->status !== 'disetujui') {
            return back()->with('error', 'Anda hanya dapat mengupload Draft TA setelah proposal disetujui.');
        }

        if ($request->hasFile('file_ta')) {
            if ($proposal->file_ta && Storage::exists('public/' . $proposal->file_ta)) {
                Storage::delete('public/' . $proposal->file_ta);
            }

            $path = $request->file('file_ta')->store('draft_ta', 'public');

            $proposal->update([
                'file_ta' => $path
            ]);

            // CATAT LOG RIWAYAT
            $this->simpanLog(
                auth()->id(),
                'Proposal',
                'Upload Draft TA',
                'Mahasiswa mengunggah draf laporan Tugas Akhir',
                $path
            );
        }

        return back()->with('success', 'Draft Tugas Akhir berhasil diupload!');
    }

    public function updateRevisi(Request $request, $id)
    {
        $request->validate([
            'file_proposal' => 'required|mimes:pdf|max:10240', // Max 10MB
        ]);

        $proposal = \App\Models\Proposal::findOrFail($id);

        // 1. Hapus file lama jika ada (opsional)
        if ($proposal->file_proposal) {
            Storage::delete($proposal->file_proposal);
        }

        // 2. Simpan file baru
        $path = $request->file('file_proposal')->store('proposals');

        // 3. Update database
        $proposal->update([
            'file_proposal' => $path,
            'status' => 'pending', // Reset ke pending agar dicek ulang oleh koordinator
            'komentar' => null,     // Hapus komentar revisi yang lama
        ]);

        return redirect()->route('mahasiswa.proposal.status')
            ->with('success', 'Proposal revisi berhasil dikirim.');
    }
}