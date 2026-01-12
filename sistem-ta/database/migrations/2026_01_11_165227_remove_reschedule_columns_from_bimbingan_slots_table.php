<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bimbingan_slots', function (Blueprint $table) {
            $table->dropColumn(['waktu_reschedule', 'alasan_reschedule']); // Menghapus kolom sisa fungsi reschedule
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bimbingan_slots', function (Blueprint $table) {
            //
        });
    }
};
