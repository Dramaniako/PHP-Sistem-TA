<?php

namespace App\Traits;
use App\Models\RiwayatMahasiswa;

trait LogAktivitas
{
    public function simpanLog($mahasiswaId, $tahap, $aksi, $keterangan, $file = null)
    {
        RiwayatMahasiswa::create([
            'mahasiswa_id' => $mahasiswaId,
            'tahap' => $tahap,
            'aksi' => $aksi,
            'keterangan' => $keterangan,
            'file_path' => $file
        ]);
    }
}