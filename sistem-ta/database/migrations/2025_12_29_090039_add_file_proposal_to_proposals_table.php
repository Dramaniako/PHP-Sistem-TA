<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('proposals', function (Blueprint $table) {
        // Menambah kolom file_proposal
        $table->string('file_proposal')->nullable()->after('deskripsi');
    });
}

public function down(): void
{
    Schema::table('proposals', function (Blueprint $table) {
        $table->dropColumn('file_proposal');
    });
}
};
