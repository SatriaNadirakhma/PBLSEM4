<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('ujian');
    }

    public function down(): void
    {
        Schema::create('ujian', function (Blueprint $table) {
            $table->id('ujian_id');
            $table->string('ujian_kode')->unique();
            $table->unsignedBigInteger('jadwal_id');
            $table->unsignedBigInteger('pendaftaran_id');
            $table->timestamps();

            // Menambahkan foreign key constraint
            $table->foreign('jadwal_id')->references('jadwal_id')->on('jadwal')->onDelete('cascade');
            $table->foreign('pendaftaran_id')->references('pendaftaran_id')->on('pendaftaran')->onDelete('cascade');
        });
    }
};
