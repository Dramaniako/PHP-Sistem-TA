<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'riwayat_mahasiswa';

    protected $fillable = [
        'mahasiswa_id',
        'tahap',
        'aksi',
        'keterangan',
        'file_path'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}