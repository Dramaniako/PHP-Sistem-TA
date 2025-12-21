<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penetapan; // Pastikan baris ini ada untuk memanggil Model

class PenetapanController extends Controller
{
    public function index()
    {
        return view('penetapan');
    }

    public function store(Request $request)
    {
        // 1. Tangkap semua data dari form dan simpan ke database
        Penetapan::create($request->all());

        // 2. Setelah simpan, balikkan ke halaman tadi dengan pesan sukses
        return redirect()->back()->with('success', 'Data Berhasil Disimpan!');
    }
}