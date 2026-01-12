<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProposalSeeder extends Seeder
{
    public function run()
    {
        DB::table('proposals')->insert([
            [
                'id' => 1, 
                'mahasiswa_id' => 1, 
                'dosen_pembimbing_id' => null, 
                'dosen_penguji_id' => null,
                'judul' => 'Analisis Sentimen Masyarakat Terhadap Kebijakan Energi Terbarukan Menggunakan Metode Deep Learning LSTM',
                'file_path' => 'proposals/dummy.pdf', 
                'deskripsi' => 'Penelitian ini bertujuan untuk mengklasifikasikan opini masyarakat...',
                // Tambahkan kolom null agar konsisten dengan data ke-6
                'file_proposal' => null,
                'file_khs' => null,
                'file_ta' => null,
                'status' => 'pending', 
                'nilai' => null,
                'komentar' => null,
                'created_at' => '2025-12-28 17:57:46', 
                'updated_at' => '2025-12-28 17:57:46'
            ],
            [
                'id' => 2, 
                'mahasiswa_id' => 2, 
                'dosen_pembimbing_id' => null, 
                'dosen_penguji_id' => null,
                'judul' => 'Implementasi Machine Learning untuk Klasifikasi Data Genomik pada Tanaman Padi Bali',
                'file_path' => 'proposals/dummy.pdf',
                'deskripsi' => 'Menggunakan algoritma Support Vector Machine (SVM)...',
                'file_proposal' => null,
                'file_khs' => null,
                'file_ta' => null,
                'status' => 'pending', 
                'nilai' => null,
                'komentar' => null,
                'created_at' => '2025-12-28 17:57:48', 
                'updated_at' => '2025-12-28 17:57:48'
            ],
            [
                'id' => 3, 
                'mahasiswa_id' => 3, 
                'dosen_pembimbing_id' => 7, 
                'dosen_penguji_id' => null,
                'judul' => 'Sistem Monitoring Kualitas Air Tambak Udang Berbasis IoT dengan Notifikasi Real-time Telegram',
                'file_path' => 'proposals/dummy.pdf',
                'deskripsi' => 'Rancang bangun alat ukur pH, suhu, dan kekeruhan air...',
                'file_proposal' => null,
                'file_khs' => null,
                'file_ta' => null,
                'status' => 'disetujui', 
                'nilai' => null,
                'komentar' => null,
                'created_at' => '2025-12-28 17:57:49', 
                'updated_at' => '2025-12-30 04:30:51'
            ],
            [
                'id' => 4, 
                'mahasiswa_id' => 4, 
                'dosen_pembimbing_id' => 2, 
                'dosen_penguji_id' => 3,
                'judul' => 'Pengembangan Game Edukasi Sejarah Kerajaan Bali Menggunakan Unity Engine Berbasis Android',
                'file_path' => 'proposals/dummy.pdf',
                'deskripsi' => 'Game edukasi bergenre RPG...',
                'file_proposal' => null,
                'file_khs' => null,
                'file_ta' => null,
                'status' => 'disetujui', 
                'nilai' => null,
                'komentar' => null,
                'created_at' => '2025-12-28 17:57:50', 
                'updated_at' => '2025-12-28 17:57:50'
            ],
            [
                'id' => 5, 
                'mahasiswa_id' => 5, 
                'dosen_pembimbing_id' => 7, 
                'dosen_penguji_id' => 7,
                'judul' => 'Sistem Pendukung Keputusan Pemilihan Lokasi Strategis UMKM Kuliner Menggunakan Metode AHP dan TOPSIS',
                'file_path' => 'proposals/dummy.pdf',
                'deskripsi' => 'Mombantu pelaku UMKM menentukan lokasi usaha terbaik...',
                'file_proposal' => null,
                'file_khs' => null,
                'file_ta' => null,
                'status' => 'disetujui', 
                'nilai' => null,
                'komentar' => null,
                'created_at' => '2025-12-28 17:57:51', 
                'updated_at' => '2025-12-28 18:28:26'
            ],
            [
                'id' => 6, 
                'mahasiswa_id' => 6, 
                'dosen_pembimbing_id' => 7, 
                'dosen_penguji_id' => 7,
                'judul' => 'Implementasi Nasi Sebagai Pangan Kuli',
                'file_path' => null,
                'deskripsi' => 'menelusuri efek dari karbohidrat berlebih dalam diet manusia',
                // Data khusus row 6 yang sebelumnya bikin error
                'file_proposal' => 'proposals/5eXLPNzUmdQffklqclXBlYW00DMKybfSNUPf42TK.pdf',
                'file_khs' => null,
                'file_ta' => null,
                'status' => 'disetujui', 
                'nilai' => null,
                'komentar' => null,
                'created_at' => '2025-12-29 09:11:42', 
                'updated_at' => '2025-12-29 10:21:37'
            ],
        ]);
    }
}