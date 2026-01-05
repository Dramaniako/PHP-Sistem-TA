<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BimbinganSlot extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // TAMBAHAN WAJIB: Agar $jadwal->waktu_bimbingan bisa diformat tanggalnya
    protected $casts = [
        'waktu_bimbingan' => 'datetime',
        'waktu_reschedule' => 'datetime',
    ];

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}