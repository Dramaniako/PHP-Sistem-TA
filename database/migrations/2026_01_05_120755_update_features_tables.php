<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom Tugas Akhir di tabel proposals
        Schema::table('proposals', function (Blueprint $table) {
            $table->string('file_ta')->nullable()->after('file_khs');
        });

        // 2. Buat tabel Jadwal Bimbingan (Fitur Dosen)
        Schema::create('bimbingan_jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('waktu_bimbingan');
            $table->string('tempat')->nullable(); // Misal: Ruang 303 atau Google Meet
            $table->text('topik')->nullable();
            $table->enum('status', ['dijadwalkan', 'pengajuan_reschedule', 'disetujui_reschedule', 'selesai', 'batal'])->default('dijadwalkan');
            $table->text('alasan_reschedule')->nullable(); // Jika mahasiswa minta ganti jadwal
            $table->dateTime('waktu_reschedule')->nullable(); // Waktu yang diminta mahasiswa
            $table->timestamps();
        });

        // 3. Buat tabel Sidang (Fitur Admin)
        Schema::create('sidangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('proposals')->onDelete('cascade');
            $table->dateTime('jadwal_sidang');
            $table->string('ruangan')->nullable();
            // Kita simpan penguji di sini juga untuk history, meski di proposal sudah ada
            $table->foreignId('penguji_1_id')->nullable()->constrained('users');
            $table->foreignId('penguji_2_id')->nullable()->constrained('users');
            $table->enum('status', ['dijadwalkan', 'pending_reschedule', 'selesai'])->default('dijadwalkan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sidangs');
        Schema::dropIfExists('bimbingan_jadwals');
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropColumn('file_ta');
        });
    }
};