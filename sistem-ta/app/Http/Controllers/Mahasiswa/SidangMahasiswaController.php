<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SidangJadwal;
use App\Models\BimbinganSlot; // Tambahkan Model ini
use App\Models\PengajuanPerubahan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Tambahkan Carbon untuk memproses tanggal bimbingan

class SidangMahasiswaController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $events = [];

        // ==========================================
        // 1. DATA JADWAL SIDANG (Hanya Milik User)
        // ==========================================
        $jadwals = SidangJadwal::with(['dosen', 'mahasiswa']) 
                    ->where('mahasiswa_id', $userId) // Filter hanya milik user
                    ->where('status', '!=', 'dibatalkan')
                    ->get();

        foreach ($jadwals as $jadwal) {
            $events[] = [
                'title' => "SIDANG: " . $jadwal->jenis_sidang,
                'start' => $jadwal->tanggal . 'T' . $jadwal->jam_mulai,
                'end'   => $jadwal->tanggal . 'T' . $jadwal->jam_selesai,
                'color' => '#10B981', // Hijau (Emerald)
                'extendedProps' => [
                    'tipe_jadwal' => 'sidang', // Penanda tipe
                    'sidang_id'   => $jadwal->id,
                    'lokasi'      => $jadwal->lokasi, 
                    'penguji'     => $jadwal->dosen->name ?? '-',
                    'is_mine'     => true
                ]
            ];
        }

        // ==========================================
        // 2. DATA JADWAL BIMBINGAN (Hanya Milik User)
        // ==========================================
        $bimbingans = BimbinganSlot::with('dosen')
                    ->where('mahasiswa_id', $userId)
                    ->where('status', '!=', 'dibatalkan') // Sesuaikan jika ada status lain
                    ->get();

        foreach ($bimbingans as $bim) {
            // Asumsi waktu_bimbingan adalah datetime di database
            // Kita set durasi default 1 jam karena bimbingan biasanya tidak punya jam selesai fix
            $start = Carbon::parse($bim->waktu_bimbingan);
            $end   = $start->copy()->addHour(); 

            $events[] = [
                'title' => "BIMBINGAN: " . ($bim->dosen->name ?? 'Dosen'),
                'start' => $start->toIso8601String(),
                'end'   => $end->toIso8601String(),
                'color' => '#3B82F6', // Biru
                'extendedProps' => [
                    'tipe_jadwal' => 'bimbingan', // Penanda tipe
                    'lokasi'      => $bim->tempat,
                    'topik'       => $bim->topik,
                    'is_mine'     => true
                ]
            ];
        }

        return view('mahasiswa.sidang.index', compact('events'));
    }

    // ... method ajukanPerubahan tetap sama ...
    public function ajukanPerubahan(Request $request, $id)
    {
        // (Kode sama seperti yang Anda miliki sebelumnya)
        $sidang = SidangJadwal::findOrFail($id);
        if ($sidang->mahasiswa_id != Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses.');
        }
        $request->validate([
            'alasan_perubahan'   => 'required|string|min:10',
            'tanggal_baru_saran' => 'required|date|after:today',
            'jam_baru_saran'     => 'required'
        ]);
        PengajuanPerubahan::create([
            'sidang_jadwal_id' => $sidang->id,
            'mahasiswa_id'     => Auth::id(),
            'alasan'           => $request->alasan_perubahan,
            'tanggal_saran'    => $request->tanggal_baru_saran,
            'jam_saran'        => $request->jam_baru_saran,
            'status'           => 'pending'
        ]);
        return back()->with('success', 'Pengajuan berhasil dikirim.');
    }
}