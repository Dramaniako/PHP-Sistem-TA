<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('proposals', function (Blueprint $table) {
        $table->id();
        // Relasi ke User
        $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('dosen_pembimbing_id')->nullable()->constrained('users')->onDelete('set null');
        $table->foreignId('dosen_penguji_id')->nullable()->constrained('users')->onDelete('set null'); // INI UNTUK REQUEST NO. 2
        
        $table->string('judul');
        $table->text('deskripsi')->nullable();
        $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
