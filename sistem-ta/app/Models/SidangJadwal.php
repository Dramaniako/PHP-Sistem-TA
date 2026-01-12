<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SidangJadwal extends Model
{
    use HasFactory;

    protected $table = 'sidang_jadwals';

    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'judul_ta',
        'jenis_sidang',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'lokasi',
        'status'
    ];

    // Relasi ke Mahasiswa (User)
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    // Relasi ke Dosen Penguji (User)
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    // Relasi: Satu sidang bisa memiliki riwayat pengajuan perubahan
    public function pengajuanPerubahan()
    {
        return $this->hasMany(PengajuanPerubahan::class, 'sidang_jadwal_id');
    }

    // Opsional: Jika Anda punya tabel Ruangan terpisah
    public function ruangan()
    {
        // return $this->belongsTo(Ruangan::class);
        // Untuk sementara kita pakai kolom string 'lokasi' dulu sesuai migration
    }

    public function proposal()
    {
        // Asumsi: di tabel sidang_jadwals ada kolom 'proposal_id'
        // Jika tidak ada, gunakan relasi melalui mahasiswa_id
        return $this->belongsTo(Proposal::class, 'mahasiswa_id', 'mahasiswa_id');
    }
}