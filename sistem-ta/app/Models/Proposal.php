<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'dosen_pembimbing_id',
        'dosen_penguji_id',
        'judul',
        'deskripsi',
        'status',
    ];

    // Relasi ke Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    // Relasi ke Dosen Pembimbing
    public function pembimbing()
    {
        return $this->belongsTo(User::class, 'dosen_pembimbing_id');
    }

    // Relasi ke Dosen Penguji
    public function penguji()
    {
        return $this->belongsTo(User::class, 'dosen_penguji_id');
    }
}