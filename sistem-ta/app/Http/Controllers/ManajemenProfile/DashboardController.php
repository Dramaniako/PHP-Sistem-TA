<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Asumsi setiap user punya data mahasiswa (relasi di models/user.php)
        $mahasiswa = $user->mahasiswa; 

        // sementara syarat kita bikin dummy dulu sampai datanya fix
        $syarat = [
            ['nama' => 'Bukti Bebas Administrasi', 'status' => 'belum'],
            ['nama' => 'Kartu Bimbingan', 'status' => 'terpenuhi'],
            ['nama' => 'Laporan Hasil Plagiasi', 'status' => 'proses'],
            ['nama' => 'Draft Skripsi', 'status' => 'terpenuhi'],
        ];

        return view('dashboard.index', compact('user','mahasiswa','syarat'));
    }
}
