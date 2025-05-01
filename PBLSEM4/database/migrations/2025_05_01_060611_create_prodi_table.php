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
        Schema::create('prodi', function (Blueprint $table) {
            $table->id('prodi_id');
            $table->string('prodi_kode', 10);
            $table->string('prodi_nama', 50);
            $table->unsignedBigInteger('jurusan_id');
            // foreign key
            // onDelete('cascade') -> if the jurusan is deleted, all related prodi will be deleted
            // onUpdate('cascade') -> if the jurusan is updated, all related prodi will be updated
            $table->foreign('jurusan_id')->references('jurusan_id')->on('jurusan')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps(); // otomatis buat created_at dan updated_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prodi');
    }
};
