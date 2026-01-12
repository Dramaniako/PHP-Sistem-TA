<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RiwayatMahasiswa;
use Illuminate\Http\Request;

class AdminMonitoringController extends Controller
{
    // Menampilkan daftar mahasiswa khusus untuk menu Log Riwayat
    public function indexRiwayat(Request $request)
    {
        $query = User::where('role', 'mahasiswa');

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('nim', 'like', '%' . $request->search . '%');
        }

        $mahasiswas = $query->get();

        // MEMANGGIL VIEW KHUSUS: index_riwayat
        return view('koordinator.monitoring.index_riwayat', compact('mahasiswas'));
    }

    // Menampilkan timeline.blade.php untuk satu mahasiswa
    public function showTimeline($id)
    {
        $mahasiswa = User::findOrFail($id);
        $riwayats = RiwayatMahasiswa::where('mahasiswa_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('koordinator.monitoring.timeline', compact('mahasiswa', 'riwayats'));
    }
}