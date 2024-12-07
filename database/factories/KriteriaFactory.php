<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kriteria>
 */
class KriteriaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kd_penilaian' => rand(111, 999999),
            'nama_kriteria' => fake()->sentence(3),
            'bobot_kriteria' => fake()->randomElement([10, 20, 30, 40, 50]),
            'type' => 'siswa',
        ];
    }
}
