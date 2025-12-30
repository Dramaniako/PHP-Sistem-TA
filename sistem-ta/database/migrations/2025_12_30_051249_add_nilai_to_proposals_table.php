<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('proposals', function (Blueprint $table) {
        // Tambahkan kolom nilai (integer/angka), nullable (karena awal upload belum ada nilai)
        $table->integer('nilai')->nullable()->after('status');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            //
        });
    }
};
