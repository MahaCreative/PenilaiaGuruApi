<?php

namespace Database\Factories;

use App\Models\Kelas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Siswa>
 */
class SiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nis' => rand(111111, 999999),
            'nama' => fake()->name(),
            'alamat' => fake()->address(),
            'tanggal_lahir' => fake()->dateTimeBetween('-11 years', '-8 years'),
            'jenis_kelamin' => fake()->randomElement(['laki-laki', 'perempuan']),
            'no_hp' => fake()->phoneNumber(),
            'foto_profile' => 'profile.png',
            'kelas_id' => fake()->randomElement(Kelas::all()->pluck('id'))
        ];
    }
}
