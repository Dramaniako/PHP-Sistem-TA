<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // CEK DULU: Hanya buat kolom jika kolom tersebut BELUM ada
        if (!Schema::hasColumn('proposals', 'dosen_pembimbing_id')) {
            Schema::table('proposals', function (Blueprint $table) {
                $table->foreignId('dosen_pembimbing_id')->nullable()->constrained('users')->onDelete('set null')->after('mahasiswa_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            // Hapus foreign key dan kolom jika ada
            $table->dropForeign(['dosen_pembimbing_id']);
            $table->dropColumn('dosen_pembimbing_id');
        });
    }
};