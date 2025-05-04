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
        Schema::create('hasil_ujian', function (Blueprint $table) {
            $table->id('hasil_id');
            $table->integer('nilai_listening');
            $table->integer('nilai_reading');
            $table->integer('nilai_total'); 
            $table->enum('status_lulus', ['lulus', 'tidak lulus']);
            $table->text('catatan')->nullable();
        
            // Foreign key ke jadwal (dengan primary key jadwal_id)
            $table->unsignedBigInteger('jadwal_id');
            $table->foreign('jadwal_id')->references('jadwal_id')->on('jadwal')->onDelete('cascade');
        
            // Foreign key ke user (dengan primary key user_id)
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('user')->onDelete('cascade');
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_ujian');
    }
};
