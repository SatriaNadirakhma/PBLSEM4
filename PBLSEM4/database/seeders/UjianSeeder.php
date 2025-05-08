<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UjianSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ujian')->insert([
            [
                'ujian_kode' => 'UJ0001',
                'jadwal_id' => 1,
                'pendaftaran_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ujian_kode' => 'UJ0002',
                'jadwal_id' => 2,
                'pendaftaran_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
