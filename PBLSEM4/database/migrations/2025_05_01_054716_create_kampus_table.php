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
        Schema::create('kampus', function (Blueprint $table) {
            $table->id('kampus_id');
            $table->string('kampus_kode', 10);
            $table->string('kampus_nama', 10);
            $table->timestamps(); // otomatis buat created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kampus');
    }
};
