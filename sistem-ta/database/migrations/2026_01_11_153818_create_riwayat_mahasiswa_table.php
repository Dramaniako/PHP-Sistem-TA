<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('riwayat_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
            $table->string('tahap'); // Contoh: 'Proposal', 'Bimbingan', 'Sidang', 'Administrasi'
            $table->string('aksi');  // Contoh: 'Upload', 'Revisi', 'Lulus Sidang'
            $table->text('keterangan')->nullable(); // Detail tambahan
            $table->string('file_terkait')->nullable(); // Path file jika ada upload
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_mahasiswa');
    }
};
