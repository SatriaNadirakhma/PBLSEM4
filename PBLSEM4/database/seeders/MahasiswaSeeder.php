<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        DB::table('mahasiswa')->insert([
            [
                'mahasiswa_id' => 1,
                'nim' => '2341760022',
                'nik' => '3506066302050003',
                'mahasiswa_nama' => 'Aqila Nur Azza',
                'angkatan' => 2023,
                'no_telp' => '081230382286',
                'alamat_asal' => 'Kediri, Jawa Timur',
                'alamat_sekarang' => 'Jl. Simpang Remujung',
                'jenis_kelamin' => 'Perempuan',
                'status' => 'aktif',
                'keterangan' => 'gratis',
                'prodi_id' => 1,
                'created_at' => $now,
            ],
            [
                'mahasiswa_id' => 2,
                'nim' => '2341760105',
                'nik' => '3573034608050005',
                'mahasiswa_nama' => 'Faiza Anathasya Eka Falen',
                'angkatan' => 2023,
                'no_telp' => '081230948205',
                'alamat_asal' => 'Jl. Danau Kerinci 3',
                'alamat_sekarang' => 'Jl. Danau Kerinci 3',
                'jenis_kelamin' => 'Perempuan',
                'status' => 'aktif',
                'keterangan' => 'gratis',
                'prodi_id' => 1,
                'created_at' => $now,
            ],
            [
                'mahasiswa_id' => 3,
                'nim' => '2341760013',
                'nik' => '3515077107040001',
                'mahasiswa_nama' => 'Lyra Faiqah Bilqis',
                'angkatan' => 2023,
                'no_telp' => '085655896780',
                'alamat_asal' => 'Sidoarjo, Jawa Timur',
                'alamat_sekarang' => 'Jl. Kembang Turi',
                'jenis_kelamin' => 'Perempuan',
                'status' => 'aktif',
                'keterangan' => 'berbayar',
                'prodi_id' => 1,
                'created_at' => $now,
            ],
            [
                'mahasiswa_id' => 4,
                'nim' => '2341760012',
                'nik' => '3275082401030009',
                'mahasiswa_nama' => 'Muhammad Reishi Fauzi',
                'angkatan' => 2023,
                'no_telp' => '085773071834',
                'alamat_asal' => 'Bekasi, Jawa Barat',
                'alamat_sekarang' => 'Jl. DMC 7 No. 11',
                'jenis_kelamin' => 'Laki-laki',
                'status' => 'aktif',
                'keterangan' => 'berbayar',
                'prodi_id' => 1,
                'created_at' => $now,
            ],
            [
                'mahasiswa_id' => 5,
                'nim' => '2341760106',
                'nik' => '3525140505050001',
                'mahasiswa_nama' => 'Satria Rakhmadani',
                'angkatan' => 2023,
                'no_telp' => '085335510121',
                'alamat_asal' => 'Permata Tunggulwulung Kav. 10',
                'alamat_sekarang' => 'Permata Tunggulwulung Kav. 10',
                'jenis_kelamin' => 'Laki-laki',
                'status' => 'aktif',
                'keterangan' => 'gratis',
                'prodi_id' => 1,
                'created_at' => $now,
            ],
        ]);
    }
}

