<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanPerubahan;
use App\Models\SidangJadwal;
use Illuminate\Support\Facades\DB;

class KoordinatorController extends Controller
{
    public function profile()
    {
        $user = auth()->user();

        // ===== STATISTIK =====
        $totalProposal = \App\Models\Proposal::count();
        $pendingProposal = \App\Models\Proposal::where('status', 'pending')->count();
        $approvedProposal = \App\Models\Proposal::where('status', 'disetujui')->count();
        $totalPengajuan = \App\Models\PengajuanPerubahan::count();

        // ===== AKTIVITAS =====
        $recentProposals = \App\Models\Proposal::with('mahasiswa')
            ->latest()
            ->take(5)
            ->get();

        $recentPengajuan = \App\Models\PengajuanPerubahan::with('mahasiswa')
            ->latest()
            ->take(5)
            ->get();

        return view('koordinator.profile', compact(
            'user',
            'totalProposal',
            'pendingProposal',
            'approvedProposal',
            'totalPengajuan',
            'recentProposals',
            'recentPengajuan'
        ));
    }

    // Halaman List Pengajuan Pending
    public function index()
    {
        // Ambil pengajuan yang statusnya masih 'pending'
        $pengajuans = PengajuanPerubahan::with(['sidangJadwal', 'mahasiswa'])
                        ->where('status', 'pending')
                        ->orderBy('created_at', 'asc') // Yang lama di atas
                        ->get();

        return view('koordinator.approval', compact('pengajuans'));
    }

    // Logic Menyetujui (Approve)
    public function approve($id)
    {
        // Gunakan Transaction agar Data Konsisten
        DB::transaction(function () use ($id) {
            $pengajuan = PengajuanPerubahan::findOrFail($id);
            $jadwalSidang = SidangJadwal::findOrFail($pengajuan->sidang_jadwal_id);

            // 1. Update Jadwal Utama dengan Tanggal Baru dari Pengajuan
            $jadwalSidang->update([
                'tanggal'     => $pengajuan->tanggal_saran,
                'jam_mulai'   => $pengajuan->jam_saran,
                // Kita asumsi durasi sama (2 jam), jadi jam selesai disesuaikan
                // Atau biarkan manual nanti. Disini saya set jam selesai manual +2 jam
                'jam_selesai' => date('H:i:s', strtotime($pengajuan->jam_saran) + 7200), 
            ]);

            // 2. Ubah Status Pengajuan jadi Disetujui
            $pengajuan->update(['status' => 'disetujui']);
        });

        return back()->with('success', 'Jadwal berhasil diperbarui sesuai pengajuan.');
    }

    // Logic Menolak (Reject)
    public function reject(Request $request, $id)
    {
        $pengajuan = PengajuanPerubahan::findOrFail($id);

        $pengajuan->update([
            'status' => 'ditolak',
            'catatan_admin' => 'Maaf, slot waktu tersebut sudah penuh.' // Bisa dibuat dinamis dari form
        ]);

        return back()->with('error', 'Pengajuan perubahan ditolak.');
    }

    public function show()
    {
        return view('koordinator.profile', [
            'user' => auth()->user(),

            // Statistik
            'totalProposal' => Proposal::count(),
            'pendingProposal' => Proposal::where('status', 'pending')->count(),
            'approvedProposal' => Proposal::where('status', 'disetujui')->count(),
            'totalPengajuan' => PengajuanPerubahan::count(),

            // Aktivitas
            'recentProposals' => Proposal::latest()->take(5)->get(),
            'recentPengajuan' => PengajuanPerubahan::latest()->take(5)->get(),
        ]);
    }
}