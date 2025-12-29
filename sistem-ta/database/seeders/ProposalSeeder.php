<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Proposal;
use Illuminate\Support\Facades\Hash;

class ProposalSeeder extends Seeder
{
    public function run()
    {
        // 1. Mahasiswa AI (Deep Learning)
        $mhs1 = User::create([
            'name' => 'Amba Penat',
            'email' => 'amba@student.unud.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'nim' => '1908561034'
        ]);
        
        Proposal::create([
            'mahasiswa_id' => $mhs1->id,
            'judul' => 'Analisis Sentimen Masyarakat Terhadap Kebijakan Energi Terbarukan Menggunakan Metode Deep Learning LSTM',
            'deskripsi' => 'Penelitian ini bertujuan untuk mengklasifikasikan opini masyarakat di Twitter mengenai energi terbarukan menjadi sentimen positif, negatif, dan netral menggunakan Long Short-Term Memory.',
            'status' => 'pending', // Belum dapat dosen
            'file_path' => 'proposals/dummy.pdf'
        ]);

        // 2. Mahasiswa BioInformatika
        $mhs2 = User::create([
            'name' => 'Made Adi Wirawan',
            'email' => 'made.adi@student.unud.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'nim' => '2108561067'
        ]);

        Proposal::create([
            'mahasiswa_id' => $mhs2->id,
            'judul' => 'Implementasi Machine Learning untuk Klasifikasi Data Genomik pada Tanaman Padi Bali',
            'deskripsi' => 'Menggunakan algoritma Support Vector Machine (SVM) untuk membedakan varietas padi lokal Bali berdasarkan sekuens DNA agar menjaga kemurnian varietas.',
            'status' => 'pending',
            'file_path' => 'proposals/dummy.pdf'
        ]);

        // 3. Mahasiswa IoT
        $mhs3 = User::create([
            'name' => 'Sie Imut',
            'email' => 'sie.imut@student.unud.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'nim' => '2008561029'
        ]);

        Proposal::create([
            'mahasiswa_id' => $mhs3->id,
            'judul' => 'Sistem Monitoring Kualitas Air Tambak Udang Berbasis IoT dengan Notifikasi Real-time Telegram',
            'deskripsi' => 'Rancang bangun alat ukur pH, suhu, dan kekeruhan air menggunakan ESP32 yang terintegrasi dengan bot Telegram untuk peringatan dini bagi petani tambak.',
            'status' => 'pending',
            'file_path' => 'proposals/dummy.pdf'
        ]);

        // 4. Mahasiswa Game Dev
        $mhs4 = User::create([
            'name' => 'Gede Gamers',
            'email' => 'gede@student.unud.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'nim' => '2208561001'
        ]);

        Proposal::create([
            'mahasiswa_id' => $mhs4->id,
            'judul' => 'Pengembangan Game Edukasi Sejarah Kerajaan Bali Menggunakan Unity Engine Berbasis Android',
            'deskripsi' => 'Game edukasi bergenre RPG untuk memperkenalkan sejarah kerajaan-kerajaan di Bali kepada siswa SD dengan metode gamifikasi yang interaktif.',
            'status' => 'disetujui', // Ceritanya sudah dapat dosen
            'dosen_pembimbing_id' => 2, // Asumsi ID 2 adalah Dosen (Sesuaikan dengan DB Anda)
            'dosen_penguji_id' => 3,
            'file_path' => 'proposals/dummy.pdf'
        ]);

        // 5. Mahasiswa SPK (Sistem Pendukung Keputusan)
        $mhs5 = User::create([
            'name' => 'Luh Data Mining',
            'email' => 'luh@student.unud.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'nim' => '2108561055'
        ]);

        Proposal::create([
            'mahasiswa_id' => $mhs5->id,
            'judul' => 'Sistem Pendukung Keputusan Pemilihan Lokasi Strategis UMKM Kuliner Menggunakan Metode AHP dan TOPSIS',
            'deskripsi' => 'Mombantu pelaku UMKM menentukan lokasi usaha terbaik berdasarkan kriteria harga sewa, keramaian, dan aksesibilitas menggunakan kombinasi dua metode MCDM.',
            'status' => 'pending',
            'file_path' => 'proposals/dummy.pdf'
        ]);
    }
}