<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\SidangJadwal; // Pastikan ini ada
use App\Models\User;
use App\Models\PengajuanPerubahan;
use Illuminate\Support\Facades\DB;

class SidangKoordinatorController extends Controller
{
    public function index()
    {
        // PERBAIKAN DI SINI:
        // Gunakan 'SidangJadwal', bukan 'Sidang'
        $jadwal_sidang = SidangJadwal::with(['mahasiswa', 'dosen'])->latest()->get();
        
        return view('koordinator.sidang.index', compact('jadwal_sidang'));
    }

    // ============================================================
    // Method Create (Menyesuaikan dengan View Baru)
    // ============================================================
    public function create()
    {
        // Ambil data mahasiswa yang:
        // 1. Memiliki proposal
        // 2. Status proposal 'disetujui'
        $mahasiswas = User::whereHas('proposal', function($q) {
            $q->where('status', 'disetujui');
        })->get();

        return view('koordinator.sidang.create', compact('mahasiswas'));
    }

    // ============================================================
    // API: Auto-fill Data (Dipanggil via AJAX/Fetch di View)
    // ============================================================
    public function getProposalData($id)
    {
        $proposal = Proposal::with(['dosenPembimbing', 'dosenPenguji'])
                    ->where('mahasiswa_id', $id)
                    ->where('status', 'disetujui') 
                    ->latest()
                    ->first();

        if(!$proposal) {
            return response()->json(['error' => 'Data tidak ditemukan atau belum disetujui'], 404);
        }

        return response()->json([
            'proposal_id' => $proposal->id,
            'judul'       => $proposal->judul,
            'pembimbing'  => $proposal->dosenPembimbing->name ?? 'Belum ditentukan',
            'penguji'     => $proposal->dosenPenguji->name ?? 'Belum ditentukan',
            'file_khs'    => $proposal->file_khs ? asset('storage/'.$proposal->file_khs) : null,
            'file_ta'     => $proposal->file_ta ? asset('storage/'.$proposal->file_ta) : null,
        ]);
    }

    // ============================================================
    // Method Store (Mapping Input View ke Database)
    // ============================================================
    public function store(Request $request)
    {
        $request->validate([
            'proposal_id'    => 'required|exists:proposals,id',
            'tanggal_sidang' => 'required|date',
            'jam_mulai'      => 'required',
            'jam_selesai'    => 'required|after:jam_mulai',
            'ruangan'        => 'required|string',
        ]);

        $proposal = \App\Models\Proposal::findOrFail($request->proposal_id);

        SidangJadwal::create([
            'mahasiswa_id' => $proposal->mahasiswa_id,
            'dosen_id'     => $proposal->dosen_penguji_id, // Penguji menjadi Dosen Penilai Sidang
            'judul_ta'     => $proposal->judul,
            'jenis_sidang' => 'sidang_akhir', // Sesuai format database (snake_case)
            'tanggal'      => $request->tanggal_sidang,
            'jam_mulai'    => $request->jam_mulai,
            'jam_selesai'  => $request->jam_selesai,
            'lokasi'       => $request->ruangan,
            'status'       => 'dijadwalkan'
        ]);

        return redirect()->route('koordinator.sidang.index')->with('success', 'Jadwal Sidang Berhasil Disimpan!');
    }

    // ============================================================
    // 1. HALAMAN APPROVAL (Melihat Request Masuk)
    // ============================================================
    public function approval()
    {
        $pengajuans = PengajuanPerubahan::with(['sidangJadwal.mahasiswa', 'sidangJadwal.dosen'])
                        ->where('status', 'pending')
                        ->latest()
                        ->get();

        return view('koordinator.sidang.approval', compact('pengajuans'));
    }

    // ============================================================
    // 2. PROSES APPROVAL (Terima / Tolak)
    // ============================================================
    public function prosesApproval(Request $request, $id)
    {
        $request->validate(['keputusan' => 'required|in:terima,tolak']);
        
        $pengajuan = PengajuanPerubahan::findOrFail($id);
        $sidang = SidangJadwal::findOrFail($pengajuan->sidang_jadwal_id);

        if ($request->keputusan == 'terima') {
            DB::transaction(function() use ($pengajuan, $sidang) {
                // 1. Update Jadwal Sidang Utama ke Waktu Baru
                $sidang->update([
                    'tanggal'     => $pengajuan->tanggal_saran,
                    'jam_mulai'   => $pengajuan->jam_saran,
                    // Set jam selesai otomatis +2 jam (atau sesuaikan logika Anda)
                    'jam_selesai' => date('H:i:s', strtotime($pengajuan->jam_saran) + 7200),
                ]);

                // 2. Tandai pengajuan disetujui
                $pengajuan->update(['status' => 'disetujui']);
            });

            return back()->with('success', 'Jadwal berhasil diubah sesuai pengajuan.');
        } else {
            // Jika Ditolak
            $pengajuan->update(['status' => 'ditolak']);
            return back()->with('info', 'Pengajuan perubahan jadwal ditolak.');
        }
    }
}