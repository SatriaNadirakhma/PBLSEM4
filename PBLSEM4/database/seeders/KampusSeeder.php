<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class KampusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kampus')->insert([
            [
                'kampus_id' => 1,
                'kampus_kode' => 'K001',
                'kampus_nama' => 'Utama',
                'created_at' => now(),
            ],
            [
                'kampus_id' => 2,
                'kampus_kode' => 'K002',
                'kampus_nama' => 'PSDKU Kediri',
                'created_at' => now(),
            ],
            [
                'kampus_id' => 3,
                'kampus_kode' => 'K003',
                'kampus_nama' => 'PSDKU Lumajang',
                'created_at' => now(),
            ],
            [
                'kampus_id' => 4,
                'kampus_kode' => 'K004',
                'kampus_nama' => 'PSDKU Pamekasan',
                'created_at' => now(),
            ],
        ]); 
    }
}
