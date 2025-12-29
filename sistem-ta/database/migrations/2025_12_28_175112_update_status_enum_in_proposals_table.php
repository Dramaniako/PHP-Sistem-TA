<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <--- PENTING: Jangan lupa import ini

return new class extends Migration
{
    public function up()
{
    Schema::dropIfExists('bimbingan_slots');
}

public function down()
{
    // Kosongkan atau isi ulang struktur tabel jika ingin bisa di-rollback
}
};