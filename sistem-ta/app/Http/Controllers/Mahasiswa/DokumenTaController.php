<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\DokumenTa;
use App\Models\RiwayatMahasiswa; // Tambahkan import ini
use App\Traits\LogAktivitas;      // Tambahkan import ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;    // Tambahkan import ini
use Illuminate\Support\Facades\DB;      // Tambahkan import ini

class DokumenTaController extends Controller
{
    use LogAktivitas; // Menggunakan Trait untuk fungsi simpanLog()

    public function index()
    {
        $user = auth()->user();

        // Cek status di tabel sidang_jadwals
        $sidang = DB::table('sidang_jadwals')
            ->where('mahasiswa_id', $user->id)
            ->where('status', 'Selesai')
            ->first();

        // Jika tidak ditemukan jadwal yang sudah selesai, halaman terkunci
        if (!$sidang) {
            return view('mahasiswa.dokumen.locked');
        }

        $dokumens = DokumenTa::where('mahasiswa_id', $user->id)->get();

        $totalWajib = 9;
        $disetujui = $dokumens->where('status', 'Disetujui')->count();
        $progres = ($totalWajib > 0) ? ($disetujui / $totalWajib) * 100 : 0;

        return view('mahasiswa.dokumen.index', compact('dokumens', 'progres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:5120',
            'jenis_dokumen' => 'required'
        ]);

        $path = $request->file('file')->store('dokumen_ta', 'public');

        DokumenTa::updateOrCreate(
            ['mahasiswa_id' => auth()->id(), 'jenis_dokumen' => $request->jenis_dokumen],
            [
                'file_path' => $path,
                'komen_mahasiswa' => $request->komen_mahasiswa,
                'status' => 'Menunggu',
                'catatan_dosen' => null
            ]
        );

        // Mencatat log riwayat menggunakan Trait
        $this->simpanLog(
            auth()->id(),
            'Administrasi',
            'Unggah Dokumen',
            'Mahasiswa mengunggah dokumen: ' . $request->jenis_dokumen,
            $path
        );

        return redirect()->route('mahasiswa.dokumen.index')->with('success', 'File berhasil diserahkan.');
    }

    public function indexDosen()
    {
        $dosenId = auth()->id();
        $mahasiswas = \App\Models\User::where('role', 'mahasiswa')
            ->whereHas('penetapan', function ($q) use ($dosenId) {
                $q->where('dosen_pembimbing_id', $dosenId);
            })
            ->withCount([
                'dokumens as total_menunggu' => function ($q) {
                    $q->where('status', 'Menunggu')
                        ->whereNotNull('file_path')
                        ->where('file_path', '!=', '');
                }
            ])
            ->withCount('dokumens')
            ->withCount([
                'dokumens as total_disetujui' => function ($q) {
                    $q->where('status', 'Disetujui');
                }
            ])
            ->get();

        return view('dosen.validasi.index', compact('mahasiswas'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Fungsi ini tampaknya redundant dengan updateValidasi, 
        // namun saya perbaiki agar tetap sinkron dengan Trait
        $dokumen = DokumenTa::findOrFail($id);
        $dokumen->update([
            'status' => $request->status
        ]);

        $this->simpanLog(
            $dokumen->mahasiswa_id,
            'Administrasi',
            $request->status == 'Disetujui' ? 'Validasi Disetujui' : 'Validasi Ditolak',
            $request->status == 'Disetujui'
            ? 'Dokumen ' . $dokumen->jenis_dokumen . ' telah disetujui oleh Dosen.'
            : 'Dokumen ' . $dokumen->jenis_dokumen . ' ditolak. Catatan: ' . $request->catatan_dosen
        );

        return redirect()->back()->with('success', 'Status dokumen berhasil diperbarui!');
    }

    public function dashboardStatistik()
    {
        $totalMahasiswa = \App\Models\User::where('role', 'mahasiswa')->count();
        $menunggu = DokumenTa::where('status', 'Menunggu')->count();
        $lengkap = DokumenTa::where('status', 'Disetujui')
            ->select('mahasiswa_id')
            ->groupBy('mahasiswa_id')
            ->havingRaw('count(*) = 9') // Diubah ke 9 sesuai total wajib
            ->get()
            ->count();

        $sudahUpload = DokumenTa::distinct('mahasiswa_id')->count('mahasiswa_id');
        $belumUpload = $totalMahasiswa - $sudahUpload;

        return view('koordinator.statistik.index', compact(
            'totalMahasiswa',
            'menunggu',
            'lengkap',
            'belumUpload'
        ));
    }

    public function show($jenis)
    {
        $user = auth()->user();
        $dokumens = DokumenTa::where('mahasiswa_id', $user->id)->get();

        $totalWajib = 9;
        $disetujui = $dokumens->where('status', 'Disetujui')->count();
        $progres = ($totalWajib > 0) ? ($disetujui / $totalWajib) * 100 : 0;

        return view('mahasiswa.dokumen.show', compact('dokumens', 'jenis', 'progres'));
    }

    public function showMahasiswaDokumen($id)
    {
        $mahasiswa = \App\Models\User::findOrFail($id);
        $dokumens = DokumenTa::where('mahasiswa_id', $id)->get();

        $tahapan = [
            'Tahap Pelaksanaan & Bimbingan' => ['Formulir Bimbingan (Logbook)', 'Bukti Keikutsertaan Seminar Nasional', 'Bukti Publikasi Ilmiah'],
            'Tahap Ujian Tugas Akhir (Skripsi)' => ['Naskah Skripsi/Laporan TA', 'Persetujuan Dosen PA', 'Berkas Kelengkapan Ujian'],
            'Tahap Akhir (Pasca Ujian)' => ['Laporan TA Hasil Revisi', 'Lembar Pengesahan TA', 'Berkas untuk SKL']
        ];

        return view('dosen.validasi.mahasiswa_detail', compact('mahasiswa', 'dokumens', 'tahapan'));
    }

    public function showValidasiForm($id)
    {
        $dokumen = DokumenTa::with('mahasiswa')->findOrFail($id);
        return view('dosen.validasi.show', compact('dokumen'));
    }

    public function updateValidasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak',
            'catatan_dosen' => 'required_if:status,Ditolak'
        ]);

        $dokumen = DokumenTa::findOrFail($id);
        $dokumen->update([
            'status' => $request->status,
            'catatan_dosen' => $request->catatan_dosen
        ]);

        // Mencatat log riwayat saat dosen melakukan validasi
        $this->simpanLog(
            $dokumen->mahasiswa_id,
            'Administrasi',
            $request->status == 'Disetujui' ? 'Validasi Disetujui' : 'Validasi Ditolak',
            $request->status == 'Disetujui'
            ? 'Dokumen ' . $dokumen->jenis_dokumen . ' telah disetujui oleh Dosen.'
            : 'Dokumen ' . $dokumen->jenis_dokumen . ' ditolak. Catatan: ' . $request->catatan_dosen
        );

        return redirect()->route('dosen.validasi.mahasiswa', $dokumen->mahasiswa_id)
            ->with('success', 'Validasi berhasil disimpan.');
    }
}