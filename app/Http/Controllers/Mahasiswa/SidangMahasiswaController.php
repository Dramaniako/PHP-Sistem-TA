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
                    'penguji'     => $bim->dosen->name ?? '-',
                    'is_mine'     => true
                ]
            ];
        }

        return view('mahasiswa.sidang.index', compact('events'));
    }
}