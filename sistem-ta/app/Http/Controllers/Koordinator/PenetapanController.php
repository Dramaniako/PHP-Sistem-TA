<?php

namespace App\Http\Controllers\Koordinator;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Proposal;
use App\Models\DosenRequest;
use App\Models\SidangJadwal;
use App\Traits\LogAktivitas; // Tambahkan Trait LogAktivitas

class PenetapanController extends Controller
{
    use LogAktivitas; // Gunakan Trait di sini

    // 1. INDEX: Tampilkan Proposal
    public function index(Request $request)
    {
        $query = Proposal::with(['mahasiswa', 'dosenPembimbing', 'dosenPenguji']);

        // Fitur Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('mahasiswa', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        $proposals = Proposal::with(['mahasiswa', 'dosenPembimbing', 'dosenRequests.dosen'])
            ->latest()
            ->paginate(10);
        return view('koordinator.penetapan.index', compact('proposals'));
    }

    // 2. FORM PENETAPAN (Edit Mode)
    public function edit($id)
    {
        $proposal = Proposal::with('mahasiswa')->findOrFail($id);
        $dosens = User::whereIn('role', ['dosen', 'koordinator'])->get();

        return view('koordinator.penetapan.edit', compact('proposal', 'dosens'));
    }

    // 3. SIMPAN PENETAPAN (Update)
    public function update(Request $request, $id)
    {
        // $id di sini adalah Proposal ID (sesuai URL koordinator/penetapan/9)
        $proposal = Proposal::findOrFail($id);

        \DB::transaction(function () use ($request, $proposal) {
            // 1. Reset/Hapus Penguji Lama (Agar fresh)
            \App\Models\DosenRequest::where('proposal_id', $proposal->id)
                ->where('role', 'like', 'penguji_%')
                ->delete();

            // 2. Insert Penguji Baru dari Array yang dikirim View
            if ($request->has('dosen_penguji_id')) {
                foreach ($request->dosen_penguji_id as $index => $dosenId) {
                    if ($dosenId) {
                        \App\Models\DosenRequest::create([
                            'proposal_id' => $proposal->id,
                            'dosen_id' => $dosenId,
                            'role' => 'penguji_' . ($index + 1),
                            'status' => 'pending',
                            'pesan_penolakan' => null,
                        ]);
                    }
                }
            }

            // 3. Update Pembimbing (Jika ada)
            if ($request->filled('dosen_pembimbing_id')) {
                \App\Models\DosenRequest::updateOrCreate(
                    ['proposal_id' => $proposal->id, 'role' => 'pembimbing'],
                    [
                        'dosen_id' => $request->dosen_pembimbing_id,
                        'status' => 'pending',
                        'pesan_penolakan' => null
                    ]
                );
            }
        });

        return redirect()->route('koordinator.penetapan.index')->with('success', 'Penetapan dosen berhasil diperbarui.');
    }

    public function show(Request $request, $id)
    {
        $proposal = Proposal::with(['mahasiswa', 'dosenPembimbing'])->findOrFail($id);

        $search = $request->input('search');
        $sidebarProposals = Proposal::with('mahasiswa')
            ->when($search, function ($query, $search) {
                $query->whereHas('mahasiswa', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'desc')
            ->get();

        $dosens = User::where('role', 'dosen')->get();

        return view('koordinator.penetapan.detail', compact('proposal', 'sidebarProposals', 'dosens', 'search'));
    }

    public function updateKeputusan(Request $request, $id)
    {
        $request->validate([
            'status_review' => 'required|in:setuju,revisi,tolak',
            'komentar' => 'nullable|string'
        ]);

        $proposal = Proposal::findOrFail($id);

        $statusDb = 'pending';
        if ($request->status_review == 'setuju')
            $statusDb = 'disetujui';
        if ($request->status_review == 'revisi')
            $statusDb = 'revisi';
        if ($request->status_review == 'tolak')
            $statusDb = 'ditolak';

        $proposal->update([
            'status' => $statusDb,
            'komentar' => $request->komentar
        ]);

        // ==========================================================
        // INTEGRASI LOG RIWAYAT TA
        // ==========================================================
        $labelStatus = strtoupper($statusDb);
        $this->simpanLog(
            $proposal->mahasiswa_id,
            'Proposal',
            'Keputusan Koordinator',
            "Koordinator menetapkan status proposal menjadi $labelStatus. Komentar: " . ($request->komentar ?? '-')
        );

        return redirect()->back()->with('success', 'Keputusan berhasil disimpan!');
    }

    public function download($id)
    {
        $proposal = Proposal::with('mahasiswa')->findOrFail($id);

        // PERBAIKAN: Hapus disk('public') karena file ada di storage/app/proposals
        if (!Storage::exists($proposal->file_proposal)) {
            return back()->with('error', 'File fisik tidak ditemukan di server.');
        }

        $cleanName = 'Proposal_TA_' .
            $proposal->mahasiswa->nim . '_' .
            str_replace(' ', '_', $proposal->mahasiswa->name) . '.pdf';

        // PERBAIKAN: Gunakan default storage (disk local)
        return Storage::download($proposal->file_proposal, $cleanName);
    }
}