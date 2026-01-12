<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\BimbinganSlot;
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
        // 1. Ambil data proposal (sudah benar)
        $proposal = Proposal::with(['mahasiswa', 'dosenPembimbing'])
            ->where('id', $id)
            ->where(function($q) {
                $q->where('dosen_pembimbing_id', auth()->id())
                ->orWhere('dosen_penguji_id', auth()->id());
            })
            ->firstOrFail();

        // 2. DEFINISIKAN VARIABEL MAHASISWA (SOLUSI ERROR)
        $mahasiswa = $proposal->mahasiswa;

        // 3. Logika Prioritas View
        
        // Jika user adalah Pembimbing
        if ($proposal->dosen_pembimbing_id == auth()->id()) {
            $jadwals = BimbinganSlot::where('mahasiswa_id', $proposal->mahasiswa_id)
                        ->where('dosen_id', auth()->id())
                        ->latest()
                        ->get();

            // FIX: Tambahkan 'mahasiswa' ke dalam compact
            return view('dosen.proposal.show', compact('proposal', 'jadwals', 'mahasiswa'));
        }

        // Jika user adalah Penguji
        if ($proposal->dosen_penguji_id == auth()->id()) {
            // FIX: Tambahkan 'mahasiswa' ke dalam compact
            return view('dosen.proposal.show_penguji', compact('proposal', 'mahasiswa'));
        }

        abort(403, 'Akses ditolak.');
    }

    public function updateKeputusan(Request $request, $id)
    {
        // ... (Validasi dan Logika Simpan Database tetap sama) ...
        
        // 1. Validasi
        $request->validate([
            'keputusan' => 'required|in:nilai,tolak',
            'nilai'     => 'nullable|numeric|min:0|max:100',
            'komentar'  => 'nullable|string'
        ]);

        $proposal = Proposal::findOrFail($id);

        // ... (Logika penentuan status DB tetap sama) ...
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
            'status'   => $statusDb,
            'nilai'    => $nilaiMasuk,
            'komentar' => $request->komentar
        ]);

        // ==========================================================
        // PERUBAHAN DI SINI (LOGIKA REDIRECT)
        // ==========================================================

        $pesan = 'Keputusan proposal berhasil disimpan!';

        // Cek 1: Jika user adalah PEMBIMBING
        if ($proposal->dosen_pembimbing_id == auth()->id()) {
            // Ganti 'dosen.bimbingan.index' dengan nama route daftar bimbingan Anda yang sebenarnya
            return redirect()->route('dosen.bimbingan.index')->with('success', $pesan);
        }

        // Cek 2: Jika user adalah PENGUJI
        if ($proposal->dosen_penguji_id == auth()->id()) {
            // Ganti 'dosen.penguji.index' dengan nama route daftar ujian Anda yang sebenarnya
            return redirect()->route('dosen.penguji.index')->with('success', $pesan);
        }

        // Fallback (jika ada error logika, kembalikan ke halaman sebelumnya)
        return back()->with('success', $pesan);
    }

    // A. Simpan Jadwal Baru
    public function storeJadwal(Request $request, $id)
    {
        $request->validate([
            'waktu_bimbingan' => 'required|date|after:now',
            'tempat' => 'required|string',
            'topik' => 'required|string',
        ]);

        $proposal = Proposal::findOrFail($id);
        
        if ($proposal->dosen_pembimbing_id != auth()->id()) abort(403);

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

    // B. Respon Reschedule Mahasiswa
    public function responReschedule(Request $request, $slotId)
    {
        $slot = BimbinganSlot::where('id', $slotId)->where('dosen_id', auth()->id())->firstOrFail();

        if ($request->keputusan == 'terima') {
            $slot->update([
                'waktu_bimbingan' => $slot->waktu_reschedule,
                'status' => 'disetujui_reschedule',
                'waktu_reschedule' => null,
                'alasan_reschedule' => null
            ]);
        } else {
            $slot->update([
                'status' => 'dijadwalkan', // Kembali ke jadwal awal
                'waktu_reschedule' => null,
                'alasan_reschedule' => null
            ]);
        }

        return back()->with('success', 'Respon tersimpan.');
    }

    public function downloadProposal($id)
    {
        $proposal = Proposal::findOrFail($id);

        // Ambil nama file dari database
        // Prioritaskan kolom 'file_proposal', jika kosong gunakan 'file_path'
        $filePath = $proposal->file_proposal ?? $proposal->file_path;

        if (!$filePath) {
            return back()->with('error', 'File dokumen tidak ditemukan di database.');
        }

        // CARA 1: Cek di Storage Laravel (storage/app/public)
        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->download($filePath);
        }

        // CARA 2: Cek di Folder Public Biasa (public/uploads/...)
        // public_path() menghasilkan string path sistem (D:\Project\...), 
        // tapi kita memasukkannya ke dalam response()->download() agar browser menerimanya sebagai file, bukan link.
        
        // Cek path langsung (jika di db sudah ada 'uploads/')
        $absolutePath = public_path($filePath);
        if (file_exists($absolutePath)) {
            return response()->download($absolutePath);
        }

        // Cek dengan prefix 'uploads/' (jika di db hanya nama file)
        $absolutePathManual = public_path('uploads/' . basename($filePath));
        if (file_exists($absolutePathManual)) {
            return response()->download($absolutePathManual);
        }

        return back()->with('error', 'File fisik tidak ditemukan di server.');
    }

    public function downloadKHS($id)
    {
        $proposal = Proposal::findOrFail($id);
        $filePath = $proposal->file_khs; //

        if (!$filePath) {
            return back()->with('error', 'File KHS belum diunggah.');
        }

        // Logika pencarian file sama seperti di atas
        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->download($filePath);
        }

        $absolutePath = public_path($filePath);
        if (file_exists($absolutePath)) {
            return response()->download($absolutePath);
        }
        
        $absolutePathManual = public_path('uploads/' . basename($filePath));
        if (file_exists($absolutePathManual)) {
            return response()->download($absolutePathManual);
        }

        return back()->with('error', 'File KHS tidak ditemukan di server.');
    }
}