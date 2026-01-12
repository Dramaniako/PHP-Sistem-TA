<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DosenRequestSeeder extends Seeder
{
    public function run()
    {
        DB::table('dosen_requests')->insert([
            ['id' => 1, 'proposal_id' => 5, 'dosen_id' => 7, 'role' => 'pembimbing', 'status' => 'rejected', 'pesan_penolakan' => 'Sibuk', 'created_at' => '2025-12-28 18:26:21', 'updated_at' => '2025-12-28 18:27:18'],
            ['id' => 2, 'proposal_id' => 5, 'dosen_id' => 7, 'role' => 'penguji_1', 'status' => 'accepted', 'pesan_penolakan' => null, 'created_at' => '2025-12-28 18:26:22', 'updated_at' => '2025-12-28 18:26:59'],
            ['id' => 3, 'proposal_id' => 5, 'dosen_id' => 7, 'role' => 'pembimbing', 'status' => 'accepted', 'pesan_penolakan' => null, 'created_at' => '2025-12-28 18:28:01', 'updated_at' => '2025-12-28 18:28:25'],
            ['id' => 4, 'proposal_id' => 6, 'dosen_id' => 7, 'role' => 'pembimbing', 'status' => 'accepted', 'pesan_penolakan' => null, 'created_at' => '2025-12-29 10:20:11', 'updated_at' => '2025-12-29 10:21:35'],
            ['id' => 5, 'proposal_id' => 6, 'dosen_id' => 7, 'role' => 'penguji_1', 'status' => 'accepted', 'pesan_penolakan' => null, 'created_at' => '2025-12-29 10:20:12', 'updated_at' => '2025-12-29 10:21:18'],
            ['id' => 6, 'proposal_id' => 3, 'dosen_id' => 7, 'role' => 'pembimbing', 'status' => 'accepted', 'pesan_penolakan' => null, 'created_at' => '2025-12-30 04:30:03', 'updated_at' => '2025-12-30 04:30:50'],
            ['id' => 7, 'proposal_id' => 3, 'dosen_id' => 8, 'role' => 'penguji_1', 'status' => 'pending', 'pesan_penolakan' => null, 'created_at' => '2025-12-30 04:30:05', 'updated_at' => '2025-12-30 04:30:05'],
        ]);
    }
}