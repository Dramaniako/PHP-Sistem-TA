<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom NIM (Nullable karena Admin/Dosen mungkin tidak punya NIM)
            // Taruh setelah kolom email agar rapi
            if (!Schema::hasColumn('users', 'nim')) {
                $table->string('nim')->nullable()->after('email');
            }

            // Tambahkan kolom Role (Jika belum ada dari merge sebelumnya)
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'koordinator', 'dosen', 'mahasiswa'])->default('mahasiswa')->after('password');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nim', 'role']);
        });
    }
};