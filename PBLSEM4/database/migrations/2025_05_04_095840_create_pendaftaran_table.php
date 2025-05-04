<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id('pendaftaran_id');
            $table->string('pendaftaran_kode', 10);
            $table->dateTime('tanggal_pendaftaran');

            $table->string('scan_ktp', 255);
            $table->string('scan_ktm', 255);
            $table->string('pas_foto', 255);

            $table->unsignedBigInteger('mahasiswa_id'); // Wajib diisi
            $table->unsignedBigInteger('jadwal_id');    // Wajib diisi

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('jadwal_id')->references('jadwal_id')->on('jadwal')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
