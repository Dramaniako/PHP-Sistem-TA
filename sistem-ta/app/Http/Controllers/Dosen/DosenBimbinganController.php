<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\BimbinganSlot;
use App\Traits\LogAktivitas; // Import Trait LogAktivitas
use Illuminate\Support\Facades\Auth;

class DosenBimbinganController extends Controller
{
    use LogAktivitas; // Gunakan Trait LogAktivitas

    // A. Simpan Jadwal Baru
    public function store(Request $request, $id)
    {
        $request->validate([
            'waktu_bimbingan' => 'required|date|after:now',
            'tempat' => 'required|string',
            'topik' => 'required|string',
        ]);

        $proposal = Proposal::findOrFail($id);

        // Pastikan hanya pembimbing yang bisa buat jadwal
        if ($proposal->dosen_pembimbing_id != auth()->id()) {
            abort(403, 'Akses ditolak. Anda bukan pembimbing mahasiswa ini.');
        }

        $slot = BimbinganSlot::create([
            'dosen_id' => auth()->id(),
            'mahasiswa_id' => $proposal->mahasiswa_id,
            'waktu_bimbingan' => $request->waktu_bimbingan,
            'tempat' => $request->tempat,
            'topik' => $request->topik,
            'status' => 'dijadwalkan'
        ]);

        // CATAT LOG RIWAYAT
        $this->simpanLog(
            $proposal->mahasiswa_id,
            'Bimbingan',
            'Penjadwalan Bimbingan',
            'Dosen Pembimbing menjadwalkan bimbingan baru pada ' . date('d M Y H:i', strtotime($request->waktu_bimbingan)) . ' di ' . $request->tempat . '. Topik: ' . $request->topik
        );

        return back()->with('success', 'Jadwal bimbingan berhasil dibuat!');
    }
}