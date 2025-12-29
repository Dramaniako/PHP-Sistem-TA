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
        
        // Validasi keamanan: Pastikan yang jawab adalah dosen yang dituju
        if($dosenRequest->dosen_id != Auth::id()) {
            abort(403);
        }

        if ($request->action == 'terima') {
            // 1. Update status Request jadi Accepted
            $dosenRequest->update(['status' => 'accepted']);

            // 2. UPDATE TABEL UTAMA PROPOSALS (Resmi ditetapkan)
            $proposal = Proposal::findOrFail($dosenRequest->proposal_id);
            
            if ($dosenRequest->role == 'pembimbing') {
                $proposal->update(['dosen_pembimbing_id' => Auth::id()]);
            } elseif ($dosenRequest->role == 'penguji_1') {
                $proposal->update(['dosen_penguji_id' => Auth::id()]);
            }

            return back()->with('success', 'Anda berhasil menerima permintaan ini.');
        } 
        
        if ($request->action == 'tolak') {
            $dosenRequest->update([
                'status' => 'rejected',
                'pesan_penolakan' => $request->alasan ?? 'Tidak bersedia saat ini.'
            ]);
            
            // Proposal ID tidak diupdate (tetap null), Koordinator akan melihat status rejected
            return back()->with('success', 'Permintaan ditolak.');
        }
    }
}