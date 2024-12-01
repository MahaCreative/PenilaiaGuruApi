<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Periode>
 */
class PeriodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bulan' => rand(1, 12),
            'tahun' => rand(2023, 2024),
            'tanggal_mulai' => fake()->dateTimeBetween('-1 years', 'now'),
            'tanggal_berakhir' => fake()->dateTimeBetween('-1 years', 'now'),
        ];
    }
}
