<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Dokumen TA
        Schema::create('dokumen_ta', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel mahasiswa (sesuai dump data id 1-5)
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            
            $table->string('nama_file');
            $table->string('path_file');
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->timestamps();
        });

        // Tabel Validasi Dokumen (Pasangan tabel dokumen_ta)
        Schema::create('validasi_dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dokumen_id')->constrained('dokumen_ta')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
            $table->enum('keputusan', ['setuju', 'tolak']);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('validasi_dokumen');
        Schema::dropIfExists('dokumen_ta');
    }
};