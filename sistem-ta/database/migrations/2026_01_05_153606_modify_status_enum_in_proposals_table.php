<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    // Menggunakan Raw Statement agar support ENUM update di MySQL
    DB::statement("ALTER TABLE proposals MODIFY COLUMN status ENUM('pending', 'disetujui', 'ditolak', 'revisi') NOT NULL DEFAULT 'pending'");
}

public function down(): void
{
    // Rollback ke status lama (opsional)
    DB::statement("ALTER TABLE proposals MODIFY COLUMN status ENUM('pending', 'disetujui', 'ditolak') NOT NULL DEFAULT 'pending'");
}
};
