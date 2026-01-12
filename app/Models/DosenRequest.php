<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi ke Proposal
    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    // Relasi ke Dosen (User)
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }
}