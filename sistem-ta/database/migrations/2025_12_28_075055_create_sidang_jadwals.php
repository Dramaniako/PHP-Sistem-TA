<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sidang_jadwals', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke User (Mahasiswa & Dosen Penguji)
            // Asumsi tabel user digabung, dibedakan role-nya
            $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade'); 
            
            // Detail Sidang
            $table->string('judul_ta')->nullable(); // Judul Tugas Akhir
            $table->enum('jenis_sidang', ['proposal', 'seminar_hasil', 'sidang_akhir']);
            
            // Waktu & Tempat
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('lokasi'); // Misal: "Ruang 304" atau Link Zoom
            
            // Status Sidang
            $table->enum('status', ['dijadwalkan', 'selesai', 'dibatalkan', 'reschedule_pending'])
                  ->default('dijadwalkan');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sidang_jadwals');
    }
};