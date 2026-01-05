<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Data Mahasiswa (ID 1-5 berdasarkan dump mahasiswa)
        $users = [
            ['id' => 1, 'name' => 'Amba Penat', 'email' => 'amba@univ.ac.id', 'role' => 'mahasiswa'],
            ['id' => 2, 'name' => 'Made Adi Wirawan', 'email' => 'made@univ.ac.id', 'role' => 'mahasiswa'],
            ['id' => 3, 'name' => 'Sie Imut', 'email' => 'sie@univ.ac.id', 'role' => 'mahasiswa'],
            ['id' => 4, 'name' => 'Gede Gamers', 'email' => 'gede@univ.ac.id', 'role' => 'mahasiswa'],
            ['id' => 5, 'name' => 'Luh Data Mining', 'email' => 'luh@univ.ac.id', 'role' => 'mahasiswa'],
            ['id' => 6, 'name' => 'Mahasiswa 6', 'email' => 'mhs6@univ.ac.id', 'role' => 'mahasiswa'],
            
            // Data Dosen (ID 7 & 8 berdasarkan dump dosen_requests & proposal)
            ['id' => 7, 'name' => 'Dosen Pembimbing Utama', 'email' => 'dosen1@univ.ac.id', 'role' => 'dosen'],
            ['id' => 8, 'name' => 'Dosen Penguji', 'email' => 'dosen2@univ.ac.id', 'role' => 'dosen'],
        ];

        foreach ($users as $user) {
            DB::table('users')->insertOrIgnore([
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'),
                'role' => $user['role'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}