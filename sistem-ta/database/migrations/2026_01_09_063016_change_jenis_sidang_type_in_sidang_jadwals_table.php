<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sidang_jadwals', function (Blueprint $table) {
            // Mengubah tipe data menjadi string (VARCHAR) agar lebih fleksibel
            // Kita gunakan 255 karakter agar tidak ada lagi error 'Data truncated'
            $table->string('jenis_sidang', 255)->change();
        });
    }

    public function down(): void
    {
        Schema::table('sidang_jadwals', function (Blueprint $table) {
            // Jika ingin rollback, kembalikan ke enum (sesuaikan dengan enum awal Anda)
            $table->enum('jenis_sidang', ['Sidang Proposal', 'Sidang Akhir'])->change();
        });
    }
};