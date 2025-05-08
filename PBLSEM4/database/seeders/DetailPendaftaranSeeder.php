<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailPendaftaranSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('detail_pendaftaran')->insert([
            [
                'pendaftaran_id' => 1,
                'status' => 'diterima',
                'catatan' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pendaftaran_id' => 2,
                'status' => 'diterima',
                'catatan' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
