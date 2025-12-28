<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Proposal;

class PenetapanController extends Controller
{
    // 1. INDEX: Tampilkan Proposal yang BELUM punya pembimbing
    public function index(Request $request)
    {
        $query = Proposal::with('mahasiswa')
                    ->whereNull('dosen_pembimbing_id'); // Filter: Cari yang belum ditetapkan

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('mahasiswa', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $proposals = $query->latest()->paginate(10);
        return view('koordinator.penetapan.index', compact('proposals'));
    }

    // 2. FORM PENETAPAN (Edit Mode)
    // Kita menerima ID Proposal, bukan ID Mahasiswa lagi
    public function edit($id)
    {
        $proposal = Proposal::with('mahasiswa')->findOrFail($id);
        
        // Ambil Dosen untuk Dropdown
        $dosens = User::whereIn('role', ['dosen', 'koordinator'])->get();

        return view('koordinator.penetapan.edit', compact('proposal', 'dosens'));
    }

    // 3. SIMPAN PENETAPAN (Update)
    public function update(Request $request, $id)
    {
        $request->validate([
            'dosen_pembimbing_id' => 'required|exists:users,id',
            'dosen_penguji_id'    => 'required|exists:users,id',
        ]);

        $proposal = Proposal::findOrFail($id);
        
        $proposal->update([
            'dosen_pembimbing_id' => $request->dosen_pembimbing_id,
            'dosen_penguji_id'    => $request->dosen_penguji_id,
            'status'              => 'disetujui' // Ubah status jadi disetujui/aktif
        ]);

        return redirect()->route('koordinator.penetapan.index')
                         ->with('success', 'Dosen berhasil ditetapkan untuk ' . $proposal->mahasiswa->name);
    }
}