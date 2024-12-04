<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Guru;
use App\Models\Kriteria;
use App\Models\Periode;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            KelasSeeder::class,
        ]);
        User::create([
            'name' => 'Kepala Sekolah',
            'nip' => '112233',
            'password' => bcrypt('password'),
            'role' => 'kepala sekolah',
        ]);
        User::factory(10)->hasGuru()->create();
        User::factory(10)->hasSiswa()->create();
        Kriteria::factory(10)->create();
    }
}
