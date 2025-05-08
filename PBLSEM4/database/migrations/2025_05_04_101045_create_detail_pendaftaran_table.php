<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pendaftaran', function (Blueprint $table) {
            $table->id('detail_id');
            $table->unsignedBigInteger('pendaftaran_id')->unique();
            $table->enum('status', ['menunggu', 'diterima', 'ditolak']);
            $table->text('catatan')->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            // foreign key ke kolom 'pendaftaran_id' di tabel pendaftaran
            $table->foreign('pendaftaran_id')->references('pendaftaran_id')->on('pendaftaran')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pendaftaran');
    }
};
