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
        Schema::create('bimbingan_slots', function (Blueprint $table) {
        $table->id();
        // Data Ketersediaan Dosen 
        $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade'); // Asumsi tabel users sudah ada
        $table->date('tanggal');
        $table->time('jam_mulai');
        $table->time('jam_selesai');
        $table->string('lokasi'); // Ruangan atau Online 
        
        // Data Pengajuan Mahasiswa 
        $table->foreignId('mahasiswa_id')->nullable()->constrained('users')->onDelete('cascade');
        $table->string('topik_bimbingan')->nullable(); // Catatan/Topik
        $table->string('file_path')->nullable(); // Upload file Bab TA
        
        // Status: 'tersedia', 'menunggu_konfirmasi', 'disetujui', 'ditolak'
        $table->enum('status', ['tersedia', 'menunggu_konfirmasi', 'disetujui', 'ditolak'])->default('tersedia');
        
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bimbingan_slots');
    }
};
