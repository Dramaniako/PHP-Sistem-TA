<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DokumenTa;

class AdministrasiKoorController extends Controller
{
    // Halaman Monitoring Semua Mahasiswa dengan FITUR SEARCHING
    public function monitoring(Request $request)
    {
        $search = $request->input('search');

        $mahasiswas = User::where('role', 'mahasiswa')
            ->when($search, function ($query, $search) {
                // Mencari berdasarkan nama atau NIM mahasiswa
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('nim', 'like', "%{$search}%"); // Asumsi ada kolom NIM
                });
            })
            ->with('dokumens')
            ->get();

        return view('koordinator.administrasi.monitoring', compact('mahasiswas'));
    }

    // Halaman Statistik Administrasi
    public function statistik()
    {
        $totalMhs = User::where('role', 'mahasiswa')->count();

        $mhsLengkap = User::where('role', 'mahasiswa')
            ->whereHas('dokumens', function($query) {
                $query->where('status', 'Disetujui');
            }, '=', 9)
            ->count();

        return view('koordinator.administrasi.statistik', compact('totalMhs', 'mhsLengkap'));
    }

    // Halaman Statistik Detail dengan FITUR SEARCHING
    public function statistikDetail(Request $request)
    {
        $search = $request->input('search');

        $mahasiswas = User::where('role', 'mahasiswa')
            ->whereHas('penetapan')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('nim', 'like', "%{$search}%");
                });
            })
            ->with(['dokumens', 'penetapan'])
            ->get();

        return view('koordinator.administrasi.statistik_detail', compact('mahasiswas'));
    }

    public function kondisiDokumen($id)
    {
        $mhs = User::with(['dokumens', 'penetapan'])->findOrFail($id);
        return view('koordinator.administrasi.kondisi_dokumen', compact('mhs'));
    }

    public function cetak($id)
    {
        $mhs = User::with('dokumens')->findOrFail($id);
        return view('koordinator.administrasi.cetak_berkas', compact('mhs'));
    }
}
