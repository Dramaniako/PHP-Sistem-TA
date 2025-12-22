<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BimbinganSlot;
use Illuminate\Support\Facades\Auth;

class PenjadwalanController extends Controller
{
    // [Fitur 1: Set Ketersediaan Dosen] - Dosen memilih tanggal, jam, lokasi
    public function storeKetersediaan(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'lokasi' => 'required|string',
        ]);

        BimbinganSlot::create([
            'dosen_id' => Auth::id(), // ID Dosen yang sedang login
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'lokasi' => $request->lokasi,
            'status' => 'tersedia'
        ]);

        return back()->with('success', 'Slot waktu berhasil dibuat!');
    }

    // [Fitur 2: Pengajuan Bimbingan] - Mahasiswa memilih slot & upload file
    public function ajukanBimbingan(Request $request, $id)
    {
        $slot = BimbinganSlot::findOrFail($id);
        
        // Validasi input mahasiswa
        $request->validate([
            'topik_bimbingan' => 'required|string',
            'file_bab' => 'required|mimes:pdf,doc,docx|max:2048' // Upload file Bab TA 
        ]);

        // Simpan file
        $filePath = $request->file('file_bab')->store('dokumen_bimbingan');

        // Update slot dengan data mahasiswa
        $slot->update([
            'mahasiswa_id' => Auth::id(),
            'topik_bimbingan' => $request->topik_bimbingan,
            'file_path' => $filePath,
            'status' => 'menunggu_konfirmasi' // Status berubah jadi pending
        ]);

        return back()->with('success', 'Pengajuan bimbingan terkirim!');
    }

    // [Fitur 3: Persetujuan Bimbingan] - Dosen menyetujui/menolak
    public function updateStatus(Request $request, $id)
    {
        $slot = BimbinganSlot::findOrFail($id);
        
        // Validasi status hanya boleh 'disetujui' atau 'ditolak'
        $request->validate([
            'status' => 'required|in:disetujui,ditolak'
        ]);

        $slot->update([
            'status' => $request->status
        ]);

        // Disini bisa ditambahkan logika Notifikasi Email (SMTP) sesuai FR-7 [cite: 1279]
        
        return back()->with('success', 'Status bimbingan diperbarui.');
    }

    public function index()
    {
        // Ambil data jadwal milik dosen yang sedang login
        // 'with' digunakan agar query lebih efisien (Eager Loading)
        $slots = BimbinganSlot::where('dosen_id', Auth::id())
                    ->with('mahasiswa')
                    ->orderBy('tanggal', 'desc')
                    ->get();

        // Kirim data $slots ke view
        return view('dosen.penjadwalan.index', compact('slots'));
    }
}