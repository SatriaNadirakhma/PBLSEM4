<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jadwal')->insert([
            [
                'tanggal_pelaksanaan' => Carbon::createFromFormat('d/m/Y', '13/04/2025')->toDateTimeString(),
                'jam_mulai' => '08:00:00',
                'keterangan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tanggal_pelaksanaan' => Carbon::createFromFormat('d/m/Y', '15/04/2025')->toDateTimeString(),
                'jam_mulai' => '14:00:00',
                'keterangan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
