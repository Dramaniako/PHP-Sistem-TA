<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dokumen_ta', function (Blueprint $table) {
            // 1. Mengubah nama kolom agar sesuai dengan Controller
            // Pastikan Anda sudah menginstall library 'doctrine/dbal' jika menggunakan Laravel versi lama
            $table->renameColumn('nama_file', 'jenis_dokumen');
            $table->renameColumn('path_file', 'file_path');

            // 2. Menambahkan kolom yang kurang untuk mendukung fitur validasi & revisi
            $table->text('komen_mahasiswa')->after('file_path')->nullable();
            $table->text('catatan_dosen')->after('komen_mahasiswa')->nullable();

            // 3. Menyesuaikan tipe data enum agar case-sensitive sesuai Controller
            $table->string('status')->default('Menunggu')->change();
        });
    }

    public function down(): void
    {
        Schema::table('dokumen_ta', function (Blueprint $table) {
            $table->renameColumn('jenis_dokumen', 'nama_file');
            $table->renameColumn('file_path', 'path_file');
            $table->dropColumn(['komen_mahasiswa', 'catatan_dosen']);
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu')->change();
        });
    }
};