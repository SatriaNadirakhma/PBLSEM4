<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('kampus', function (Blueprint $table) {
            $table->string('kampus_nama', 50)->change();
        });
    }

    public function down()
    {
        Schema::table('kampus', function (Blueprint $table) {
            $table->string('kampus_nama')->change(); // default length: 255
        });
    }

};
