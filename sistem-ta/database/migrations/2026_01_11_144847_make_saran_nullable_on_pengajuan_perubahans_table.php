<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('pengajuan_perubahans', function (Blueprint $table) {
            // Mengubah kolom agar boleh kosong (nullable)
            $table->date('tanggal_saran')->nullable()->change();
            $table->time('jam_saran')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
