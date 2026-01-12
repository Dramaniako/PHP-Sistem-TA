<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Menghapus tabel bimbingan_slots jika ada
        Schema::dropIfExists('bimbingan_slots');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // PENTING: Jika Anda ingin bisa melakukan rollback (php artisan migrate:rollback),
        // Anda harus menuliskan ulang struktur tabel bimbingan_slots di sini.
        // Jika tidak butuh rollback, biarkan kosong atau komentar saja.
        
        /*
        Schema::create('bimbingan_slots', function (Blueprint $table) {
            $table->id();
            // ... definisi kolom lama Anda ...
            $table->timestamps();
        });
        */
    }
};