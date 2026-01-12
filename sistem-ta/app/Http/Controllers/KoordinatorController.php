<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanPerubahan;
use App\Models\SidangJadwal;
use Illuminate\Support\Facades\DB;

class KoordinatorController extends Controller
{
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

    public function approve(Request $request, $id)
    {
        $request->validate([
            'tanggal_baru' => 'required|date',
            'jam_baru' => 'required',
        ]);

        $pengajuan = PengajuanPerubahan::findOrFail($id);
        $sidang = SidangJadwal::findOrFail($pengajuan->sidang_jadwal_id);

        \DB::transaction(function () use ($request, $pengajuan, $sidang) {
            // 1. Update Jadwal Sidang Utama
            $sidang->update([
                'tanggal' => $request->tanggal_baru,
                'jam_mulai' => $request->jam_baru,
                'jam_selesai' => date('H:i:s', strtotime($request->jam_baru) + 7200), // +2 jam
            ]);

            // 2. Tandai Pengajuan Selesai
            $pengajuan->update(['status' => 'disetujui']);

            // 3. Simpan ke Log Riwayat Mahasiswa (Agar muncul di Timeline)
            $this->simpanLog(
                $sidang->mahasiswa_id,
                'Sidang',
                'Jadwal Diperbarui',
                'Koordinator telah memperbarui jadwal sidang berdasarkan permintaan dosen.'
            );
        });

        return redirect()->back()->with('success', 'Jadwal berhasil diperbarui.');
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
}