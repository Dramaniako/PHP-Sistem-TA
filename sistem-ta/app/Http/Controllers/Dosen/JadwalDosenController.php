<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SidangJadwal;
use App\Models\BimbinganSlot; // Tambahkan Model ini
use App\Models\PengajuanPerubahan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Tambahkan Carbon untuk memproses tanggal bimbingan

class JadwalDosenController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $events = [];

        // ==========================================
        // 1. DATA JADWAL SIDANG (Hanya Milik User)
        // ==========================================
        $jadwals = SidangJadwal::with(['dosen', 'mahasiswa'])
            ->where('dosen_id', $userId) // Filter hanya milik user
            ->where('status', '!=', 'dibatalkan')
            ->get();

        foreach ($jadwals as $jadwal) {
            $events[] = [
                'title' => "SIDANG: " . $jadwal->jenis_sidang,
                'start' => $jadwal->tanggal . 'T' . $jadwal->jam_mulai,
                'end' => $jadwal->tanggal . 'T' . $jadwal->jam_selesai,
                'color' => '#10B981', // Hijau (Emerald)
                'extendedProps' => [
                    'tipe_jadwal' => 'sidang', // Penanda tipe
                    'sidang_id' => $jadwal->id,
                    'lokasi' => $jadwal->lokasi,
                    'penguji' => $jadwal->dosen->name ?? '-',
                    'is_mine' => true
                ]
            ];
        }

        $pengajuans = PengajuanPerubahan::where('id', $userId)->get();

        // Tambahkan 'jadwals' di sini
        return view('dosen.sidang.index', compact('events', 'pengajuans', 'jadwals'));
    }
}