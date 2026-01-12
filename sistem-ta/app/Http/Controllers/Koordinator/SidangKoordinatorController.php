<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\SidangJadwal;
use App\Models\User;
use App\Models\PengajuanPerubahan;
use App\Models\DosenRequest;
use App\Traits\LogAktivitas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SidangKoordinatorController extends Controller
{
    use LogAktivitas;

    public function index()
    {
        $jadwal_sidang = SidangJadwal::with(['mahasiswa', 'dosen'])->latest()->get();
        return view('koordinator.sidang.index', data: compact('jadwal_sidang'));
    }

    public function indexApproval()
    {
        $pengajuans = PengajuanPerubahan::with(['sidangJadwal.mahasiswa', 'sidangJadwal.dosen'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('koordinator.approval', compact('pengajuans'));
    }

    public function create()
    {
        $mahasiswas = User::whereHas('proposal', function ($q) {
            $q->where('status', 'disetujui');
        })->get();

        return view('koordinator.sidang.create', compact('mahasiswas'));
    }

    public function getProposalData($mahasiswa_id)
    {
        $proposal = Proposal::with(['dosenPembimbing', 'dosenRequests.dosen', 'mahasiswa'])
            ->where('mahasiswa_id', $mahasiswa_id)
            ->latest()
            ->first();

        if (!$proposal)
            return response()->json(['error' => 'Not Found'], 404);

        $pengujiNames = $proposal->dosenRequests
            ->filter(function ($req) {
                return str_contains($req->role, 'penguji') && $req->status === 'accepted';
            })
            ->map(fn($req) => $req->dosen->name)
            ->values()
            ->toArray();

        return response()->json([
            'proposal_id' => $proposal->id,
            'judul' => $proposal->judul,
            'pembimbing' => $proposal->dosenPembimbing->name ?? 'Belum ada',
            'penguji_list' => $pengujiNames,
            // Pastikan variabel ini dikirim untuk pengecekan di Blade
            'file_ta' => $proposal->file_ta ? true : false,
            'file_khs' => $proposal->file_khs ? true : false,
        ]);
    }

    public function edit($id)
    {
        $sidang = SidangJadwal::findOrFail($id);
        $dosens = User::where('role', 'dosen')->get();

        return view('koordinator.sidang.edit', compact('sidang', 'dosens'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_sidang' => 'required',
            'tanggal_sidang' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'ruangan' => 'required|string',
            'status' => 'required|in:dijadwalkan,selesai,dibatalkan,reschedule',
        ]);

        $sidang = \App\Models\SidangJadwal::findOrFail($id);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // Pastikan dosen_id terisi agar tidak error NOT NULL di database
            $dosen_id = (isset($request->dosen_penguji_id) && !empty($request->dosen_penguji_id))
                ? $request->dosen_penguji_id[0]
                : $sidang->dosen_id;

            // 1. Simpan perubahan ke database
            $sidang->update([
                'dosen_id' => $dosen_id,
                'jenis_sidang' => $request->jenis_sidang,
                'tanggal' => $request->tanggal_sidang,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'lokasi' => $request->ruangan,
                'status' => $request->status,
            ]);

            // 2. LOG AKTIVITAS (Log dipastikan aktif)
            $this->simpanLog(
                $sidang->mahasiswa_id,
                'Sidang',
                'Update Jadwal',
                "Memperbarui jadwal sidang menjadi " . strtoupper($request->status)
            );

            if ($request->filled('pengajuan_id')) {
                \App\Models\PengajuanPerubahan::where('id', $request->pengajuan_id)->update(['status' => 'disetujui']);
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('koordinator.sidang.index')->with('success', 'Perubahan berhasil disimpan!');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollback();
            return back()->with('error', 'Gagal Simpan: ' . $e->getMessage());
        }
    }

    private function kirimNotifikasiKeDosen($requestDosen)
    {
        // Tambahkan logika pengiriman WA/Email di sini jika diperlukan
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'proposal_id' => 'required|exists:proposals,id',
            'jenis_sidang' => 'required|in:Sidang Proposal,Sidang Akhir',
            'tanggal_sidang' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'ruangan' => 'required|string|max:255',
        ]);

        // 2. Ambil data proposal untuk mendapatkan mahasiswa_id
        $proposal = Proposal::findOrFail($request->proposal_id);

        SidangJadwal::create([
            'proposal_id' => $request->proposal_id,
            'mahasiswa_id' => $proposal->mahasiswa_id,
            'dosen_id' => $proposal->dosen_penguji_id, // PASTIKAN NILAI INI ADA
            'jenis_sidang' => $request->jenis_sidang,
            'tanggal' => $request->tanggal_sidang,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'lokasi' => $request->ruangan,
            'status' => 'dijadwalkan',
        ]);

        return redirect()->route('koordinator.sidang.index')->with('success', 'Jadwal berhasil dibuat');
    }

    public function downloadTA($id)
    {
        $proposal = Proposal::with('mahasiswa')->findOrFail($id);
        if (!$proposal->file_ta || !Storage::exists($proposal->file_ta))
            return back()->with('error', 'File TA tidak ada.');
        return Storage::download($proposal->file_ta, 'Draft_TA_' . $proposal->mahasiswa->nim . '.pdf');
    }

    public function downloadKHS($id)
    {
        $proposal = Proposal::with('mahasiswa')->findOrFail($id);
        if (!$proposal->file_khs || !Storage::exists($proposal->file_khs))
            return back()->with('error', 'File KHS tidak ada.');
        return Storage::download($proposal->file_khs, 'KHS_' . $proposal->mahasiswa->nim . '.pdf');
    }
}