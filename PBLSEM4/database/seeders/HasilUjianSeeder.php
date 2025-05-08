<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HasilUjianSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('hasil_ujian')->insert([
            [
                'nilai_listening' => 350,
                'nilai_reading' => 315,
                'nilai_total' => 665,
                'status_lulus' => 'lulus',
                'catatan' => null,
                'jadwal_id' => 1,
                'user_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nilai_listening' => 225,
                'nilai_reading' => 260,
                'nilai_total' => 485,
                'status_lulus' => 'tidak lulus',
                'catatan' => 'Harap mengikuti tes toeic kembali secara mandiri',
                'jadwal_id' => 2,
                'user_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
