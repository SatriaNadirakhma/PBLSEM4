<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        DB::table('admin')->insert([
            [
                'admin_id' => 1,
                'admin_nama' => 'admin',
                'no_telp' => '0812345678',
                'created_at' => $now,
            ],
            [
                'admin_id' => 2,
                'admin_nama' => 'admin1',
                'no_telp' => '0812345678',
                'created_at' => $now,
            ],
        ]);
    }
}
