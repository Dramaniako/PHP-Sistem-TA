<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SidangJadwal;
use App\Models\PengajuanPerubahan; // Pastikan model ini di-import
use Illuminate\Support\Facades\Auth;

class SidangDosenController extends Controller
{
    // =================================================================
    // 1. HALAMAN UTAMA (Jadwal & Request Perubahan) - TAMBAHAN BARU
    // =================================================================
    public function index()
    {
        $dosenId = Auth::id();

        // A. Ambil Jadwal Sidang Dimana User ini adalah Penguji
        $jadwals = SidangJadwal::with('mahasiswa')
                    ->where('dosen_id', $dosenId)
                    ->where('status', '!=', 'dibatalkan')
                    ->orderBy('tanggal', 'asc')
                    ->get();

        // B. Ambil Pengajuan Perubahan dari Mahasiswa
        // Logic: Cari pengajuan yang terkait dengan sidang milik dosen ini
        $pengajuans = PengajuanPerubahan::whereHas('sidang', function($q) use ($dosenId) {
                        $q->where('dosen_id', $dosenId);
                    })
                    ->where('status', 'pending') // Hanya ambil yang pending
                    ->with(['sidang.mahasiswa'])
                    ->get();

        return view('dosen.sidang.index', compact('jadwals', 'pengajuans'));
    }

    // =================================================================
    // 2. PROSES PERSETUJUAN JADWAL (Terima/Tolak) - TAMBAHAN BARU
    // =================================================================
    public function prosesPengajuan(Request $request, $id)
    {
        $request->validate(['keputusan' => 'required|in:terima,tolak']);

        $pengajuan = PengajuanPerubahan::with('sidang')->findOrFail($id);
        $sidang = $pengajuan->sidang;

        // Validasi: Pastikan dosen yang login berhak mengubah ini
        if ($sidang->dosen_id != Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses ke jadwal ini.');
        }

        if ($request->keputusan == 'terima') {
            // 1. Update Status Pengajuan
            $pengajuan->update(['status' => 'disetujui']);

            // 2. Update Jadwal Sidang Utama sesuai saran mahasiswa
            $sidang->update([
                'tanggal'     => $pengajuan->tanggal_saran,
                'jam_mulai'   => $pengajuan->jam_saran,
                // Otomatis set jam selesai +2 jam dari jam saran
                'jam_selesai' => date('H:i:s', strtotime($pengajuan->jam_saran) + 7200),
            ]);

            $message = 'Pengajuan diterima. Jadwal sidang telah diperbarui.';
        } else {
            // Tolak Pengajuan
            $pengajuan->update(['status' => 'ditolak']);
            $message = 'Pengajuan perubahan jadwal ditolak.';
        }

        return back()->with('success', $message);
    }

    // =================================================================
    // 3. CODE LAMA ANDA (Create & Store) - TETAP DIPERTAHANKAN
    // =================================================================
    public function create()
    {
        $mahasiswas = User::where('role', 'mahasiswa')->get();
        $dosens = User::where('role', 'dosen')->orWhere('role', 'koordinator')->get();
        return view('dosen.sidang.create', compact('mahasiswas', 'dosens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:users,id',
            'dosen_id'     => 'required|exists:users,id',
            'judul_ta'     => 'required|string|max:255',
            'jenis_sidang' => 'required|in:proposal,seminar_hasil,sidang_akhir', // Sesuaikan enum db
            'tanggal'      => 'required|date|after:today',
            'jam_mulai'    => 'required',
            'jam_selesai'  => 'required|after:jam_mulai',
            'lokasi'       => 'required|string',
        ]);

        SidangJadwal::create([
            'mahasiswa_id' => $request->mahasiswa_id,
            'dosen_id'     => $request->dosen_id,
            'judul_ta'     => $request->judul_ta,
            'jenis_sidang' => $request->jenis_sidang,
            'tanggal'      => $request->tanggal,
            'jam_mulai'    => $request->jam_mulai,
            'jam_selesai'  => $request->jam_selesai,
            'lokasi'       => $request->lokasi,
            'status'       => 'dijadwalkan'
        ]);

        return redirect()->route('dosen.sidang.index')->with('success', 'Jadwal Sidang Berhasil Dibuat!');
    }
}