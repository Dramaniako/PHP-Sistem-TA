<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Proposal;
use App\Models\SidangJadwal;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        // 1. DATA UNTUK KOORDINATOR
        if ($user->role == 'koordinator') {
            $data['total_mahasiswa'] = User::where('role', 'mahasiswa')->count();
            $data['proposal_pending'] = Proposal::where('dosen_pembimbing_id', null)->count();
            $data['sidang_minggu_ini'] = SidangJadwal::whereBetween('tanggal', [now(), now()->addDays(7)])->count();
            $data['recent_proposals'] = Proposal::with('mahasiswa')->latest()->take(5)->get();
        }

        // 2. DATA UNTUK MAHASISWA
        elseif ($user->role == 'mahasiswa') {
            $data['my_proposal'] = Proposal::where('mahasiswa_id', $user->id)->first();
            $data['my_sidang'] = SidangJadwal::where('mahasiswa_id', $user->id)->first();
        }

        // 3. DATA UNTUK DOSEN
        elseif ($user->role == 'dosen') {
            $data['total_bimbingan'] = Proposal::where('dosen_pembimbing_id', $user->id)->count();
            $data['jadwal_menguji'] = SidangJadwal::where('dosen_id', $user->id)
                                        ->where('tanggal', '>=', now())
                                        ->orderBy('tanggal', 'asc')
                                        ->take(5)
                                        ->get();
        }

        return view('dashboard', compact('data'));
    }
}