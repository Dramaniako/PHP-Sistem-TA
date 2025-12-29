<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TUController extends Controller
{
    public function index()
    {
        return view('tu.dashboard');
    }

    public function dashboard()
    {
        return view('tu.dashboard');
    }

    public function mahasiswa()
    {
        // DATA DUMMY (SEMENTARA, TANPA DATABASE)
        $mahasiswa = [
            [
                'nama' => 'I Made Contoh',
                'nim' => '2408561xxx',
                'judul' => 'Sistem Informasi Pada Fakultas'
            ],
            [
                'nama' => 'Budi Contoh',
                'nim' => '2408561xxx',
                'judul' => 'Analisis Jaringan di Lingkungan Kampus'
            ],
            [
                'nama' => 'Indah Contoh',
                'nim' => '2408561xxx',
                'judul' => 'Aplikasi Mobile untuk Monitoring'
            ],
            [
                'nama' => 'Andi Contoh',
                'nim' => '2408561xxx',
                'judul' => 'Rancang Bangun Aplikasi Web'
            ],
            [
                'nama' => 'Dian Contoh',
                'nim' => '2408561xxx',
                'judul' => 'Analisis Algoritma pada Sistem'
            ],
        ];

        return view('tu.mahasiswa', compact('mahasiswa'));
    }

    public function detail($nim)
    {
        $mahasiswa = [
            'nim' => $nim,
            'nama' => 'I Made Contoh',
            'progres' => '7/16'
        ];

        $dokumen = [
            ['nama' => 'Proposal TA', 'status' => 'lengkap', 'validator' => 'Pembimbing'],
            ['nama' => 'Lembar Pengesahan Proposal', 'status' => 'lengkap', 'validator' => 'Pembimbing'],
            ['nama' => 'Berita Acara Seminar Proposal', 'status' => 'lengkap', 'validator' => 'Penguji'],
            ['nama' => 'Nilai Seminar Proposal', 'status' => 'lengkap', 'validator' => 'Penguji'],
            ['nama' => 'Bab 1', 'status' => 'lengkap', 'validator' => 'Pembimbing'],
            ['nama' => 'Bab 2', 'status' => 'lengkap', 'validator' => 'Pembimbing'],
            ['nama' => 'Bab 3', 'status' => 'lengkap', 'validator' => 'Pembimbing'],
            ['nama' => 'Bab 4', 'status' => 'menunggu', 'validator' => 'Pembimbing'],
            ['nama' => 'Bab 5', 'status' => 'menunggu', 'validator' => 'Pembimbing'],
            ['nama' => 'Laporan TA Full', 'status' => 'menunggu', 'validator' => 'Pembimbing & Penguji'],
        ];

        return view('tu.detail', compact('mahasiswa', 'dokumen'));
    }
}
