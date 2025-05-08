<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TendikSeeder extends Seeder
{
    public function run(): void
    {

        DB::table('tendik')->insert([
            [
                'tendik_id' => 1,
                'nip' => '196303061989672390',
                'nik' => '3576021902010005',
                'tendik_nama' => 'Nicholas Saputra',
                'no_telp' => '083487995487',
                'alamat_asal' => 'Bali',
                'alamat_sekarang' => 'Malang',
                'jenis_kelamin' => 'Laki-laki',
                'kampus_id' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'tendik_id' => 2,
                'nip' => '196303061989031006',
                'nik' => '3578021702010005',
                'tendik_nama' => 'Mamat Alkatiri',
                'no_telp' => '081276672309',
                'alamat_asal' => 'Papua',
                'alamat_sekarang' => 'Pamekasan',
                'jenis_kelamin' => 'Laki-laki',
                'kampus_id' => 4,
                'created_at' => Carbon::now(),
            ],
        ]);
    }
}
