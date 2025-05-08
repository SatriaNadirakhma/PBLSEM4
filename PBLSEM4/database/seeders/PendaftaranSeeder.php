<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PendaftaranSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pendaftaran')->insert([
            [
                'pendaftaran_kode' => 'PT0001',
                'tanggal_pendaftaran' => Carbon::createFromFormat('d/m/Y', '10/04/2025')->toDateTimeString(),
                'scan_ktp' => '',
                'scan_ktm' => '',
                'pas_foto' => '',
                'mahasiswa_id' => 3,
                'jadwal_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pendaftaran_kode' => 'PT0002',
                'tanggal_pendaftaran' => Carbon::createFromFormat('d/m/Y', '13/04/2025')->toDateTimeString(),
                'scan_ktp' => '',
                'scan_ktm' => '',
                'pas_foto' => '',
                'mahasiswa_id' => 4,
                'jadwal_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
