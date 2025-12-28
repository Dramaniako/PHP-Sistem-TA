<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SidangJadwal;
use App\Models\PengajuanPerubahan;
use Illuminate\Support\Facades\Auth;

class SidangMahasiswaController extends Controller
{
    public function index()
    {
        // PERBAIKAN DISINI:
        // Hapus 'ruangan' dari array with([]) karena relasinya belum ada.
        $jadwals = SidangJadwal::with(['dosen', 'mahasiswa']) 
                    ->where('status', '!=', 'dibatalkan')
                    ->get();

        $events = [];
        $userId = Auth::id();

        foreach ($jadwals as $jadwal) {
            $isMySidang = ($jadwal->mahasiswa_id == $userId);

            $color = $isMySidang ? '#10B981' : '#6B7280';
            
            $title = $isMySidang 
                ? "SIDANG SAYA (" . $jadwal->jenis_sidang . ")" 
                : "Terisi: " . ($jadwal->mahasiswa->name ?? 'Mhs Lain');

            $events[] = [
                'title' => $title,
                'start' => $jadwal->tanggal . 'T' . $jadwal->jam_mulai,
                'end'   => $jadwal->tanggal . 'T' . $jadwal->jam_selesai,
                'color' => $color,
                'extendedProps' => [
                    'sidang_id' => $jadwal->id,
                    
                    // PERBAIKAN DISINI:
                    // Langsung ambil dari kolom 'lokasi' di database,
                    // jangan panggil relasi ->ruangan->nama_ruangan
                    'lokasi'    => $jadwal->lokasi, 
                    
                    'penguji'   => $jadwal->dosen->name ?? '-',
                    'is_mine'   => $isMySidang
                ]
            ];
        }

        return view('mahasiswa.sidang.index', compact('events'));
    }

    public function ajukanPerubahan(Request $request, $id)
    {
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
            'sidang_jadwal_id'   => $sidang->id,
            'mahasiswa_id'       => Auth::id(),
            'alasan'             => $request->alasan_perubahan,
            'tanggal_saran'      => $request->tanggal_baru_saran,
            'jam_saran'          => $request->jam_baru_saran,
            'status'             => 'pending'
        ]);

        return back()->with('success', 'Pengajuan berhasil dikirim.');
    }
}