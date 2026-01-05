<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ValidasiController extends Controller
{
    public function index()
    {
        $mahasiswa = DB::table('mahasiswa')->get();
        return view('dosen-validasi.cari-mahasiswa', compact('mahasiswa'));
    }

    public function detail(Request $request)
    {
        $mhs = DB::table('mahasiswa')
                ->where('nim', $request->nim)
                ->first();

        return view('dosen-validasi.detail', compact('mhs'));
    }

    public function setujui(Request $request)
    {
        DB::table('mahasiswa')
            ->where('nim', $request->nim)
            ->update([
                'status' => 'disetujui',
                'updated_at' => now()
            ]);

        return redirect('/dosen')->with('success', 'Dokumen disetujui');
    }

    public function tolak(Request $request)
    {
        DB::table('mahasiswa')
            ->where('nim', $request->nim)
            ->update([
                'status' => 'ditolak',
                'updated_at' => now()
            ]);

        return redirect('/dosen')->with('success', 'Dokumen ditolak');
    }
}
