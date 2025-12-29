<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('dosen_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('proposal_id')->constrained()->onDelete('cascade');
        $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade'); // Dosen yang dituju
        $table->enum('role', ['pembimbing', 'penguji_1', 'penguji_2']); // Sebagai apa?
        $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
        $table->text('pesan_penolakan')->nullable(); // Alasan jika menolak
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('dosen_requests');
}
};
