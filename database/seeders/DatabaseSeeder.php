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

        User::create([
            'name' => 'Kepala Sekolah',
            'nip' => '112233',
            'password' => bcrypt('password'),
            'role' => 'kepala sekolah',
        ]);
        $this->call(KelasSeeder::class);
        User::factory(2)->hasSiswa()->create();
        User::factory(5)->hasGuru()->create();
        Kriteria::factory(10)->create();
        $getKriteria = Kriteria::where('type', 'siswa')->get();
        $totalBobot = $getKriteria->sum('bobot_kriteria');
        foreach ($getKriteria as $item) {
            $item->update([
                'fuzzy' => $item->bobot_kriteria / $totalBobot
            ]);
        }

        $getKriteria = Kriteria::where('type', 'kepsek')->get();
        $totalBobot = $getKriteria->sum('bobot_kriteria');
        foreach ($getKriteria as $item) {
            $item->update([
                'fuzzy' => $item->bobot_kriteria / $totalBobot
            ]);
        }
    }
}
