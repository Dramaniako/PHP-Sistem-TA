<?php

namespace App\Http\Controllers\Koordinator;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Proposal;
use App\Models\DosenRequest;

class PenetapanController extends Controller
{
    // 1. INDEX: Tampilkan Proposal yang BELUM punya pembimbing
    public function index(Request $request)
    {
        $query = Proposal::with('mahasiswa')
                    ->whereNull('dosen_pembimbing_id'); // Filter: Cari yang belum ditetapkan

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('mahasiswa', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $proposals = $query->latest()->paginate(10);
        return view('koordinator.penetapan.index', compact('proposals'));
    }

    // 2. FORM PENETAPAN (Edit Mode)
    // Kita menerima ID Proposal, bukan ID Mahasiswa lagi
    public function edit($id)
    {
        $proposal = Proposal::with('mahasiswa')->findOrFail($id);
        
        // Ambil Dosen untuk Dropdown
        $dosens = User::whereIn('role', ['dosen', 'koordinator'])->get();

        return view('koordinator.penetapan.edit', compact('proposal', 'dosens'));
    }

    // 3. SIMPAN PENETAPAN (Update)
    public function update(Request $request, $id)
    {
        $request->validate([
            'dosen_pembimbing_id' => 'nullable|exists:users,id',
            'dosen_penguji_id'    => 'nullable|exists:users,id',
        ]);

        $proposal = Proposal::findOrFail($id);

        // 1. CEK & KIRIM REQUEST PEMBIMBING
        if ($request->dosen_pembimbing_id) {
            // Cek apakah dosen ini sudah pernah direquest untuk proposal ini dan statusnya pending/accepted
            $existing = DosenRequest::where('proposal_id', $id)
                        ->where('role', 'pembimbing')
                        ->whereIn('status', ['pending', 'accepted'])
                        ->first();

            // Jika belum ada request, atau request sebelumnya ditolak, buat baru
            if (!$existing) {
                DosenRequest::create([
                    'proposal_id' => $id,
                    'dosen_id' => $request->dosen_pembimbing_id,
                    'role' => 'pembimbing',
                    'status' => 'pending'
                ]);
            }
        }

        // 2. CEK & KIRIM REQUEST PENGUJI
        if ($request->dosen_penguji_id) {
            $existing = DosenRequest::where('proposal_id', $id)
                        ->where('role', 'penguji_1')
                        ->whereIn('status', ['pending', 'accepted'])
                        ->first();

            if (!$existing) {
                DosenRequest::create([
                    'proposal_id' => $id,
                    'dosen_id' => $request->dosen_penguji_id,
                    'role' => 'penguji_1',
                    'status' => 'pending'
                ]);
            }
        }

        return redirect()->route('koordinator.penetapan.index')->with('success', 'Permintaan kesediaan telah dikirim ke Dosen terkait!');
    }

    public function show(Request $request, $id)
    {
        // 1. Ambil Data Proposal Utama
        $proposal = Proposal::with(['mahasiswa', 'dosenPembimbing'])->findOrFail($id);

        // 2. Data Sidebar (List Proposal + Fitur Cari)
        $search = $request->input('search');
        $sidebarProposals = Proposal::with('mahasiswa')
            ->when($search, function($query, $search) {
                $query->whereHas('mahasiswa', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'desc')
            ->get();

        // 3. Data Dosen (Untuk Dropdown Penetapan)
        $dosens = User::where('role', 'dosen')->get();

        return view('koordinator.penetapan.detail', compact('proposal', 'sidebarProposals', 'dosens', 'search'));
    }

    public function updateKeputusan(Request $request, $id)
    {
        $request->validate([
            'status_review' => 'required|in:setuju,revisi,tolak',
            'komentar'      => 'nullable|string'
        ]);

        $proposal = Proposal::findOrFail($id);

        // Mapping status review ke status database
        $statusDb = 'pending';
        if ($request->status_review == 'setuju') $statusDb = 'disetujui';
        if ($request->status_review == 'revisi') $statusDb = 'revisi'; // Pastikan enum di DB mendukung 'revisi' atau gunakan 'pending'
        if ($request->status_review == 'tolak')  $statusDb = 'ditolak';

        $proposal->update([
            'status'   => $statusDb,
            'komentar' => $request->komentar
        ]);

        return redirect()->back()->with('success', 'Keputusan berhasil disimpan!');
    }

    public function download($id)
    {
        // Ambil data proposal beserta data mahasiswa
        $proposal = Proposal::with('mahasiswa')->findOrFail($id);

        // Cek file di disk 'public' agar tidak error Metadata
        if (!Storage::disk('public')->exists($proposal->file_proposal)) {
            return back()->with('error', 'File fisik tidak ditemukan di server.');
        }

        // Buat nama file baru yang rapi: "Proposal_TA_[NIM]_[Nama].pdf"
        $cleanName = 'Proposal_TA_' . 
                    $proposal->mahasiswa->nim . '_' . 
                    str_replace(' ', '_', $proposal->mahasiswa->name) . '.pdf';

        // Download dari disk 'public' dengan nama baru
        return Storage::disk('public')->download($proposal->file_proposal, $cleanName);
    }
}