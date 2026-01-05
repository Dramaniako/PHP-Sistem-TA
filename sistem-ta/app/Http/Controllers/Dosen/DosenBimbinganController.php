<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\BimbinganSlot;

class DosenBimbinganController extends Controller
{
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

        BimbinganSlot::create([
            'dosen_id' => auth()->id(),
            'mahasiswa_id' => $proposal->mahasiswa_id,
            'waktu_bimbingan' => $request->waktu_bimbingan,
            'tempat' => $request->tempat,
            'topik' => $request->topik,
            'status' => 'dijadwalkan'
        ]);

        return back()->with('success', 'Jadwal bimbingan berhasil dibuat!');
    }

    // B. Respon Reschedule Mahasiswa
    public function update(Request $request, $slotId)
    {
        $slot = BimbinganSlot::where('id', $slotId)
                    ->where('dosen_id', auth()->id())
                    ->firstOrFail();

        if ($request->keputusan == 'terima') {
            $slot->update([
                'waktu_bimbingan' => $slot->waktu_reschedule,
                'status' => 'disetujui_reschedule',
                'waktu_reschedule' => null,
                'alasan_reschedule' => null
            ]);
        } else {
            $slot->update([
                'status' => 'dijadwalkan', // Kembali ke jadwal awal
                'waktu_reschedule' => null,
                'alasan_reschedule' => null
            ]);
        }

        return back()->with('success', 'Respon reschedule berhasil disimpan.');
    }
}