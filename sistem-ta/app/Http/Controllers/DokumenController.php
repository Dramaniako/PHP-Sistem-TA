<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    // Halaman utama mahasiswa
    public function index()
    {
        $userId = 1;

        $mahasiswa = DB::table('mahasiswa')
            ->where('user_id', $userId)
            ->first();

        $status = $mahasiswa->status ?? '-';

        return view('mahasiswa-upload.index', compact('status'));
    }

    // Form upload
    public function upload()
    {
        return view('mahasiswa-upload.upload');
    }

    // Simpan dokumen
    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required',
            'nama' => 'required',
            'prodi' => 'required',
            'judul_ta' => 'required',
            'file_dokumen' => 'required|file|mimes:pdf|max:2048',
        ]);

        $filePath = $request->file('file_dokumen')
                            ->store('dokumen_ta', 'public');

        DB::table('mahasiswa')->insert([
            'nim' => $request->nim,
            'nama' => $request->nama,
            'prodi' => $request->prodi,
            'judul_ta' => $request->judul_ta,
            'file_dokumen' => $filePath,
            'status' => 'diajukan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/mahasiswa')->with('success', 'Dokumen berhasil diupload');
    }
}
