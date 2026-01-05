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
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel users (karena mahasiswa adalah user yang login)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->string('nim', 20);
            $table->string('nama', 100);
            $table->string('prodi', 100);
            $table->string('judul_ta')->nullable();     // Boleh kosong (nullable)
            $table->string('file_dokumen')->nullable(); // Boleh kosong (nullable)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};