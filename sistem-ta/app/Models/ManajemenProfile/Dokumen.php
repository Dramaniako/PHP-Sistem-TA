<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dokumen extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'path',
    ];

    // RELASI
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
