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
        Schema::table('jadwal', function (Blueprint $table) {
            // Ubah tipe kolom tanggal_pelaksanaan menjadi date
            $table->date('tanggal_pelaksanaan')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            // Kembalikan ke tipe datetime
            $table->dateTime('tanggal_pelaksanaan')->change();
        });
    }
};
