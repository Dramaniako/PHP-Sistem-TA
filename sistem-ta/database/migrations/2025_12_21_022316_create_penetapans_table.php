<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penetapans', function (Blueprint $table) {
            $table->id();
            // Data Mahasiswa
            $table->string('nim');
            $table->string('nama_mahasiswa');
            $table->date('tanggal_ujian');
            
            // Slot Penguji 1
            $table->string('nidn_penguji_1')->nullable();
            $table->string('nama_penguji_1')->nullable();
            $table->string('status_penguji_1')->default('Bersedia');

            // Slot Penguji 2
            $table->string('nidn_penguji_2')->nullable();
            $table->string('nama_penguji_2')->nullable();
            $table->string('status_penguji_2')->default('Bersedia');

            // Slot Penguji 3
            $table->string('nidn_penguji_3')->nullable();
            $table->string('nama_penguji_3')->nullable();
            $table->string('status_penguji_3')->default('Bersedia');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penetapans');
    }
};