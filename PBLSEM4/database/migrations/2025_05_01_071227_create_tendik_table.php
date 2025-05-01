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
        Schema::create('tendik', function (Blueprint $table) {
            $table->id('tendik_id');
            $table->string('nip', 20)->unique();
            $table->string('tendik_nama', 60);
            $table->string('no_telp', 15)->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            // Menambahkan kolom kampus_id sebagai foreign key
            $table->unsignedBigInteger('kampus_id');
            // Mendefinisikan foreign key
            $table->foreign('kampus_id')
                ->references('kampus_id')->on('kampus')
                ->onDelete('cascade') // Menghapus tendik ketika kampus dihapus
                ->onUpdate('cascade'); // Mengupdate tendik ketika kampus diupdate
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tendik');
    }
};
