<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->text('alamat_asal')->nullable()->after('no_telp');
            $table->text('alamat_sekarang')->nullable()->after('alamat_asal');
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropColumn(['alamat_asal', 'alamat_sekarang']);
        });
    }
};
