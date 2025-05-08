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
        Schema::create('jurusan', function (Blueprint $table) {
            $table->id('jurusan_id');
            $table->string('jurusan_kode', 10);
            $table->string('jurusan_nama', 20);
            $table->unsignedBigInteger('kampus_id');
            // foreign key
            // onDelete('cascade') -> if the kampus is deleted, all related jurusan will be deleted
            $table->foreign('kampus_id')->references('kampus_id')->on('kampus')->onDelete('cascade');
            $table->timestamps(); // otomatis buat created_at dan updated_at
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurusan');
    }
};
