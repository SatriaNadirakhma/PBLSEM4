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
        Schema::create('dosen', function (Blueprint $table) {
            $table->id('dosen_id');
            $table->string('nidn', 20)->unique();
            $table->string('dosen_nama', 60);
            $table->string('no_telp', 15)->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            
            // Foreign key ke 'jurusan'
            $table->unsignedBigInteger('jurusan_id'); // Tentukan tipe data sesuai 'jurusan_id' di tabel jurusan
            $table->foreign('jurusan_id')
                ->references('jurusan_id') // Pastikan ini merujuk ke 'jurusan_id' di tabel 'jurusan'
                ->on('jurusan')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
