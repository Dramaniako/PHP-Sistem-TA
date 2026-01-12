<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('pengajuan_perubahans', function (Blueprint $table) {
            // Kolom untuk menyimpan path surat kerja dosen
            $table->string('file_pendukung')->after('jam_saran')->nullable();

            // Kolom untuk membedakan siapa yang meminta (mahasiswa atau dosen)
            $table->enum('tipe_pengaju', ['mahasiswa', 'dosen'])->after('status')->default('mahasiswa');
        });
    }

    public function down()
    {
        Schema::table('pengajuan_perubahans', function (Blueprint $table) {
            $table->dropColumn(['file_pendukung', 'tipe_pengaju']);
        });
    }
};
