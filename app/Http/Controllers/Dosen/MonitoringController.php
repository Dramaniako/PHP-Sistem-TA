<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        // REQUEST NO. 3: Filter hanya proposal milik dosen yang sedang login
        $query = Proposal::with('mahasiswa')
                    ->where('dosen_pembimbing_id', Auth::id());

        // Fitur Search Bar (Bonus)
        if ($request->has('search')) {
            $query->whereHas('mahasiswa', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $proposals = $query->get();

        return view('dosen.monitoring.index', compact('proposals'));
    }
}