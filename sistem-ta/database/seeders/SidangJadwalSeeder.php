<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SidangJadwalSeeder extends Seeder
{
    public function run()
    {
        DB::table('sidang_jadwals')->insert([
            [
                'id' => 1,
                'mahasiswa_id' => 5,
                'dosen_id' => 7,
                'judul_ta' => 'testing',
                'jenis_sidang' => 'sidang_akhir',
                'tanggal' => '2025-12-30',
                'jam_mulai' => '12:00:00',
                'jam_selesai' => '14:00:00',
                'lokasi' => 'Ruang Sidang 1',
                'status' => 'dijadwalkan',
                'created_at' => '2025-12-28 18:30:14',
                'updated_at' => '2025-12-28 18:30:14'
            ],
        ]);
    }
}