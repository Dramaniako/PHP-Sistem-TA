<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DosenRequest;
use App\Models\Proposal;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    // Halaman List Request Masuk
    public function index()
    {
        $requests = DosenRequest::with(['proposal.mahasiswa'])
            ->where('dosen_id', Auth::id())
            ->where('status', 'pending') // Hanya tampilkan yang belum dijawab
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dosen.request.index', compact('requests'));
    }

    // Proses Terima / Tolak
    public function respond(Request $request, $id)
    {
        $dosenRequest = DosenRequest::findOrFail($id);

        if ($dosenRequest->dosen_id != Auth::id()) {
            abort(403);
        }

        if ($request->action == 'terima') {
            $dosenRequest->update(['status' => 'accepted']);

            $proposal = Proposal::findOrFail($dosenRequest->proposal_id);

            // LOGIKA PENERIMAAN BERDASARKAN ROLE
            if ($dosenRequest->role == 'pembimbing') {
                $proposal->update(['dosen_pembimbing_id' => Auth::id()]);
            }
            // Gunakan str_contains atau regex agar mencakup penguji_1, penguji_2, dst.
            elseif (str_contains($dosenRequest->role, 'penguji')) {
                // Update dosen_penguji_id pada tabel proposal (sebagai penguji utama/perwakilan)
                // Atau jika Anda punya kolom spesifik seperti dosen_penguji_2, silakan sesuaikan
                $proposal->update(['dosen_penguji_id' => Auth::id()]);
            }

            return back()->with('success', 'Anda berhasil menerima permintaan ini.');
        }

        if ($request->action == 'tolak') {
            $dosenRequest->update([
                'status' => 'rejected',
                'pesan_penolakan' => $request->alasan ?? 'Tidak bersedia saat ini.'
            ]);

            return back()->with('success', 'Permintaan ditolak.');
        }
    }
}