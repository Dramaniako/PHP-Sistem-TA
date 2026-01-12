<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengajuan_perubahans', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke jadwal sidang yang mau diubah
            $table->foreignId('sidang_jadwal_id')
                  ->constrained('sidang_jadwals')
                  ->onDelete('cascade');
            
            // Siapa yang request (untuk audit trail)
            $table->foreignId('mahasiswa_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            
            // Data Perubahan
            $table->text('alasan'); // Wajib diisi alasan kenapa minta ganti
            $table->date('tanggal_saran');
            $table->time('jam_saran');
            
            // Status Pengajuan
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            
            // Opsional: Catatan dari prodi/admin kenapa ditolak
            $table->text('catatan_admin')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengajuan_perubahans');
    }
};