<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tendik', function (Blueprint $table) {
            $table->string('nik', 16)->after('nip')->unique();             // NIK: varchar(16), unique
            $table->text('alamat_asal')->nullable()->after('no_telp');         // Alamat asal
            $table->text('alamat_sekarang')->nullable()->after('alamat_asal'); // Alamat sekarang
        });
    }

    public function down(): void
    {
        Schema::table('tendik', function (Blueprint $table) {
            $table->dropColumn(['nik', 'alamat_asal', 'alamat_sekarang']);
        });
    }
};
