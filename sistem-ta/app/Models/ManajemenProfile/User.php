<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'nim',
        'password',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // RELASI
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class);
    }

    

}
