<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('user')->insert([
            [
                'email' => 'admin@gmail.com',
                'username' => 'admin',
                'password' => Hash::make('12345'),
                'profile' => '12345',
                'role' => 'admin',
                'admin_id' => 1,
                'mahasiswa_id' => null,
                'dosen_id' => null,
                'tendik_id' => null,
            ],
            [
                'email' => 'admin1@gmail.com',
                'username' => 'admin1',
                'password' => Hash::make('12345'),
                'profile' => '12345',
                'role' => 'admin',
                'admin_id' => 2,
                'mahasiswa_id' => null,
                'dosen_id' => null,
                'tendik_id' => null,
            ],
            [
                'email' => 'aqila@gmail.com',
                'username' => '2341760022',
                'password' => Hash::make('12345'),
                'profile' => '12345',
                'role' => 'mahasiswa',
                'admin_id' => null,
                'mahasiswa_id' => 1,
                'dosen_id' => null,
                'tendik_id' => null,
            ],
            [
                'email' => 'faizaanathasya@gmail.com',
                'username' => '2341760105',
                'password' => Hash::make('12345'),
                'profile' => '12345',
                'role' => 'mahasiswa',
                'admin_id' => null,
                'mahasiswa_id' => 2,
                'dosen_id' => null,
                'tendik_id' => null,
            ],
            [
                'email' => 'lyra@gmail.com',
                'username' => '2341760013',
                'password' => Hash::make('12345'),
                'profile' => '12345',
                'role' => 'mahasiswa',
                'admin_id' => null,
                'mahasiswa_id' => 3,
                'dosen_id' => null,
                'tendik_id' => null,
            ],
            [
                'email' => 'reishi@gmail.com',
                'username' => '2341760012',
                'password' => Hash::make('12345'),
                'profile' => '12345',
                'role' => 'mahasiswa',
                'admin_id' => null,
                'mahasiswa_id' => 4,
                'dosen_id' => null,
                'tendik_id' => null,
            ],
            [
                'email' => 'satri@gmail.com',
                'username' => '2341760106',
                'password' => Hash::make('12345'),
                'profile' => '12345',
                'role' => 'mahasiswa',
                'admin_id' => null,
                'mahasiswa_id' => 5,
                'dosen_id' => null,
                'tendik_id' => null,
            ],
        ]);
    }
}
