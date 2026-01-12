<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanPerubahan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_perubahans';

    protected $fillable = [
        'sidang_jadwal_id',
        'mahasiswa_id',
        'alasan',
        'tanggal_saran',
        'jam_saran',
        'status',
        'catatan_admin'
    ];

    // Relasi balik ke Jadwal Sidang
    public function sidangJadwal()
    {
        return $this->belongsTo(SidangJadwal::class, 'sidang_jadwal_id');
    }

    // Relasi ke Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}