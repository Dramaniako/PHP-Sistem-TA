<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DokumenTaSeeder extends Seeder
{
    public function run()
    {
        DB::table('dokumen_ta')->insert([
            ['id' => 1, 'mahasiswa_id' => 1, 'nama_file' => 'Laporan_Amba.pdf', 'path_file' => 'uploads/laporan_amba.pdf', 'status' => 'menunggu', 'created_at' => '2026-01-05 12:06:05', 'updated_at' => '2026-01-05 12:06:05'],
            ['id' => 2, 'mahasiswa_id' => 2, 'nama_file' => 'Laporan_Made.pdf', 'path_file' => 'uploads/laporan_made.pdf', 'status' => 'menunggu', 'created_at' => '2026-01-05 12:06:06', 'updated_at' => '2026-01-05 12:06:06'],
            ['id' => 3, 'mahasiswa_id' => 3, 'nama_file' => 'Laporan_Sie.pdf', 'path_file' => 'uploads/laporan_sie.pdf', 'status' => 'menunggu', 'created_at' => '2026-01-05 12:06:06', 'updated_at' => '2026-01-05 12:06:06'],
            ['id' => 4, 'mahasiswa_id' => 4, 'nama_file' => 'Laporan_Gede.pdf', 'path_file' => 'uploads/laporan_gede.pdf', 'status' => 'menunggu', 'created_at' => '2026-01-05 12:06:07', 'updated_at' => '2026-01-05 12:06:07'],
            ['id' => 5, 'mahasiswa_id' => 5, 'nama_file' => 'Laporan_Luh.pdf', 'path_file' => 'uploads/laporan_luh.pdf', 'status' => 'menunggu', 'created_at' => '2026-01-05 12:06:08', 'updated_at' => '2026-01-05 12:06:08'],
        ]);

        DB::table('validasi_dokumen')->insert([
            ['id' => 1, 'dokumen_id' => 1, 'dosen_id' => 7, 'keputusan' => 'tolak', 'catatan' => 'Revisi di latar belakang', 'created_at' => '2026-01-05 12:06:05', 'updated_at' => '2026-01-05 12:06:05'],
            ['id' => 2, 'dokumen_id' => 2, 'dosen_id' => 7, 'keputusan' => 'setuju', 'catatan' => 'Acc, lanjut bab selanjutnya', 'created_at' => '2026-01-05 12:06:06', 'updated_at' => '2026-01-05 12:06:06'],
            ['id' => 3, 'dokumen_id' => 3, 'dosen_id' => 7, 'keputusan' => 'setuju', 'catatan' => 'Bagus sekali', 'created_at' => '2026-01-05 12:06:07', 'updated_at' => '2026-01-05 12:06:07'],
            ['id' => 4, 'dokumen_id' => 4, 'dosen_id' => 7, 'keputusan' => 'setuju', 'catatan' => 'Game loop logic sudah oke', 'created_at' => '2026-01-05 12:06:07', 'updated_at' => '2026-01-05 12:06:07'],
            ['id' => 5, 'dokumen_id' => 5, 'dosen_id' => 7, 'keputusan' => 'tolak', 'catatan' => 'Akurasi model masih rendah', 'created_at' => '2026-01-05 12:06:08', 'updated_at' => '2026-01-05 12:06:08'],
        ]);
    }
}