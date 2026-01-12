<?php

namespace App\Models;
use App\Models\User;

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
        'file_proposal',
        'file_khs', // WAJIB DITAMBAHKAN
        'file_ta',
        'status',
        'nilai',
        'komentar'
    ];

    // Relasi ke Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    // Relasi ke Dosen Pembimbing
    public function dosenPembimbing()
    {
        return $this->belongsTo(User::class, 'dosen_pembimbing_id');
    }

    // Relasi ke Dosen Penguji
    public function dosenPenguji()
    {
        return $this->belongsTo(User::class, 'dosen_penguji_id');
    }

    public function dosenRequests()
    {
        // Relasi satu proposal memiliki banyak request dosen
        return $this->hasMany(DosenRequest::class, 'proposal_id');
    }
}