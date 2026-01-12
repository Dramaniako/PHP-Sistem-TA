<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SidangJadwal;
use App\Models\PengajuanPerubahan;
use App\Traits\LogAktivitas; // Import Trait LogAktivitas
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SidangDosenController extends Controller
{
    use LogAktivitas; // Gunakan Trait di sini

    // =================================================================
    // 1. HALAMAN UTAMA (Jadwal & Request Perubahan)
    // =================================================================
    public function index()
    {
        $dosenId = Auth::id();

        $jadwals = SidangJadwal::with('mahasiswa')
            ->where('dosen_id', $dosenId)
            ->where('status', '!=', 'dibatalkan')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Pastikan menggunakan 'sidang' sesuai relasi yang dibuat di Model tadi
        $pengajuans = PengajuanPerubahan::whereHas('sidangJadwal', function ($q) use ($dosenId) {
            $q->where('dosen_id', $dosenId);
        })
            ->where('status', 'pending')
            ->with(['sidangJadwal.mahasiswa']) // Gunakan 'sidang' bukan 'sidangJadwal'
            ->get();

        return view('dosen.sidang.index', compact('jadwals', 'pengajuans'));
    }

    // =================================================================
    // 2. PROSES PERSETUJUAN JADWAL (Respon terhadap Mahasiswa)
    // =================================================================
    public function prosesPengajuan(Request $request, $id)
    {
        $request->validate(['keputusan' => 'required|in:terima,tolak']);

        $pengajuan = PengajuanPerubahan::with('sidangJadwal')->findOrFail($id);
        $sidang = $pengajuan->sidang;

        if (!$sidang) {
            return back()->with('error', 'Data jadwal sidang utama tidak ditemukan atau sudah dihapus.');
        }

        if ($sidang->dosen_id != Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses ke jadwal ini.');
        }

        if ($request->keputusan == 'terima') {
            $pengajuan->update(['status' => 'disetujui']);

            $sidang->update([
                'tanggal' => $pengajuan->tanggal_saran,
                'jam_mulai' => $pengajuan->jam_saran,
                'jam_selesai' => date('H:i:s', strtotime($pengajuan->jam_saran) + 7200),
            ]);

            // CATAT LOG RIWAYAT (Terima Reschedule dari Mahasiswa)
            $this->simpanLog(
                $sidang->mahasiswa_id,
                'Sidang',
                'Reschedule Sidang Disetujui',
                "Dosen menyetujui perubahan jadwal ke tanggal " . date('d M Y', strtotime($pengajuan->tanggal_saran)) . " pukul " . $pengajuan->jam_saran
            );

            $message = 'Pengajuan diterima. Jadwal sidang telah diperbarui.';
        } else {
            $pengajuan->update(['status' => 'ditolak']);

            // CATAT LOG RIWAYAT (Tolak Reschedule dari Mahasiswa)
            $this->simpanLog(
                $sidang->mahasiswa_id,
                'Sidang',
                'Reschedule Sidang Ditolak',
                "Dosen menolak permintaan perubahan jadwal sidang dari mahasiswa."
            );

            $message = 'Pengajuan perubahan jadwal ditolak.';
        }

        return back()->with('success', $message);
    }

    // =================================================================
    // 3. CREATE & STORE JADWAL (Koordinator/Dosen)
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
            'dosen_id' => 'required|exists:users,id',
            'judul_ta' => 'required|string|max:255',
            'jenis_sidang' => 'required|in:proposal,seminar_hasil,sidang_akhir',
            'tanggal' => 'required|date|after:today',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'lokasi' => 'required|string',
        ]);

        $sidang = SidangJadwal::create([
            'mahasiswa_id' => $request->mahasiswa_id,
            'dosen_id' => $request->dosen_id,
            'judul_ta' => $request->judul_ta,
            'jenis_sidang' => $request->jenis_sidang,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'lokasi' => $request->lokasi,
            'status' => 'dijadwalkan'
        ]);

        // CATAT LOG RIWAYAT (Pembuatan Jadwal Baru)
        $this->simpanLog(
            $request->mahasiswa_id,
            'Sidang',
            'Jadwal Sidang Dibuat',
            "Jadwal " . ucfirst(str_replace('_', ' ', $request->jenis_sidang)) . " telah ditetapkan pada " . date('d M Y', strtotime($request->tanggal)) . " pukul " . $request->jam_mulai . " di " . $request->lokasi
        );

        return redirect()->route('dosen.sidang.index')->with('success', 'Jadwal Sidang Berhasil Dibuat!');
    }

    // =================================================================
    // 4. DOSEN MENGAJUKAN PERUBAHAN KE KOORDINATOR
    // =================================================================
    public function ajukanPerubahan(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required|string|min:5',
            'surat_kerja' => 'required|mimes:pdf,jpg,png|max:2048',
        ]);

        $sidang = SidangJadwal::findOrFail($id);

        if ($request->hasFile('surat_kerja')) {
            $path = $request->file('surat_kerja')->store('surat_kerja_dosen', 'public');

            $pengajuan = PengajuanPerubahan::create([
                'sidang_jadwal_id' => $sidang->id,
                'mahasiswa_id' => $sidang->mahasiswa_id,
                'alasan' => $request->alasan,
                'file_pendukung' => $path,
                'status' => 'pending',
                'tipe_pengaju' => 'dosen',
            ]);

            // CATAT LOG RIWAYAT (Dosen ajukan reschedule ke Koordinator)
            $this->simpanLog(
                $sidang->mahasiswa_id,
                'Sidang',
                'Dosen Ajukan Reschedule',
                "Dosen penguji mengajukan perubahan jadwal sidang ke Koordinator dengan alasan: " . $request->alasan,
                $path
            );

            return back()->with('success', 'Permintaan perubahan berhasil dikirim ke Koordinator.');
        }

        return back()->with('error', 'Gagal mengunggah surat tugas.');
    }
}