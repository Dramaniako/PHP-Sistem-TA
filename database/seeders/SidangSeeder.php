<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SidangJadwal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class SidangSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat User: Dosen Penguji
        $dosen = User::create([
            'name' => 'Dr. Budi Santoso',
            'email' => 'dosen@test.com',
            'password' => Hash::make('password'),
            // 'role' => 'dosen' // Sesuaikan jika ada kolom role
        ]);

        // 2. Buat User: Mahasiswa Utama (Ini akun yang Anda pakai Login nanti)
        $me = User::create([
            'name' => 'Ahmad Mahasiswa (Saya)',
            'email' => 'mhs@test.com', // Login pakai email ini
            'password' => Hash::make('password'),
            // 'role' => 'mahasiswa'
        ]);

        // 3. Buat User: Mahasiswa Lain (Dummy)
        $otherMhs = User::factory()->count(3)->create(); // Butuh UserFactory default Laravel

        // -------------------------------------------------------

        // SKENARIO A: Jadwal Sidang Milik "SAYA" (Akan berwarna Hijau & Bisa Reschedule)
        SidangJadwal::create([
            'mahasiswa_id' => $me->id,
            'dosen_id' => $dosen->id,
            'judul_ta' => 'Sistem Pakar Diagnosa Kerusakan Laptop',
            'jenis_sidang' => 'sidang_akhir',
            'tanggal' => Carbon::tomorrow()->format('Y-m-d'), // Besok
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '11:00:00',
            'lokasi' => 'Ruang 101',
            'status' => 'dijadwalkan'
        ]);

        // SKENARIO B: Jadwal Sidang Orang Lain (Akan berwarna Abu-abu & Read Only)
        foreach($otherMhs as $index => $mhs) {
            SidangJadwal::create([
                'mahasiswa_id' => $mhs->id,
                'dosen_id' => $dosen->id,
                'judul_ta' => 'Analisis Algoritma No. ' . ($index + 1),
                'jenis_sidang' => 'proposal',
                'tanggal' => Carbon::tomorrow()->addDays(1)->format('Y-m-d'), // Lusa
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '15:00:00',
                'lokasi' => 'Lab Komputer A',
                'status' => 'dijadwalkan'
            ]);
        }
    }
}