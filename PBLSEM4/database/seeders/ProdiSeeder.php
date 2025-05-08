<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now(); // waktu saat ini

        DB::table('prodi')->insert([
            ['prodi_id' => 1, 'prodi_kode' => '24742035', 'prodi_nama' => 'D-IV Sistem Informasi Bisnis','jurusan_id' => 6, 'created_at' => $now],
            ['prodi_id' => 2, 'prodi_kode' => '24742005', 'prodi_nama' => 'D-IV Teknik Informatika', 'jurusan_id' => 6, 'created_at' => $now],
            ['prodi_id' => 3, 'prodi_kode' => '24742055', 'prodi_nama' => 'D-II PPLS', 'jurusan_id' => 6, 'created_at' => $now],
            ['prodi_id' => 4, 'prodi_kode' => '24743038', 'prodi_nama' => 'D-III Teknik Elektronika', 'jurusan_id' => 7, 'created_at' => $now],
            ['prodi_id' => 5, 'prodi_kode' => '24743041', 'prodi_nama' => 'D-III Telekomunikasi', 'jurusan_id' => 7, 'created_at' => $now],
            ['prodi_id' => 6, 'prodi_kode' => '24743027', 'prodi_nama' => 'D-III Teknik Listrik', 'jurusan_id' => 7, 'created_at' => $now],
            ['prodi_id' => 7, 'prodi_kode' => '24742003', 'prodi_nama' => 'D-IV Teknik Elektronika', 'jurusan_id' => 7, 'created_at' => $now],
            ['prodi_id' => 8, 'prodi_kode' => '24742003', 'prodi_nama' => 'D-IV Sistem Kelistrikan', 'jurusan_id' => 7, 'created_at' => $now],
            ['prodi_id' => 9, 'prodi_kode' => '24742001', 'prodi_nama' => 'D-IV Jaringan Telekomunikasi Digital', 'jurusan_id' => 7, 'created_at' => $now],
            ['prodi_id' => 10, 'prodi_kode' => '24743023', 'prodi_nama' => 'D-III Teknik Kimia', 'jurusan_id' => 3, 'created_at' => $now],
            ['prodi_id' => 11, 'prodi_kode' => '24742006', 'prodi_nama' => 'DIV Teknik Kimia Industri', 'jurusan_id' => 3, 'created_at' => $now],
            ['prodi_id' => 12, 'prodi_kode' => '24743037', 'prodi_nama' => 'D-III Teknik Sipil', 'jurusan_id' => 4, 'created_at' => $now],
            ['prodi_id' => 13, 'prodi_kode' => '24743022', 'prodi_nama' => 'D-III Teknologi Pertambangan', 'jurusan_id' => 4, 'created_at' => $now],
            ['prodi_id' => 14, 'prodi_kode' => '24743024', 'prodi_nama' => 'D-III Teknologi Konstruksi Jalan, Jembatan, Dan Bangunan Air', 'jurusan_id' => 4,'created_at' => $now],
            ['prodi_id' => 15, 'prodi_kode' => '24742002', 'prodi_nama' => 'D-IV Manajemen Rekayasa Konstruksi', 'jurusan_id' => 4, 'created_at' => $now],
            ['prodi_id' => 16, 'prodi_kode' => '24743036', 'prodi_nama' => 'D-III Teknik Mesin','jurusan_id' => 5, 'created_at' => $now],
            ['prodi_id' => 17, 'prodi_kode' => '24740407', 'prodi_nama' => 'D-III Teknik Pemeliharaan Pesawat Udara', 'jurusan_id' => 5,'created_at' => $now],
            ['prodi_id' => 18, 'prodi_kode' => '24742008', 'prodi_nama' => 'D-IV Teknik Otomotif Elektronik', 'jurusan_id' => 5,'created_at' => $now],
            ['prodi_id' => 19, 'prodi_kode' => '24742007', 'prodi_nama' => 'D-IV Teknik Mesin Produksi Dan Perawatan', 'jurusan_id' => 5, 'created_at' => $now],
            ['prodi_id' => 20, 'prodi_kode' => '24743033', 'prodi_nama' => 'D-III Akuntansi', 'jurusan_id' => 2, 'created_at' => $now],
            ['prodi_id' => 21, 'prodi_kode' => '24742014', 'prodi_nama' => 'D-IV Akuntansi Manajemen', 'jurusan_id' => 2, 'created_at' => $now],
            ['prodi_id' => 22, 'prodi_kode' => '24742015', 'prodi_nama' => 'D-IV Keuangan', 'jurusan_id' => 2, 'created_at' => $now],
            ['prodi_id' => 23, 'prodi_kode' => '24743032', 'prodi_nama' => 'D-III Administrasi Bisnis', 'jurusan_id' => 1, 'created_at' => $now],
            ['prodi_id' => 24, 'prodi_kode' => '24742016', 'prodi_nama' => 'D-IV Manajemen Pemasaran', 'jurusan_id' => 1, 'created_at' => $now],
            ['prodi_id' => 25, 'prodi_kode' => '24742018', 'prodi_nama' => 'D-IV Pengelolaan Arsip Dan Rekaman Informasi', 'jurusan_id' => 1, 'created_at' => $now],
            ['prodi_id' => 26, 'prodi_kode' => '24742019', 'prodi_nama' => 'D-IV Usaha Perjalanan Wisata', 'jurusan_id' => 1, 'created_at' => $now],
            ['prodi_id' => 27, 'prodi_kode' => '24742039', 'prodi_nama' => 'D-IV Bahasa Inggris Untuk Industri Pariwisata', 'jurusan_id' => 1, 'created_at' => $now],
            ['prodi_id' => 28, 'prodi_kode' => '24742017', 'prodi_nama' => 'D-IV Bahasa Inggris Untuk Komunikasi Bisnis Dan Profesional', 'jurusan_id' => 1, 'created_at' => $now],
        ]);
    }
}