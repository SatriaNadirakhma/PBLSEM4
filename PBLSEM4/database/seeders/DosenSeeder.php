<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DosenSeeder extends Seeder
{
    public function run(): void
    {

        DB::table('dosen')->insert([
            [
                'dosen_id' => 1,
                'nidn' => '0215047803',
                'nik' => '3576021501010001',
                'dosen_nama' => 'Dimas Wahyu',
                'no_telp' => '0815038702',
                'alamat_asal' => 'Jalan Melati No. 45, RT 03 RW 07',
                'alamat_sekarang' => 'Apartemen Green Palm Tower B Lt. 10 No. 1008',
                'jenis_kelamin' => 'Laki-laki',
                'jurusan_id' => 6,
                'created_at' => Carbon::now(),
            ],
            [
                'dosen_id' => 2,
                'nidn' => '0215047823',
                'nik' => '3576021501010002',
                'dosen_nama' => 'Eka Larasati',
                'no_telp' => '0815038775',
                'alamat_asal' => 'Jalan Mawar No. 49, RT 01 RW 01',
                'alamat_sekarang' => 'Jalan H. R. Rasuna Said, Kelurahan Kuningan Timur',
                'jenis_kelamin' => 'Perempuan',
                'jurusan_id' => 6,
                'created_at' => Carbon::now(),
            ],
            [
                'dosen_id' => 3,
                'nidn' => '0215047389',
                'nik' => '3576021501010003',
                'dosen_nama' => 'Rakmat Arianto',
                'no_telp' => '0817034772',
                'alamat_asal' => 'Jalan Anggrek No. 23, RT 09 RW 010',
                'alamat_sekarang' => 'Jl. Veteran No 19',
                'jenis_kelamin' => 'Laki-laki',
                'jurusan_id' => 6,
                'created_at' => Carbon::now(),
            ],
        ]);
    }
}

