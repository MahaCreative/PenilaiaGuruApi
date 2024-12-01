<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kelas')->insert([
            [
                'kode_kelas' => 'VIIA',
                'nama_kelas' => 'VII A',
            ],
            [
                'kode_kelas' => 'VIIIA',
                'nama_kelas' => 'VIII A',
            ],
        ]);
    }
}
