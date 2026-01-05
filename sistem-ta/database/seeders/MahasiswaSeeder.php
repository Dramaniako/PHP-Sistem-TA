<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        DB::table('mahasiswa')->insert([
            [
                'id' => 1, 'user_id' => 1, 'nim' => '1908561034', 'nama' => 'Amba Penat', 
                'prodi' => 'Informatika', 'judul_ta' => 'Analisis Algoritma Mengeluh', 
                'created_at' => '2026-01-05 12:06:05', 'updated_at' => '2026-01-05 12:06:05'
            ],
            [
                'id' => 2, 'user_id' => 2, 'nim' => '2108561067', 'nama' => 'Made Adi Wirawan', 
                'prodi' => 'Informatika', 'judul_ta' => 'Sistem Pakar Deteksi Error', 
                'created_at' => '2026-01-05 12:06:05', 'updated_at' => '2026-01-05 12:06:05'
            ],
            [
                'id' => 3, 'user_id' => 3, 'nim' => '2008561029', 'nama' => 'Sie Imut', 
                'prodi' => 'Informatika', 'judul_ta' => 'UI/UX Design Comel', 
                'created_at' => '2026-01-05 12:06:06', 'updated_at' => '2026-01-05 12:06:06'
            ],
            [
                'id' => 4, 'user_id' => 4, 'nim' => '2208561001', 'nama' => 'Gede Gamers', 
                'prodi' => 'Informatika', 'judul_ta' => 'AI untuk NPC Game RPG', 
                'created_at' => '2026-01-05 12:06:07', 'updated_at' => '2026-01-05 12:06:07'
            ],
            [
                'id' => 5, 'user_id' => 5, 'nim' => '2108561055', 'nama' => 'Luh Data Mining', 
                'prodi' => 'Informatika', 'judul_ta' => 'Prediksi Cuaca dengan Python', 
                'created_at' => '2026-01-05 12:06:08', 'updated_at' => '2026-01-05 12:06:08'
            ],
        ]);
    }
}