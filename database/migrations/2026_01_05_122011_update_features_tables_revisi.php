<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom File TA di tabel proposals (Untuk Mahasiswa)
        if (!Schema::hasColumn('proposals', 'file_ta')) {
            Schema::table('proposals', function (Blueprint $table) {
                $table->string('file_ta')->nullable()->after('file_khs');
            });
        }

        // 2. Tabel Bimbingan Slots (Untuk Dosen & Fitur Reschedule)
        // Kita gunakan nama tabel 'bimbingan_slots' sesuai model Anda
        if (!Schema::hasTable('bimbingan_slots')) {
            Schema::create('bimbingan_slots', function (Blueprint $table) {
                $table->id();
                $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
                
                $table->dateTime('waktu_bimbingan');
                $table->string('tempat')->nullable(); 
                $table->text('topik')->nullable();
                
                // Kolom Penting untuk Fitur Reschedule
                $table->enum('status', ['dijadwalkan', 'pengajuan_reschedule', 'disetujui_reschedule', 'selesai', 'batal'])->default('dijadwalkan');
                $table->text('alasan_reschedule')->nullable();
                $table->dateTime('waktu_reschedule')->nullable(); // Waktu baru yang diminta mahasiswa
                
                $table->timestamps();
            });
        }

        // 3. Tabel Sidang (Untuk KOORDINATOR)
        if (!Schema::hasTable('sidangs')) {
            Schema::create('sidangs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('proposal_id')->constrained('proposals')->onDelete('cascade');
                $table->dateTime('jadwal_sidang');
                $table->string('ruangan')->nullable();
                $table->foreignId('penguji_1_id')->nullable()->constrained('users');
                $table->foreignId('penguji_2_id')->nullable()->constrained('users');
                $table->enum('status', ['dijadwalkan', 'selesai'])->default('dijadwalkan');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sidangs');
        Schema::dropIfExists('bimbingan_slots');
        if (Schema::hasColumn('proposals', 'file_ta')) {
            Schema::table('proposals', function (Blueprint $table) {
                $table->dropColumn('file_ta');
            });
        }
    }
};