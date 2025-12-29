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
    // 1. Tambah Prodi ke tabel Users
    Schema::table('users', function (Blueprint $table) {
        if (!Schema::hasColumn('users', 'prodi')) {
            $table->string('prodi')->default('Informatika')->after('nim');
        }
    });

    // 2. Tambah File & Komentar ke tabel Proposals
    Schema::table('proposals', function (Blueprint $table) {
        if (!Schema::hasColumn('proposals', 'file_path')) {
            $table->string('file_path')->nullable()->after('judul');
        }
        if (!Schema::hasColumn('proposals', 'komentar')) {
            $table->text('komentar')->nullable()->after('status');
        }
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['prodi']);
    });
    Schema::table('proposals', function (Blueprint $table) {
        $table->dropColumn(['file_path', 'komentar']);
    });
}
};
