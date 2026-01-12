<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dokumen_ta', function (Blueprint $table) {
            // 1. Hapus Foreign Key lama yang bermasalah
            // Nama constraint biasanya: namaTabel_namaKolom_foreign
            $table->dropForeign(['mahasiswa_id']);

            // 2. Ubah nama kolom agar sesuai Controller jika belum diubah
            // Gunakan IF untuk mencegah error jika kolom sudah diganti sebelumnya
            if (Schema::hasColumn('dokumen_ta', 'nama_file')) {
                $table->renameColumn('nama_file', 'jenis_dokumen');
            }
            if (Schema::hasColumn('dokumen_ta', 'path_file')) {
                $table->renameColumn('path_file', 'file_path');
            }

            // 3. Tambah kolom pendukung jika belum ada
            if (!Schema::hasColumn('dokumen_ta', 'komen_mahasiswa')) {
                $table->text('komen_mahasiswa')->nullable()->after('file_path');
            }
            if (!Schema::hasColumn('dokumen_ta', 'catatan_dosen')) {
                $table->text('catatan_dosen')->nullable()->after('komen_mahasiswa');
            }

            // 4. Perbaiki tipe data status agar support 'Menunggu'
            $table->string('status')->default('Menunggu')->change();
        });

        Schema::table('dokumen_ta', function (Blueprint $table) {
            // 5. Buat Foreign Key baru yang mengarah ke tabel 'users'
            // Ini akan memperbaiki Error 1452 karena ID 10 ada di tabel users
            $table->foreign('mahasiswa_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Logika untuk mengembalikan jika diperlukan
    }
};