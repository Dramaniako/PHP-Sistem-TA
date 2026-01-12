<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Import model User

class DokumenTa extends Model
{
    use HasFactory;

    protected $table = 'dokumen_ta';

    protected $fillable = [
        'mahasiswa_id',
        'jenis_dokumen',
        'file_path',
        'komen_mahasiswa',
        'catatan_dosen',
        'status'
    ];

    /**
     * Mendefinisikan hubungan bahwa Dokumen ini dimiliki oleh seorang Mahasiswa (User)
     */
    public function mahasiswa()
    {
        // belongsTo karena kolom 'mahasiswa_id' ada di tabel 'dokumen_tas'
        // 'mahasiswa_id' adalah foreign key, 'id' adalah primary key di tabel users
        return $this->belongsTo(User::class, 'mahasiswa_id', 'id');
    }
}