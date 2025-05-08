<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InformasiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('informasi')->insert([
            'judul' => 'Pendaftaran',
            'isi' => 'Pendaftaran dapat diakses lewat link yang tertera pada dasboard',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
