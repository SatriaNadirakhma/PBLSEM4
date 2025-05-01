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
        Schema::create('user', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('email', 60)->unique();
            $table->string('username', 20)->unique();
            $table->string('password', 255);
            $table->string('profile', 255)->nullable();
            $table->enum('role', ['admin', 'mahasiswa', 'dosen', 'tendik']);
            
            // Relasi ke admin
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('admin_id')->references('admin_id')->on('admin')->onDelete('set null');
        
            // Relasi ke mahasiswa
            $table->unsignedBigInteger('mahasiswa_id')->nullable();
            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('mahasiswa')->onDelete('set null');
        
            // Relasi ke dosen
            $table->unsignedBigInteger('dosen_id')->nullable();
            $table->foreign('dosen_id')->references('dosen_id')->on('dosen')->onDelete('set null');
        
            // Relasi ke tendik
            $table->unsignedBigInteger('tendik_id')->nullable();
            $table->foreign('tendik_id')->references('tendik_id')->on('tendik')->onDelete('set null');
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
