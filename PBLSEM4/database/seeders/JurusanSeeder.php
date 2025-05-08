<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jurusan')->insert([
            [
                'jurusan_id' => 1,
                'jurusan_kode' => 'J001',
                'jurusan_nama' => 'Administrasi Niaga',
                'kampus_id' => 1,
                'created_at' => now(),
            ],
            [
                'jurusan_id' => 2,
                'jurusan_kode' => 'J002',
                'jurusan_nama' => 'Akuntansi',
                'kampus_id' => 1,
                'created_at' => now(),
            ],
            [
                'jurusan_id' => 3,
                'jurusan_kode' => 'J003',
                'jurusan_nama' => 'Teknik Kimia',
                'kampus_id' => 1,
                'created_at' => now(),
            ],
            [
                'jurusan_id' => 4,
                'jurusan_kode' => 'J004',
                'jurusan_nama' => 'Teknik Sipil',
                'kampus_id' => 1,
                'created_at' => now(),
            ],
            [
                'jurusan_id' => 5,
                'jurusan_kode' => 'J005',
                'jurusan_nama' => 'Teknik Mesin',
                'kampus_id' => 1,
                'created_at' => now(),
            ],
            [
                'jurusan_id' => 6,
                'jurusan_kode' => 'J006',
                'jurusan_nama' => 'Teknologi Informasi',
                'kampus_id' => 1,
                'created_at' => now(),
            ],
            [
                'jurusan_id' => 7,
                'jurusan_kode' => 'J007',
                'jurusan_nama' => 'Teknik Elektro',
                'kampus_id' => 1,
                'created_at' => now(),
            ],
        ]);
    }
}
