<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenetapanDosen extends Model
{
    use HasFactory;

    // Ganti nama tabel menjadi proposals
    protected $table = 'proposals'; 

    protected $fillable = [
        'mahasiswa_id',
        'dosen_pembimbing_id',
        'dosen_penguji_id',
        'judul',
        'status',
        // tambahkan kolom lain jika perlu diinput lewat model ini
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}